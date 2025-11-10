<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProvisionDeviceRequest;
use App\Http\Resources\DeviceResource; // Asumo que tienes un Resource
use App\Models\Device;
use App\Models\ProvisioningToken;
//facede de config
use Illuminate\Support\Facades\Config;

class ProvisionController extends Controller
{
    /**
     * Aprovisiona un nuevo dispositivo o reactiva uno existente.
     */
    public function store(ProvisionDeviceRequest $request)
    {
        // 1. Validar el token de aprovisionamiento
        $token = ProvisioningToken::where('token', $request->input('provisioning_token'))->firstOrFail();

        // 2. Buscar dispositivo (incluyendo borrados)
        $device = Device::withTrashed()
            ->where('serial_number', $request->input('chip_id'))
            ->first();

        $wasCreated = false; // <-- Variable de control

        if ($device) {
            // 3a. Si existe, restaurar y/o actualizar
            if ($device->trashed()) {
                $device->restore();
            }

            $device->update([
                'team_id' => $token->team_id,
                'status' => 'online',
            ]);
        } else {
            // 3b. Si no existe, crearlo
            $device = Device::create([
                'team_id' => $token->team_id,
                'name' => 'Device ' . $request->input('chip_id'),
                'serial_number' => $request->input('chip_id'),
                'status' => 'online',
            ]);
            $wasCreated = true;
        }

        // 4. Consumir (borrar) el token de aprovisionamiento
        $token->delete();

        // --- INICIO DE MODIFICACIÓN ---

        // 5. Obtener el recurso del dispositivo
        $resource = new DeviceResource($device);
        $deviceData = $resource->resolve(); // Convertir el recurso a array

        // 6. Obtener configuración MQTT desde config/mqtt.php
        // Se asume que se usa la conexión 'default'
        $mqttConfig = [
            'host'     => Config::get('mqtt.connections.default.host'),
            'port'     => (int) Config::get('mqtt.connections.default.port'),
            'username' => Config::get('mqtt.connections.default.username'),
            'password' => Config::get('mqtt.connections.default.password'),
        ];

        // 7. Devolver respuesta JSON combinada con 200 OK
        return response()->json([
            'device' => $deviceData,
            'mqtt'   => $mqttConfig,
        ], 200); // Forzar 200 OK como se requería

        // --- FIN DE MODIFICACIÓN ---
    }
}