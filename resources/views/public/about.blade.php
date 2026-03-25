@extends('layouts.public')

@section('title', "À propos · Win's Events")

@section('content')
    <!-- Premium Hero Section -->
    <div class="mb-16 md:mb-24 -mt-4 sm:-mt-8 bg-stone-950">
        <div class="overflow-hidden border-0 p-0">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div class="relative flex flex-col justify-center p-12 lg:p-20 z-10">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-400/20 to-orange-600/5"></div>
                    
                    <div class="relative">
                        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-orange-500/30 bg-orange-500/10 px-4 py-1.5 backdrop-blur-md">
                            <span class="text-xs font-bold uppercase tracking-[0.2em] text-orange-400">L'Art de Recevoir</span>
                        </div>
                        
                        <h1 class="mb-8 text-5xl font-black leading-[1.05] tracking-tighter text-white sm:text-6xl">
                            Win's Events<br />
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Créateur d'Instants.</span>
                        </h1>
                        
                        <p class="mb-10 text-lg leading-relaxed text-stone-400 sm:text-xl max-w-lg">
                            Depuis nos débuts, nous concevons des soirées mémorables. De la billetterie fluide à l'accueil premium, nous orchestrons chaque détail pour que l'expérience soit absolue.
                        </p>
                        
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('public.events.index') }}" class="btn-primary px-8 py-3.5 shadow-sunset">
                                Découvrir l'agenda
                            </a>
                            <a href="#valeurs" class="btn-glass px-8 py-3.5 rounded-2xl">
                                Notre vision
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="relative hidden lg:block bg-stone-900 overflow-hidden">
                    <img id="hero-image" src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=1000&q=80" alt="Ambiance Soirée" class="h-full w-full object-cover opacity-80 mix-blend-luminosity hover:mix-blend-normal hover:scale-105 transition-all duration-1000" />
                    <div class="absolute inset-0 bg-gradient-to-l from-transparent via-stone-950/40 to-stone-950"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pb-16">

    <!-- Editorial Story Section -->
    <section class="mb-24 lg:mb-32">
        <div class="mx-auto max-w-3xl text-center px-4">
            <p class="mb-4 text-xs font-bold uppercase tracking-[0.2em] text-orange-600">Notre Histoire</p>
            <h2 class="mb-8 text-4xl font-black tracking-tighter text-stone-900 sm:text-5xl">La passion pour l'inoubliable.</h2>
            <p class="text-xl font-medium leading-relaxed text-stone-500 mb-6">
                Win's Events est né d'une vision simple : élever le standard des soirées festives. Nous croyons que la magie d'un événement commence bien avant d'y entrer, dès l'achat du billet.
            </p>
            <p class="text-lg leading-relaxed text-stone-400">
                Aujourd'hui, nous combinons une technologie de billetterie de pointe avec une organisation sans faille pour que les créateurs de soirées se concentrent sur l'ambiance, et les invités sur l'instant présent.
            </p>
        </div>
    </section>

    <!-- Bento Values Section -->
    <section id="valeurs" class="mb-24 lg:mb-32">
        <div class="mb-16 text-center">
            <p class="mb-4 text-xs font-bold uppercase tracking-[0.2em] text-orange-600">L'Engagement</p>
            <h2 class="text-4xl font-black tracking-tighter text-stone-900 sm:text-5xl">Ce qui nous distingue</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card-premium group relative overflow-hidden p-10 bg-white">
                <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:scale-110 transition-transform duration-500">
                    <span class="text-9xl">🎉</span>
                </div>
                <div class="mb-8 inline-flex h-16 w-16 items-center justify-center rounded-[1.5rem] bg-orange-50/80 text-3xl shadow-inner group-hover:bg-orange-100/80 transition-colors">
                    ✨
                </div>
                <h3 class="mb-4 text-2xl font-black text-stone-900 group-hover:text-orange-600 transition-colors">Production</h3>
                <p class="text-base font-medium leading-relaxed text-stone-500 relative z-10">
                    Du concept à la scène, nous orchestrons chaque étape pour créer des ambiances uniques qui marquent les esprits.
                </p>
            </div>
            
            <div class="card-premium group relative overflow-hidden p-10 bg-white">
                <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:scale-110 transition-transform duration-500">
                    <span class="text-9xl">⚡</span>
                </div>
                <div class="mb-8 inline-flex h-16 w-16 items-center justify-center rounded-[1.5rem] bg-orange-50/80 text-3xl shadow-inner group-hover:bg-orange-100/80 transition-colors">
                    🎟️
                </div>
                <h3 class="mb-4 text-2xl font-black text-stone-900 group-hover:text-orange-600 transition-colors">Billetterie</h3>
                <p class="text-base font-medium leading-relaxed text-stone-500 relative z-10">
                    Un parcours d'achat sans friction. Réservation instantanée, QR code sécurisé, et entrée express. L'attente appartient au passé.
                </p>
            </div>
            
            <div class="card-premium group relative overflow-hidden p-10 bg-white">
                <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:scale-110 transition-transform duration-500">
                    <span class="text-9xl">🛡️</span>
                </div>
                <div class="mb-8 inline-flex h-16 w-16 items-center justify-center rounded-[1.5rem] bg-orange-50/80 text-3xl shadow-inner group-hover:bg-orange-100/80 transition-colors">
                    🛡️
                </div>
                <h3 class="mb-4 text-2xl font-black text-stone-900 group-hover:text-orange-600 transition-colors">Logistique</h3>
                <p class="text-base font-medium leading-relaxed text-stone-500 relative z-10">
                    Une sécurité intransigeante et un accueil irréprochable. Laissez-vous porter par la musique en toute sérénité.
                </p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="mb-24 lg:mb-32">
        <div class="card-premium relative overflow-hidden border-orange-100 bg-gradient-to-br from-white to-orange-50/30 p-12 lg:p-20 text-center">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-12">
                <div class="flex flex-col items-center justify-center">
                    <p class="text-5xl font-black text-orange-500 mb-2">120<span class="text-3xl">+</span></p>
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-500">Soirées Épiques</p>
                </div>
                <div class="flex flex-col items-center justify-center border-l-0 lg:border-l border-stone-200/50">
                    <p class="text-5xl font-black text-orange-500 mb-2">8k<span class="text-3xl">+</span></p>
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-500">Billets Scannés</p>
                </div>
                <div class="flex flex-col items-center justify-center border-l-0 lg:border-l border-stone-200/50">
                    <p class="text-5xl font-black text-orange-500 mb-2">4.9</p>
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-500">Note Globale</p>
                </div>
                <div class="flex flex-col items-center justify-center border-l-0 lg:border-l border-stone-200/50">
                    <p class="text-5xl font-black text-orange-500 mb-2">98<span class="text-3xl">%</span></p>
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-500">Taux de Présence</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="mb-24 lg:mb-32">
        <div class="mb-16 text-center">
            <p class="mb-4 text-xs font-bold uppercase tracking-[0.2em] text-orange-600">Avis</p>
            <h2 class="text-4xl font-black tracking-tighter text-stone-900 sm:text-5xl">Ceux qui le disent le mieux</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="card-premium p-10 bg-white relative">
                <span class="absolute top-8 right-8 text-6xl text-stone-100 font-serif leading-none">"</span>
                <p class="relative z-10 text-xl font-medium leading-relaxed text-stone-600 italic mb-10">
                    Mettre en place la billetterie a pris 5 minutes. Le soir venu, les scans étaient instantanés. Plus jamais je ne passerai par une autre plateforme pour mes événements.
                </p>
                <div class="flex items-center gap-4 border-t border-stone-100 pt-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-orange-100 text-orange-600 font-black text-xl">J</div>
                    <div>
                        <p class="text-base font-bold text-stone-900">Julien M.</p>
                        <p class="text-sm font-medium text-stone-500">Organisateur Indépendant</p>
                    </div>
                </div>
            </div>
            
            <div class="card-premium p-10 bg-white relative">
                <span class="absolute top-8 right-8 text-6xl text-stone-100 font-serif leading-none">"</span>
                <p class="relative z-10 text-xl font-medium leading-relaxed text-stone-600 italic mb-10">
                    Habituellement je déteste acheter des places et faire la queue. Win's Events a rendu le process tellement premium que je réserve maintenant les yeux fermés.
                </p>
                <div class="flex items-center gap-4 border-t border-stone-100 pt-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-orange-100 text-orange-600 font-black text-xl">C</div>
                    <div>
                        <p class="text-base font-bold text-stone-900">Clara P.</p>
                        <p class="text-sm font-medium text-stone-500">Habituée des soirées</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visual Gallery Section -->
    <section class="mb-24 lg:mb-32 w-full full-width">
        <div class="mb-16 text-center">
            <p class="mb-4 text-xs font-bold uppercase tracking-[0.2em] text-orange-600">Galerie</p>
            <h2 class="text-4xl font-black tracking-tighter text-stone-900 sm:text-5xl">Ambiance visuelle</h2>
        </div>

        <div id="gallery-container" class="grid grid-cols-2 md:grid-cols-3 gap-6 auto-rows-[250px] lg:auto-rows-[300px]">
            <!-- JS loaded images -->
        </div>
    </section>

    <!-- Final CTA -->
    <section class="mb-16">
        <div class="card-premium overflow-hidden bg-gradient-to-br from-stone-900 to-stone-950 p-12 text-center shadow-xl sm:p-20 relative">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=1600&q=80')] opacity-10 bg-cover bg-center mix-blend-screen"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-900/80 to-stone-900/20"></div>
            
            <div class="relative z-10 flex flex-col items-center">
                <h2 class="mb-6 text-4xl font-black tracking-tighter text-white sm:text-5xl">Prêt pour l'expérience ?</h2>
                <p class="mb-10 max-w-2xl text-lg font-medium text-stone-400">
                    Rejoignez les milliers de passionnés et accédez aux meilleures ambiances dès maintenant.
                </p>
                <a href="{{ route('public.events.index') }}" class="btn-primary px-10 py-4 text-lg shadow-sunset rounded-full">
                    Parcourir l'agenda
                    <svg class="h-5 w-5 inline ml-2" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
        </div>
    </section>

    </div>

    <script>
        const defaultImages = [
            { url: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&q=80', alt: 'Soirée' },
            { url: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&q=80', alt: 'Public' },
            { url: 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=800&q=80', alt: 'DJ' },
            { url: 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&q=80', alt: 'Foule' },
            { url: 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&q=80', alt: 'Ambiance' },
            { url: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&q=80', alt: 'Concert' },
        ];

        function renderGallery(images) {
            const container = document.getElementById('gallery-container');
            if (!container) return;

            container.innerHTML = images.map((img, i) => `
                <div class="group relative overflow-hidden rounded-3xl shadow-sm hover:shadow-xl transition-shadow duration-300 ${i === 0 || i === 3 ? 'md:col-span-2' : ''}">
                    <img src="${img.url}" alt="${img.alt}" 
                         class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" 
                         loading="lazy" />
                    <div class="absolute inset-0 bg-stone-900/10 transition-opacity duration-300 group-hover:opacity-0"></div>
                </div>
            `).join('');
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderGallery(defaultImages);
            const saved = localStorage.getItem('wins-events-hero-image');
            const heroImg = document.getElementById('hero-image');
            if (saved && heroImg) heroImg.src = saved;
        });
    </script>
@endsection
