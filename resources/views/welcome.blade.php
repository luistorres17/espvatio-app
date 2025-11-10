<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EspVatio - Monitorización de Consumo Energético para ESP</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#4A90E2",
                        "background-light": "#f5f5f7",
                        "background-dark": "#1A1A1D",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-[#F5F5F7]">
    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="flex flex-1 justify-center px-4 sm:px-8 md:px-20 lg:px-40 py-5">
                <div class="layout-content-container flex w-full max-w-[960px] flex-1 flex-col">
                    
                    <!-- TopNavBar -->
                    <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-[#333333] px-4 sm:px-6 md:px-10 py-3">
                        <div class="flex items-center gap-4 text-white">
                            <div class="size-6 text-primary">
                                <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2L1 21h22L12 2zm-1.75 14h-1.5v-4h1.5v4zm3.5 0h-1.5v-4h1.5v4z"></path>
                                </svg>
                            </div>
                            <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em] font-display">EspVatio</h2>
                        </div>
                        
                        @if (Route::has('login'))
                            <div class="flex gap-2">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] font-display transition-colors hover:bg-primary/90">
                                        <span class="truncate">Dashboard</span>
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] font-display transition-colors hover:bg-primary/90">
                                        <span class="truncate">Registrarse</span>
                                    </a>
                                    <a href="{{ route('login') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-transparent border border-primary/50 text-white text-sm font-bold leading-normal tracking-[0.015em] font-display transition-colors hover:bg-primary/10">
                                        <span class="truncate">Iniciar Sesión</span>
                                    </a>
                                @endauth
                            </div>
                        @endif
                    </header>
                    
                    <!-- HeroSection -->
                    <div class="@container pt-10">
                        <div class="flex flex-col gap-6 px-4 py-10 @[480px]:gap-8 @[864px]:flex-row @[864px]:items-center">
                            <div class="flex flex-col gap-6 @[480px]:min-w-[400px] @[480px]:gap-8 @[864px]:justify-center">
                                <div class="flex flex-col gap-2 text-left">
                                    <h1 class="text-white text-4xl font-bold leading-tight tracking-[-0.033em] @[480px]:text-5xl @[480px]:font-bold @[480px]:leading-tight @[480px]:tracking-[-0.033em] font-display">
                                        Toma el control de tu consumo energético
                                    </h1>
                                    <h2 class="text-[#F5F5F7]/80 text-base font-normal leading-normal @[480px]:text-lg @[480px]:font-normal @[480px]:leading-normal font-display">
                                        Monitoriza y optimiza el consumo de tus dispositivos ESP en tiempo real con nuestra plataforma open-source.
                                    </h2>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 @[480px]:h-12 @[480px]:px-5 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] @[480px]:text-base @[480px]:font-bold @[480px]:leading-normal @[480px]:tracking-[0.015em] font-display transition-colors hover:bg-primary/90">
                                            <span class="truncate">Empezar Gratis</span>
                                        </a>
                                    @endif
                                    <a href="https://github.com/tu-usuario/espvatio" target="_blank" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 @[480px]:h-12 @[480px]:px-5 bg-transparent border border-primary/50 text-white text-sm font-bold leading-normal tracking-[0.015em] @[480px]:text-base @[480px]:font-bold @[480px]:leading-normal @[480px]:tracking-[0.015em] font-display transition-colors hover:bg-primary/10">
                                        <span class="truncate">Ver en GitHub</span>
                                    </a>
                                </div>
                            </div>
                            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg @[480px]:h-auto @[480px]:min-w-[400px] @[864px]:w-full" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD5ta5ZpG6iF_eXpkqXKPLtcuTDvlOtcaXI_oYjj-mWM8jHcI4w7TRZU2AItUQdhHVuLODwsc0s4O5p_xUoBQ6m1uOD03jY8A3URB2bEoJF7tPUKREjFd8qo7e3xtL8pdy005_vOGN2eezLfLno4OTGRcqGhlZEAlM8m4yr5OuPxlXYNqKOnCNA9b29c2CzTbY8Pprj4vkP1Q5G4YcOlshkO4dway_yqFbYtZ_-YKj2QWG9ZB04V1GgDXSdT4fhRcuEGfXSEuH9rXNK");'></div>
                        </div>
                    </div>
                    
                    <!-- SectionHeader for Features -->
                    <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-16 text-center font-display sm:text-3xl">
                        ¿Por qué EspVatio?
                    </h2>
                    
                    <!-- FeatureSection -->
                    <div class="flex flex-col gap-10 px-4 py-10 @container">
                        <div class="grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-4 p-0">
                            <div class="flex flex-1 gap-3 rounded-lg border border-[#333333] bg-[#252528] p-4 flex-col transition-transform hover:scale-105">
                                <div class="text-primary"><span class="material-symbols-outlined text-3xl">show_chart</span></div>
                                <div class="flex flex-col gap-1">
                                    <h3 class="text-white text-base font-bold leading-tight font-display">Monitorización en Tiempo Real</h3>
                                    <p class="text-[#F5F5F7]/70 text-sm font-normal leading-normal font-display">Observa el consumo de energía en vivo para tomar decisiones informadas al instante.</p>
                                </div>
                            </div>
                            <div class="flex flex-1 gap-3 rounded-lg border border-[#333333] bg-[#252528] p-4 flex-col transition-transform hover:scale-105">
                                <div class="text-primary"><span class="material-symbols-outlined text-3xl">code</span></div>
                                <div class="flex flex-col gap-1">
                                    <h3 class="text-white text-base font-bold leading-tight font-display">Open-Source y Flexible</h3>
                                    <p class="text-[#F5F5F7]/70 text-sm font-normal leading-normal font-display">Accede y modifica el código fuente para adaptar la plataforma a tus necesidades.</p>
                                </div>
                            </div>
                            <div class="flex flex-1 gap-3 rounded-lg border border-[#333333] bg-[#252528] p-4 flex-col transition-transform hover:scale-105">
                                <div class="text-primary"><span class="material-symbols-outlined text-3xl">auto_fix_high</span></div>
                                <div class="flex flex-col gap-1">
                                    <h3 class="text-white text-base font-bold leading-tight font-display">Configuración Sencilla</h3>
                                    <p class="text-[#F5F5F7]/70 text-sm font-normal leading-normal font-display">Instala nuestro firmware en tus ESP y empieza a monitorizar en minutos.</p>
                                </div>
                            </div>
                            <div class="flex flex-1 gap-3 rounded-lg border border-[#333333] bg-[#252528] p-4 flex-col transition-transform hover:scale-105">
                                <div class="text-primary"><span class="material-symbols-outlined text-3xl">pie_chart</span></div>
                                <div class="flex flex-col gap-1">
                                    <h3 class="text-white text-base font-bold leading-tight font-display">Visualización de Datos</h3>
                                    <p class="text-[#F5F5F7]/70 text-sm font-normal leading-normal font-display">Analiza patrones y tendencias con gráficos y dashboards claros e intuitivos.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Setup/CTASection -->
                    <div class="@container">
                        <div class="flex flex-col items-center justify-end gap-6 px-4 py-16 @[480px]:gap-8 @[480px]:px-10 @[480px]:py-20 text-center">
                            <div class="flex flex-col gap-2 max-w-[720px]">
                                <h2 class="text-white tracking-light text-[32px] font-bold leading-tight @[480px]:text-4xl @[480px]:font-bold @[480px]:leading-tight @[480px]:tracking-[-0.033em] font-display">
                                    Prepara tus Dispositivos en Minutos
                                </h2>
                                <p class="text-[#F5F5F7]/80 text-base font-normal leading-normal font-display">
                                    Descarga nuestro firmware open-source desde GitHub y flashea tus dispositivos ESP para empezar a enviar datos a la plataforma.
                                </p>
                            </div>
                            <div class="flex justify-center">
                                <a href="https://github.com/tu-usuario/espvatio" target="_blank" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] font-display transition-colors hover:bg-primary/90">
                                    <span class="truncate">Descargar Firmware en GitHub</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Final CTA Section -->
                    <div class="@container">
                        <div class="flex flex-col items-center justify-end gap-6 px-4 py-10 bg-[#252528] rounded-xl @[480px]:gap-8 @[480px]:px-10 @[480px]:py-16 text-center">
                            <div class="flex flex-col gap-2 max-w-[720px]">
                                <h2 class="text-white tracking-light text-[28px] font-bold leading-tight @[480px]:text-3xl @[480px]:font-bold @[480px]:leading-tight @[480px]:tracking-[-0.033em] font-display">
                                    ¿Listo para empezar a ahorrar energía?
                                </h2>
                            </div>
                            <div class="flex justify-center">
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] font-display transition-colors hover:bg-primary/90">
                                        <span class="truncate">Empieza a Monitorizar Ahora</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <footer class="mt-20 border-t border-[#333333] py-8 px-4">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-center">
                            <p class="text-sm text-[#F5F5F7]/60 font-display">© {{ date('Y') }} EspVatio. Todos los derechos reservados.</p>
                            <div class="flex gap-4">
                                <a class="text-sm text-[#F5F5F7]/60 hover:text-primary font-display transition-colors" href="#">Política de Privacidad</a>
                                <a class="text-sm text-[#F5F5F7]/60 hover:text-primary font-display transition-colors" href="#">Términos de Servicio</a>
                            </div>
                        </div>
                    </footer>
                    
                </div>
            </div>
        </div>
    </div>
</body>
</html>