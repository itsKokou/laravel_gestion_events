<footer class="mt-auto border-t border-stone-200/60 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 lg:gap-8">
            <div class="md:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2 mb-4 group inline-flex">
                    @if (file_exists(public_path('logo.png')))
                        <img src="{{ asset('logo.png') }}" alt="" class="h-8 w-auto" />
                    @else
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-orange-400 to-orange-600 text-white font-bold text-lg shadow-sunset transition-transform group-hover:scale-105">
                            W</div>
                    @endif
                    <span
                        class="text-xl font-black tracking-tight text-stone-900 group-hover:text-orange-600 transition-colors">{{ config('app.name', "Win's Events") }}</span>
                </a>
                <p class="mt-2 text-sm leading-relaxed text-stone-500 max-w-sm">
                    La plateforme premium de billetterie et de gestion d'événements. Découvrez des expériences
                    inoubliables et réservez vos places en toute sécurité.
                </p>
                <div class="mt-6 flex space-x-4">
                    <a href="#" class="text-stone-400 hover:text-orange-500 transition-colors">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-black tracking-widest text-stone-900 uppercase">Navigation</h3>
                <ul role="list" class="mt-4 space-y-3">
                    <li><a href="{{ route('home') }}"
                            class="text-sm font-medium text-stone-500 hover:text-orange-600 transition-colors">Accueil</a>
                    </li>
                    <li><a href="{{ route('public.events.index') }}"
                            class="text-sm font-medium text-stone-500 hover:text-orange-600 transition-colors">Événements</a>
                    </li>
                    <li><a href="{{ route('public.about') }}"
                            class="text-sm font-medium text-stone-500 hover:text-orange-600 transition-colors">À
                            propos</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-xs font-black tracking-widest text-stone-900 uppercase">Support</h3>
                <ul role="list" class="mt-4 space-y-3">
                    <li><a href="{{ route('public.contact') }}"
                            class="text-sm font-medium text-stone-500 hover:text-orange-600 transition-colors">Contactez-nous</a>
                    </li>
                    <li><a href="#"
                            class="text-sm font-medium text-stone-500 hover:text-orange-600 transition-colors">FAQ</a>
                    </li>
                    <li><a href="#"
                            class="text-sm font-medium text-stone-500 hover:text-orange-600 transition-colors">Mentions
                            légales</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-12 border-t border-stone-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs font-semibold text-stone-400">&copy; {{ date('Y') }}
                {{ config('app.name', "Win's Events") }}. Tous droits réservés.</p>
            <div class="flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-orange-500"></span>
                </span>
                <span class="text-xs font-semibold text-stone-500">Par Kokou Godwin</span>
            </div>
        </div>
    </div>
</footer>