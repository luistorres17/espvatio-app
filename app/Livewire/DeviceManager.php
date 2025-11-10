<?php

namespace App\Livewire;

use App\Actions\CreateProvisioningToken;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DeviceManager extends Component
{
    public $showingAddDeviceModal = false;
    public $provisioningToken = null;
    public $confirmingDeviceDeletion = false;
    public $deviceToDeleteId = null;

    /**
     * Muestra el modal y genera el token directamente.
     */
    public function showAddDeviceModal(CreateProvisioningToken $creator)
    {
        $this->reset('provisioningToken');
        
        try {
            $token = $creator(Auth::user()->currentTeam);
            $this->provisioningToken = $token->token;
        } catch (\Exception $e) {
            Log::error('Error al generar token directamente: ' . $e->getMessage());
            $this->provisioningToken = 'Error al generar el token.';
        }

        $this->showingAddDeviceModal = true;
    }
    
    public function confirmDeviceDeletion($deviceId)
    {
        $this->confirmingDeviceDeletion = true;
        $this->deviceToDeleteId = $deviceId;
    }
    
    public function deleteDevice()
    {
        if ($this->deviceToDeleteId) {
            Auth::user()->currentTeam->devices()->where('id', $this->deviceToDeleteId)->firstOrFail()->delete();
        }

        $this->confirmingDeviceDeletion = false;
        $this->deviceToDeleteId = null;
    }

    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        // --- INICIO DE CORRECCIÓN (Bug Activo / N+1) ---
        // Se añade 'with('latestMeasurement')' para la comprobación de estado
        $devices = Auth::user()->currentTeam->devices()
            ->with('latestMeasurement')
            ->latest()
            ->get();
        // --- FIN DE CORRECCIÓN ---

        return view('livewire.device-manager', [
            'devices' => $devices,
        ])->layout('layouts.app');
    }
}