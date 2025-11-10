<div wire:poll.10s="loadData" class="p-4 sm:p-6 lg:p-8">

    {{-- --- INICIO: Mensajes de Feedback --- --}}
    <div class="mb-6">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-700 dark:border-green-600 dark:text-green-100" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif
        @if (session()->has('error'))
             <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative dark:bg-red-700 dark:border-red-600 dark:text-red-100" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
    </div>
    {{-- --- FIN: Mensajes de Feedback --- --}}


    {{-- Controles (Panel de Control y Filtro) --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        
        {{-- --- INICIO: Panel de Control (Botones) --- --}}
        <div class="w-full lg:w-auto">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-3">Panel de Control</h3>
            <div class="flex items-center gap-3">
                <x-button wire:click="sendLedCommand('ON')" class="bg-green-500 hover:bg-green-600 text-white">
                    Activar
                </x-button>
                <x-button wire:click="sendLedCommand('OFF')" class="bg-yellow-500 hover:bg-yellow-600 text-white">
                    Desactivar
                </x-button>
            </div>
        </div>
        {{-- --- FIN: Panel de Control --- --}}

        {{-- Filtro de Rango --}}
        <div class="w-full lg:w-auto">
            <label for="range-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Rango de Tiempo
            </label>
            <select id="range-select"
                    wire:model.live="range"
                    wire:change="setRange($event.target.value)"
                    class="block w-full lg:w-64 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="1h">Última hora</option>
                <option value="6h">Últimas 6 horas</option>
                <option value="24h">Últimas 24 horas</option>
                <option value="7d">Últimos 7 días</option>
                <option value="30d">Últimos 30 días</option>
            </select>
        </div>
    </div>

    {{-- Tarjetas (3 columnas) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Tarjeta 1: Consumo (Rango) --}}
        <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-blue-500 rounded-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 ml-5">
                    <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                        Consumo ({{ $range }})
                    </dt>
                    <dd class="mt-1">
                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ $consumptionKwh }} <span class="text-lg font-normal">kWh</span>
                        </div>
                    </dd>
                </div>
            </div>
        </div>

        {{-- Tarjeta 2: Costo (Rango) --}}
        <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-yellow-500 rounded-md">
                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.536A4.5 4.5 0 0 0 12 21a4.5 4.5 0 0 0 2.121-1.282l.879-.536M6.343 18.343a4.5 4.5 0 0 1 6.364 0l.001.001M12 19.5v-1.5m-3-5.357A4.5 4.5 0 0 1 12 10.5a4.5 4.5 0 0 1 4.242 2.643M12 3v1.5m0 1.5A4.5 4.5 0 0 1 16.242 8.643m0 0l.879.536a4.5 4.5 0 0 0 2.121 1.282m0 0M12 5.25a4.5 4.5 0 0 0-4.242 2.643m0 0l-.879.536A4.5 4.5 0 0 0 4.758 10.5M3 15m0 0l.879-.536a4.5 4.5 0 0 1 2.121-1.282m0 0c.265.18.55.326.86.442M21 15m0 0l-.879-.536a4.5 4.5 0 0 0-2.121-1.282m0 0c-.265.18-.55.326-.86.442m0 0M12 12.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <div class="flex-1 ml-5">
                    <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                        Costo Est. ({{ $range }})
                    </dt>
                    <dd class="mt-1">
                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                            $ {{ $estimatedCost }}
                        </div>
                    </dd>
                </div>
            </div>
        </div>

        {{-- Tarjeta 3: Consumo Total (Acumulado) --}}
        <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-green-500 rounded-md">
                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                    </svg>
                </div>
                <div class="flex-1 ml-5">
                    <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                        Consumo Total
                    </dt>
                    <dd class="mt-1">
                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ $totalEnergy }} <span class="text-lg font-normal">kWh</span>
                        </div>
                    </dd>
                </div>
            </div>
        </div>
    </div>


    {{-- Contenedores de Gráficas --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" wire:ignore>
        <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">Potencia (W)</h3>
            <div id="powerChart" class="w-full" style="height: 300px;"></div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">Voltaje (V)</h3>
            <div id="voltageChart" class="w-full" style="height: 300px;"></div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">Corriente (A)</h3>
            <div id="currentChart" class="w-full" style="height: 300px;"></div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">Energía (kWh)</h3>
            <div id="energyChart" class="w-full" style="height: 300px;"></div>
        </div>
    </div>

</div>

@assets
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endassets

@script
<script>
    let powerChart, voltageChart, currentChart, energyChart;
    let chartsInitialized = false;

    document.addEventListener('livewire:navigated', () => {

        const initCharts = () => {
            
            if (chartsInitialized) return;
            
            const isDarkMode = document.documentElement.classList.contains('dark');
            const labelColor = isDarkMode ? '#9CA3AF' : '#6B7280';
            const gridColor = isDarkMode ? '#374151' : '#F3F4F6';
            const tooltipTheme = isDarkMode ? 'dark' : 'light';

            const baseOptions = {
                chart: {
                    type: 'area',
                    height: '300px',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    animations: { enabled: true, easing: 'linear', dynamicAnimation: { speed: 1000 } }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                markers: { size: 0 },
                xaxis: {
                    type: 'datetime',
                    labels: {
                        style: { colors: labelColor },
                        format: 'hh:mm TT',
                        datetimeFormatter: {
                            hour: 'hh:mm TT',
                        },
                        datetimeUTC: false
                    }
                },
                yaxis: {
                    labels: {
                        style: { colors: labelColor },
                        formatter: (val) => val.toFixed(0)
                    }
                },
                tooltip: {
                    x: { format: 'dd MMM yyyy - hh:mm TT' },
                    theme: tooltipTheme
                },
                grid: {
                    borderColor: gridColor,
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                        stops: [0, 90, 100]
                    }
                },
            };

            const powerOptions = {
                ...baseOptions,
                series: [{ name: 'Potencia', data: [] }],
                colors: ['#EF4444'],
                tooltip: { ...baseOptions.tooltip, y: { formatter: (val) => val.toFixed(0) + ' W' } }
            };

            const voltageOptions = {
                ...baseOptions,
                series: [{ name: 'Voltaje', data: [] }],
                colors: ['#3B82F6'],
                tooltip: { ...baseOptions.tooltip, y: { formatter: (val) => val.toFixed(1) + ' V' } }
            };

            const currentOptions = {
                ...baseOptions,
                series: [{ name: 'Corriente', data: [] }],
                colors: ['#EAB308'],
                yaxis: { ...baseOptions.yaxis, labels: { ...baseOptions.yaxis.labels, formatter: (val) => val.toFixed(2) } },
                tooltip: { ...baseOptions.tooltip, y: { formatter: (val) => val.toFixed(2) + ' A' } }
            };

            const energyOptions = {
                ...baseOptions,
                series: [{ name: 'Energía', data: [] }],
                colors: ['#22C55E'],
                yaxis: { ...baseOptions.yaxis, labels: { ...baseOptions.yaxis.labels, formatter: (val) => val.toFixed(2) } },
                tooltip: { ...baseOptions.tooltip, y: { formatter: (val) => val.toFixed(2) + ' kWh' } }
            };

            if (document.querySelector("#powerChart")._apexcharts) return;

            powerChart = new ApexCharts(document.querySelector("#powerChart"), powerOptions);
            voltageChart = new ApexCharts(document.querySelector("#voltageChart"), voltageOptions);
            currentChart = new ApexCharts(document.querySelector("#currentChart"), currentOptions);
            energyChart = new ApexCharts(document.querySelector("#energyChart"), energyOptions);
            
            powerChart.render();
            voltageChart.render();
            currentChart.render();
            energyChart.render();

            chartsInitialized = true;
        };

        $wire.on('updateCharts', (event) => {
            // Si las gráficas no existen, inicializarlas primero
            if (!chartsInitialized) {
                initCharts();
            }

            if (powerChart && event.data.power) {
                powerChart.updateSeries([{ data: event.data.power }]);
            }
            if (voltageChart && event.data.voltage) {
                voltageChart.updateSeries([{ data: event.data.voltage }]);
            }
            if (currentChart && event.data.current) {
                currentChart.updateSeries([{ data: event.data.current }]);
            }
            if (energyChart && event.data.energy) {
                energyChart.updateSeries([{ data: event.data.energy }]);
            }
        });

        // Forzar carga inicial de datos
        $wire.call('loadData');
    });
</script>
@endscript