<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASERNUM BACKTEST - By MARS OHOCHI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'gradient': 'gradient 15s ease infinite',
                        'fadeInUp': 'fadeInUp 1s ease-out',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px) rotate(0deg)'
                            },
                            '50%': {
                                transform: 'translateY(-20px) rotate(180deg)'
                            }
                        },
                        gradient: {
                            '0%, 100%': {
                                'background-position': '0% 50%'
                            },
                            '50%': {
                                'background-position': '100% 50%'
                            }
                        },
                        fadeInUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        }
                    },
                    backgroundSize: {
                        '300%': '300%',
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500">

    <!-- Animated Background -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <!-- Floating shapes -->
        <div class="absolute w-20 h-20 bg-white/10 rounded-full top-1/4 left-1/4 animate-float"></div>
        <div class="absolute w-32 h-32 bg-white/10 rounded-full top-3/4 right-1/4 animate-float"
            style="animation-delay: 2s;"></div>
        <div class="absolute w-16 h-16 bg-white/10 rounded-full bottom-1/3 left-1/5 animate-float"
            style="animation-delay: 4s;"></div>
        <div class="absolute w-24 h-24 bg-white/10 rounded-full top-1/6 right-1/3 animate-float"
            style="animation-delay: 1s;"></div>

        <!-- Gradient orbs -->
        <div
            class="absolute w-96 h-96 rounded-full bg-gradient-to-r from-blue-400/30 to-purple-600/30 blur-3xl -top-48 -left-48 animate-pulse-slow">
        </div>
        <div class="absolute w-96 h-96 rounded-full bg-gradient-to-r from-pink-400/30 to-red-600/30 blur-3xl -bottom-48 -right-48 animate-pulse-slow"
            style="animation-delay: 2s;"></div>
    </div>

    <!-- Main Content -->
    <div class="relative min-h-screen flex items-center justify-center p-2 md:p-4">
        <div class="max-w-4xl w-full">

            <!-- Hero Card -->
            <div
                class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-4 md:p-8 text-center animate-fadeInUp overflow-hidden relative">

                <!-- Top gradient border -->
                <div
                    class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 bg-300% animate-gradient">
                </div>

                <!-- Logo -->
                <div
                    class="w-16 h-16 md:w-20 md:h-20 mx-auto mb-4 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl md:rounded-2xl flex items-center justify-center text-2xl md:text-3xl text-white shadow-xl transform hover:scale-105 transition-transform duration-300 animate-float">
                    ⚡
                </div>
                <!-- Title -->
                <h1
                    class="text-3xl md:text-4xl lg:text-5xl font-black mb-3 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent leading-tight">
                    ASERNUM BACKTEST
                </h1>

                <!-- Subtitle -->
                <p class="text-sm md:text-base lg:text-lg text-slate-600 mb-6 max-w-2xl mx-auto leading-relaxed">
                    Ce projet est destiné à évaluer les aptitudes d'un candidat développeur à concevoir une API RESTful
                    robuste et intuitive, tout en respectant les meilleures pratiques de
                    développement. Explorez la documentation complète ou consultez le guide de démarrage
                    pour commencer votre intégration. Un rapport de couverture des tests est également disponible pour
                    évaluer la qualité du code.
                </p>

                <!-- Action Buttons -->
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center mb-8">
                    <!-- Swagger -->
                    <a href="/api/documentation"
                        class="group inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000">
                        </div>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="relative z-10">Documentation Swagger</span>
                    </a>

                    <!-- Guide -->
                    <a href="/getting-started"
                        class="group inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-6 py-3 rounded-xl border-2 border-slate-200 hover:border-slate-300 shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Guide de Démarrage
                    </a>

                    <!-- Coverage -->
                    <a href="/coverage"
                        class="group inline-flex items-center gap-2 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000">
                        </div>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 17a4 4 0 004 4h10a4 4 0 004-4V7a4 4 0 00-4-4H7a4 4 0 00-4 4v10zm5-3h6m-6-4h6" />
                        </svg>
                        <span class="relative z-10">Rapport des Tests</span>
                    </a>
                </div>



                <!-- Features Grid -->
                <div class="grid md:grid-cols-3 gap-4 pt-6 border-t border-slate-200">
                    <!-- Feature 1 -->
                    <div
                        class="group p-4 rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 hover:from-indigo-50 hover:to-purple-50 border border-slate-200 hover:border-indigo-200 transform hover:-translate-y-1 transition-all duration-300">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mb-3 mx-auto group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2 text-sm">API RESTful</h3>
                        <p class="text-slate-600 text-xs leading-relaxed">Interface moderne et intuitive suivant les
                            standards REST</p>
                    </div>

                    <!-- Feature 2 -->
                    <div
                        class="group p-4 rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 hover:from-indigo-50 hover:to-purple-50 border border-slate-200 hover:border-indigo-200 transform hover:-translate-y-1 transition-all duration-300">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mb-3 mx-auto group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2 text-sm">Documentation Interactive</h3>
                        <p class="text-slate-600 text-xs leading-relaxed">Testez vos endpoints directement depuis la
                            documentation Swagger</p>
                    </div>

                    <!-- Feature 3 -->
                    <div
                        class="group p-4 rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 hover:from-indigo-50 hover:to-purple-50 border border-slate-200 hover:border-indigo-200 transform hover:-translate-y-1 transition-all duration-300">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mb-3 mx-auto group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2 text-sm">Démarrage Rapide</h3>
                        <p class="text-slate-600 text-xs leading-relaxed">Guide complet pour intégrer l'API en quelques
                            minutes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Intersection Observer pour les animations d'entrée
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fadeInUp');
                }
            });
        }, observerOptions);

        // Observer les éléments features
        document.querySelectorAll('.grid > div').forEach((el, index) => {
            el.style.animationDelay = `${index * 0.1}s`;
            observer.observe(el);
        });

        // Effet parallax subtil pour les formes flottantes
        document.addEventListener('mousemove', (e) => {
            const shapes = document.querySelectorAll(
                '.absolute.w-20, .absolute.w-32, .absolute.w-16, .absolute.w-24');
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;

            shapes.forEach((shape, index) => {
                const speed = (index + 1) * 2;
                const x = (mouseX - 0.5) * speed;
                const y = (mouseY - 0.5) * speed;

                shape.style.transform += ` translate3d(${x}px, ${y}px, 0)`;
            });
        });
    </script>
</body>

</html>
