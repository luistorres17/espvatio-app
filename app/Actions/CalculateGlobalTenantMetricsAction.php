<?php

namespace App\Actions;

use App\Models\Team;
use App\Models\Measurement;
use Illuminate\Support\Facades\DB;

class CalculateGlobalTenantMetricsAction
{
    /**
     * Ejecuta la acción.
     * LÓGICA: Se ignora el $range. Todas las métricas se basan
     * en la última fila registrada de CADA dispositivo.
     */
    public function execute(Team $team, string $range = '24h')
    {
        $deviceIds = $team->devices()->pluck('id');
        $costPerKwh = $team->cost_per_kwh ?? 0;
        $deviceCount = $deviceIds->count();

        $totalEnergy = 0; 

        if ($deviceCount > 0) {
            
            // Subconsulta para encontrar el MAX(time) por device_id
            $latestTimes = Measurement::select('device_id', DB::raw('MAX(time) as max_time'))
                ->whereIn('device_id', $deviceIds)
                ->whereNotNull('energy') // Solo dispositivos que reportan energía
                ->groupBy('device_id');

            // Query principal que une los resultados para sumar la última fila de cada uno
            // --- CORRECCIÓN --- Se elimina el cálculo de 'power'
            $totalEnergy = Measurement::joinSub($latestTimes, 'latest_times', function ($join) {
                $join->on('measurements.device_id', '=', 'latest_times.device_id')
                     ->on('measurements.time', '=', 'latest_times.max_time');
            })
            ->whereIn('measurements.device_id', $deviceIds)
            ->sum('measurements.energy');
        }

        return [
            // Solo devolvemos los 3 datos que usará el nuevo dashboard
            'total_consumption_kwh' => round($totalEnergy, 2),
            'total_estimated_cost' => round($totalEnergy * $costPerKwh, 2),
            'device_count' => $deviceCount,
        ];
    }
}