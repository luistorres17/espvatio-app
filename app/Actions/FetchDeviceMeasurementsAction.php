<?php

namespace App\Actions;

use App\Models\Device;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FetchDeviceMeasurementsAction
{
    public function execute(Device $device, string $range = '24h')
    {
        $costPerKwh = $device->team->cost_per_kwh ?? 0;

        // --- 1. DATOS DE GRÁFICAS Y PERÍODO (Respetan $range) ---
        
        $subFunction = 'subHours';
        $subValue = 24;
        $interval = '10 minutes'; // default para 24h
        $sqlInterval = '1 minute'; // Intervalo para cálculo de costo

        switch ($range) {
            case '1h':
                $subFunction = 'subHour';
                $subValue = 1;
                $interval = '1 minute';
                $sqlInterval = '1 minute';
                break;
            case '6h':
                $subFunction = 'subHours';
                $subValue = 6;
                $interval = '5 minutes';
                $sqlInterval = '1 minute';
                break;
            case '7d':
                $subFunction = 'subDays';
                $subValue = 7;
                $interval = '1 hour';
                $sqlInterval = '10 minutes';
                break;
            case '30d':
                $subFunction = 'subDays';
                $subValue = 30;
                $interval = '1 day';
                $sqlInterval = '1 hour';
                break;
        }

        $startDate = now()->$subFunction($subValue);

        // Consulta agregada para GRÁFICAS
        $resultsRange = DB::table('measurements')
            ->select(
                DB::raw("time_bucket('{$interval}', time) AS bucket"),
                DB::raw('AVG(power) as power'),
                DB::raw('AVG(voltage) as voltage'),
                DB::raw('AVG(current) as current'),
                DB::raw('MAX(energy) as energy')
            )
            ->where('device_id', $device->id)
            ->where('time', '>=', $startDate)
            ->groupBy('bucket')
            ->orderBy('bucket', 'asc')
            ->get();

        // Formateo para ApexCharts (eje datetime)
        $powerData = [];
        $voltageData = [];
        $currentData = [];
        $energyData = [];

        foreach ($resultsRange as $row) {
            
            $timestamp = Carbon::parse($row->bucket, 'UTC')
                ->setTimezone('America/Merida')
                ->timestamp * 1000;
            
            $powerData[] = [$timestamp, round($row->power, 2)];
            $voltageData[] = [$timestamp, round($row->voltage, 2)];
            $currentData[] = [$timestamp, round($row->current, 2)];
            $energyData[] = [$timestamp, round($row->energy, 2)];
        }
        
        
        // --- INICIO DE CORRECCIÓN (Cálculo de Consumo y Costo) ---
        // Volvemos al método de integración de POTENCIA, que es más preciso
        // para rangos cortos que restar el 'energy' total.
        
        $consumptionKwh = 0;
        
        // Definir los minutos del bucket para el cálculo (1 min, 10 min, 60 min)
        $minutesInBucket = 1;
        switch ($sqlInterval) {
            case '10 minutes': $minutesInBucket = 10; break;
            case '1 hour': $minutesInBucket = 60; break;
        }
        $hoursInBucket = $minutesInBucket / 60.0; // ej. 1/60, 10/60, 60/60

        // Energía (Wh) = Potencia Media (W) * Duración del bucket (en horas)
        // Energía (kWh) = (Potencia Media (W) * Duración (h)) / 1000
        $result = DB::selectOne("
            SELECT sum(avg_power * ?) / 1000.0 as total_kwh
            FROM (
                SELECT
                    avg(power) as avg_power
                FROM measurements
                WHERE device_id = ? AND time >= ?
                GROUP BY time_bucket(?, time)
            ) as power_over_time
        ", [$hoursInBucket, $device->id, $startDate, $sqlInterval]);

        $consumptionKwh = $result->total_kwh ?? 0;
        $estimatedCost = $consumptionKwh * $costPerKwh;
        // --- FIN DE CORRECCIÓN ---


        // --- 2. DATOS DE TARJETAS "ACTUAL" (Ignoran $range) ---
        $latest = $device->measurements()
            ->orderBy('time', 'desc')
            ->first();

        return [
            // Tarjeta Verde (Total Acumulado)
            'latest' => [
                'total_energy' => $latest ? round($latest->energy, 2) : 0,
            ],
            // Tarjetas Azul y Amarilla (Por Rango)
            'period' => [
                'consumption_kwh' => round($consumptionKwh, 3), // Más precisión
                'estimated_cost' => round($estimatedCost, 2),
            ],
            // Gráficas
            'charts' => [
                'power' => $powerData,
                'voltage' => $voltageData,
                'current' => $currentData,
                'energy' => $energyData,
            ],
        ];
    }
}