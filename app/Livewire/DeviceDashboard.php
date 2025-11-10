<?php

namespace App\Livewire;

use App\Actions\FetchDeviceMeasurementsAction;
use App\Models\Device;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\View\View;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class DeviceDashboard extends Component
{
    public Device $device;
    public string $range = '24h';

    // Propiedades para las tarjetas
    public float $totalEnergy = 0;
    public float $consumptionKwh = 0;
    public float $estimatedCost = 0;

    protected $listeners = ['refreshData' => 'loadData'];

    public function mount(Device $device)
    {
        $this->device = $device;
        $this->loadData();
    }

    public function setRange(string $range)
    {
        $this->range = $range;
        $this->loadData();
    }

    /**
     * Carga los datos de la acción y los despacha al frontend
     * a través de un evento de Livewire.
     */
    public function loadData()
    {
        $action = new FetchDeviceMeasurementsAction();
        $data = $action->execute($this->device, $this->range);

        $this->totalEnergy = $data['latest']['total_energy'];
        $this->consumptionKwh = $data['period']['consumption_kwh'];
        $this->estimatedCost = $data['period']['estimated_cost'];

        $this->dispatch('updateCharts', data: $data['charts']);
    }

    /**
     * Envía un comando MQTT (LED_ON / LED_OFF) al dispositivo.
     */
    public function sendLedCommand($command)
    {
        if (empty($this->device->serial_number)) {
            session()->flash('error', 'Este dispositivo no tiene un serial_number registrado.');
            return;
        }

        $topic = "espvatio/devices/{$this->device->serial_number}/commands";
        $payload = ($command === 'ON') ? 'LED_ON' : 'LED_OFF';

        try {
            $config = config('mqtt.connections.default');

            // --- INICIO DE CORRECCIÓN ---
            // Forzamos un Client ID único para el publicador
            // para evitar conflictos con el listener.
            $publisherClientId = 'laravel_publisher_' . uniqid();
            
            $mqtt = new MqttClient(
                $config['host'],
                $config['port'],
                $publisherClientId // Usar el Client ID único
            );
            // --- FIN DE CORRECCIÓN ---

            $settings = (new ConnectionSettings)
                ->setUsername($config['username'])
                ->setPassword($config['password'])
                ->setKeepAliveInterval(10)
                ->setUseTls($config['connection_settings']['tls']['enabled'] ?? false);

            $mqtt->connect($settings, true);
            $mqtt->publish($topic, $payload, 1);
            $mqtt->disconnect();

            session()->flash('message', "Comando '{$payload}' enviado correctamente.");
            Log::info("MQTT Publish → {$topic}: {$payload}");
        } catch (\Throwable $e) {
            Log::error('Error al publicar MQTT: ' . $e->getMessage());
            session()->flash('error', 'Error al enviar comando MQTT: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('livewire.device-dashboard')
            ->layout('layouts.app', [
                'header' => new \Illuminate\Support\HtmlString(
                    '<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">' .
                        __('Dispositivo: ') . e($this->device->name) .
                    '</h2>'
                )
            ]);
    }
}