<?php

use App\Actions\CalculateGlobalTenantMetricsAction;
use App\Livewire\DeviceDashboard;
use App\Livewire\DeviceManager;
use App\Models\Device;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // --- INICIO DE CORRECCIÓN ---
    // Se elimina la lógica de 24h y 30d. Se hace una sola llamada.
    Route::get('/dashboard', function (CalculateGlobalTenantMetricsAction $metricsAction) {
        
        // Llamamos a la acción (el rango es irrelevante, pero lo pasamos)
        $metrics = $metricsAction->execute(auth()->user()->currentTeam, '30d');

        return view('dashboard', [
            'total_consumption_kwh' => $metrics['total_consumption_kwh'],
            'total_estimated_cost' => $metrics['total_estimated_cost'],
            'device_count' => $metrics['device_count'],
        ]);
    })->name('dashboard');
    // --- FIN DE CORRECCIÓN ---

    Route::get('/devices', DeviceManager::class)->name('devices.index');
    Route::get('/devices/{device}', DeviceDashboard::class)->name('devices.show');

});