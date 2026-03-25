@include('components.public.section-title', [
    'eyebrow' => 'Pourquoi Nous',
    'title' => 'Une billetterie pensée pour vous',
    'subtitle' => 'Tout est conçu pour que votre seule préoccupation soit de profiter de l\'événement.',
])
<div class="mb-24 grid gap-8 sm:grid-cols-3">
    <!-- Card 1 -->
    <div class="card-premium group p-8 sm:p-10 relative overflow-hidden bg-white">
        <div class="absolute top-0 right-0 p-6 opacity-5 transform group-hover:scale-110 transition-transform duration-500">
            <span class="text-9xl">🔒</span>
        </div>
        <div class="mb-8 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-sunset text-2xl">
            🔒
        </div>
        <h3 class="text-2xl font-black text-stone-900 group-hover:text-orange-600 transition-colors">Paiement sécurisé</h3>
        <p class="mt-4 text-base leading-relaxed text-stone-500 font-medium">Vos transactions et données sont cryptées avec les plus hauts standards de l'industrie. Achetez en toute sérénité.</p>
    </div>
    <!-- Card 2 -->
    <div class="card-premium group p-8 sm:p-10 relative overflow-hidden bg-white">
        <div class="absolute top-0 right-0 p-6 opacity-5 transform group-hover:scale-110 transition-transform duration-500">
            <span class="text-9xl">⚡</span>
        </div>
        <div class="mb-8 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-sunset text-2xl">
            ⚡
        </div>
        <h3 class="text-2xl font-black text-stone-900 group-hover:text-orange-600 transition-colors">Réservation rapide</h3>
        <p class="mt-4 text-base leading-relaxed text-stone-500 font-medium">Réservez en quelques secondes. Votre reçu et vos billets QR code sont disponibles immédiatement sans friction.</p>
    </div>
    <!-- Card 3 -->
    <div class="card-premium group p-8 sm:p-10 relative overflow-hidden bg-white">
        <div class="absolute top-0 right-0 p-6 opacity-5 transform group-hover:scale-110 transition-transform duration-500">
            <span class="text-9xl">🎟️</span>
        </div>
        <div class="mb-8 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-sunset text-2xl">
            🎟️
        </div>
        <h3 class="text-2xl font-black text-stone-900 group-hover:text-orange-600 transition-colors">Billets fiables</h3>
        <p class="mt-4 text-base leading-relaxed text-stone-500 font-medium">Pas de fraude possible sur notre plateforme officielle. Scannez, entrez et profitez de la soirée.</p>
    </div>
</div>
