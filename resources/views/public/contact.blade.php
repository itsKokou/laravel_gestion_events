@extends('layouts.public')

@section('title', 'Contact · ' . config('app.name', "Win's Events"))

@section('content')
    @php
        $contactMail = config('mail.from.address', 'contact@example.com');
    @endphp

    {{-- Premium Hero --}}
    <section class="relative w-full overflow-hidden bg-stone-900 mb-16 sm:mb-24 -mt-4 sm:-mt-8">
        <div class="relative min-h-[min(80vh,700px)] flex items-center justify-center text-center">
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=2000&q=80" alt="Contact Support" class="h-full w-full object-cover opacity-40 mix-blend-luminosity" loading="eager" />
                <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-900/80 to-transparent"></div>
            </div>

            <div class="relative w-full flex flex-col px-8 py-24 z-10 items-center mt-16">
                <div class="inline-flex items-center gap-2 rounded-full border border-orange-500/30 bg-orange-500/10 px-4 py-1.5 backdrop-blur-md mb-8">
                    <span class="h-2 w-2 rounded-full bg-orange-500 animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-400">Support 24/7</span>
                </div>
                <h1 class="text-4xl font-black tracking-tighter text-white sm:text-6xl md:text-7xl mb-6">
                    Nous sommes là <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">pour vous</span>
                </h1>
                <p class="max-w-2xl text-lg font-medium text-stone-300 sm:text-xl mb-10">
                    Une question sur un événement ? Besoin d'aide pour une réservation ? Notre équipe vous accompagne.
                </p>
            </div>
        </div>
    </section>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pb-16">
        
        {{-- CONTACT FORM + COORDONNEES --}}
        <section class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-start mb-24">
            <div class="lg:col-span-7">
                <div class="card-premium border-0 bg-white p-8 sm:p-12 shadow-2xl relative overflow-hidden" id="contact-form">
                    <div class="mb-10 text-center sm:text-left flex flex-col items-center sm:items-start">
                        <p class="mb-3 text-xs font-black uppercase tracking-[0.25em] text-orange-600">Contact</p>
                        <h2 class="text-4xl font-black tracking-tighter text-stone-900">Envoyer un message</h2>
                        <p class="mt-4 text-lg font-medium text-stone-500 leading-relaxed">
                            Remplissez le formulaire ci-dessous de manière précise, notre équipe support vous répondra dans les plus brefs délais.
                        </p>
                    </div>
                    <x-public.contact-form />
                </div>
            </div>

            <aside class="lg:col-span-5">
                <div class="sticky top-32 card-premium border-0 bg-stone-900 p-8 sm:p-12 shadow-2xl text-white relative overflow-hidden rounded-[2.5rem]">
                    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1540039155073-9bb7d5d3f0d0?w=800&q=80')] opacity-5 bg-cover bg-center"></div>
                    <div class="absolute inset-0 bg-gradient-to-br from-stone-900/90 via-stone-900/80 to-stone-950"></div>
                    
                    <div class="relative z-10">
                        <p class="mb-3 text-xs font-black uppercase tracking-[0.25em] text-stone-400">Direct</p>
                        <h2 class="text-3xl font-black text-white mb-10 tracking-tight">Coordonnées</h2>

                        <div class="grid grid-cols-1 gap-6">
                            <div class="flex items-start gap-5">
                                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/5 border border-white/10 text-orange-400 shadow-inner backdrop-blur-md">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-stone-400 mb-1">Email Support</p>
                                    <a href="mailto:{{ $contactMail }}" class="block text-lg font-bold text-white hover:text-orange-400 transition-colors">
                                        {{ $contactMail }}
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-start gap-5">
                                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/5 border border-white/10 text-orange-400 shadow-inner backdrop-blur-md">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 0 1 2-2h2l2 5-2 1a16 16 0 0 0 7 7l1-2 5 2v2a2 2 0 0 1-2 2h-1C9.716 22 2 14.284 2 5V5a2 2 0 0 1 1-2" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-stone-400 mb-1">Assistance Téléphonique</p>
                                    <p class="text-lg font-bold text-white tracking-wide">
                                        +221 33 868 55 03
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-5">
                                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/5 border border-white/10 text-orange-400 shadow-inner backdrop-blur-md">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6-4.35-6-10a6 6 0 1 1 12 0c0 5.65-6 10-6 10z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-stone-400 mb-1">Siège Social</p>
                                    <p class="text-lg font-bold text-white tracking-wide">Paris, France</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-12 rounded-2xl bg-white/5 border border-white/10 p-6 backdrop-blur-md">
                            <div class="flex items-center gap-3">
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                                <p class="text-sm font-bold text-white">Horaires d'ouverture</p>
                            </div>
                            <p class="mt-3 text-sm leading-relaxed text-stone-400 font-medium">
                                Nos équipes de support répondent du lundi au vendredi, de 9h à 18h, pour un traitement rapide de vos demandes.
                            </p>
                        </div>
                    </div>
                </div>
            </aside>
        </section>

        {{-- FAQ --}}
        <section class="mb-24 lg:mb-32">
            @include('components.public.section-title', [
                'eyebrow' => 'Ressources',
                'title' => 'Questions fréquentes',
                'subtitle' => 'Trouvez rapidement une réponse avant de nous envoyer un message.',
            ])

            <div class="mx-auto max-w-3xl mt-12 space-y-4">
                @php
                    $faqs = [
                        [
                            'q' => 'Comment acheter un billet ?',
                            'a' => 'Choisissez l’événement, sélectionnez le type de billet, indiquez la quantité, complétez vos coordonnées puis confirmez. Vous recevrez ensuite votre billet par e-mail.',
                        ],
                        [
                            'q' => 'Puis-je me faire rembourser ?',
                            'a' => 'Cela dépend des conditions de l’événement. Consultez les conditions générales d’achat pour connaître l’éligibilité et les délais. En cas de doute, contactez notre support.',
                        ],
                        [
                            'q' => 'Comment recevoir mon billet ?',
                            'a' => 'Après la transaction, le billet numérique avec un QR code vous est envoyé instantanément par e-mail. Vous pouvez également retrouver vos billets sur votre tableau de bord.',
                        ],
                        [
                            'q' => 'Les paiements sont-ils sécurisés ?',
                            'a' => 'Absolument. Nos flux financiers sont entièrement chiffrés et opérés par des systèmes bancaires certifiés de haute sécurité (Stripe).',
                        ],
                    ];
                @endphp

                @foreach ($faqs as $i => $faq)
                    <x-public.faq-item :faqId="$i" :question="$faq['q']" :answer="$faq['a']" />
                @endforeach
            </div>
        </section>

        <x-public.cta />
    </div>

    <script>
        (function () {
            const items = document.querySelectorAll('[data-faq-item]');
            if (!items || items.length === 0) return;

            function closeItem(item) {
                const button = item.querySelector('[data-faq-button]');
                const panel = item.querySelector('[data-faq-panel]');
                const icon = item.querySelector('[data-faq-icon]');
                if (!button || !panel) return;

                button.setAttribute('aria-expanded', 'false');
                panel.style.maxHeight = '0px';
                panel.style.opacity = '0';
                panel.style.transform = 'translateY(-4px)';

                if (icon) icon.classList.remove('rotate-180');
            }

            function openItem(item) {
                const button = item.querySelector('[data-faq-button]');
                const panel = item.querySelector('[data-faq-panel]');
                const icon = item.querySelector('[data-faq-icon]');
                if (!button || !panel) return;

                button.setAttribute('aria-expanded', 'true');
                panel.style.maxHeight = panel.scrollHeight + 'px';
                panel.style.opacity = '1';
                panel.style.transform = 'translateY(0)';

                if (icon) icon.classList.add('rotate-180');
            }

            items.forEach(function (item) {
                const button = item.querySelector('[data-faq-button]');
                if (!button) return;

                button.addEventListener('click', function () {
                    const isOpen = button.getAttribute('aria-expanded') === 'true';

                    items.forEach(function (it) {
                        if (it === item) return;
                        closeItem(it);
                    });

                    if (isOpen) {
                        closeItem(item);
                    } else {
                        openItem(item);
                    }
                });
            });
        })();
    </script>
@endsection
