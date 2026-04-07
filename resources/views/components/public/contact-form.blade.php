@props([
    'successTitle' => 'Message envoyé',
    'successMessage' => 'Merci, nous revenons vers vous au plus vite. En attendant, vous pouvez consulter la FAQ.',
])

<div class="w-full">
    <form id="contactForm" method="POST" action="#" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="space-y-3 mb-6" >
                <label for="full_name" class="block text-sm font-semibold text-stone-700">
                    Nom complet <span class="text-red-500">*</span>
                </label>
                <input
                    id="full_name"
                    name="full_name"
                    type="text"
                    value="{{ old('full_name') }}"
                    required
                    autocomplete="name"
                    placeholder="Votre nom et prénom"
                    class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-5 py-4 text-base font-medium text-stone-900 transition-all placeholder:text-stone-400 hover:bg-stone-100/80 focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-500/10"
                />
            </div>

            <div class="space-y-3 mb-6">
                <label for="contact_email" class="block text-sm font-semibold text-stone-700">
                    Email <span class="text-red-500">*</span>
                </label>
                <input
                    id="contact_email"
                    name="contact_email"
                    type="email"
                    value="{{ old('contact_email') }}"
                    required
                    autocomplete="email"
                    placeholder="vous@exemple.com"
                    class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-5 py-4 text-base font-medium text-stone-900 transition-all placeholder:text-stone-400 hover:bg-stone-100/80 focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-500/10"
                />
            </div>
        </div>

        <div class="space-y-3 mb-6">
            <label for="subject" class="block text-sm font-semibold text-stone-700">
                Sujet <span class="text-red-500">*</span>
            </label>
            <select
                id="subject"
                name="subject"
                required
                class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-5 py-4 text-base font-medium text-stone-900 transition-all hover:bg-stone-100/80 focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-500/10 cursor-pointer appearance-none"
            >
                <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Choisir un sujet</option>
                <option value="billetterie" {{ old('subject') === 'billetterie' ? 'selected' : '' }}>Billetterie</option>
                <option value="paiement" {{ old('subject') === 'paiement' ? 'selected' : '' }}>Paiement</option>
                <option value="billet" {{ old('subject') === 'billet' ? 'selected' : '' }}>Billet</option>
                <option value="remboursement" {{ old('subject') === 'remboursement' ? 'selected' : '' }}>Remboursement</option>
                <option value="autre" {{ old('subject') === 'autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>

        <div class="space-y-3 mb-6">
            <label for="message" class="block text-sm font-semibold text-stone-700">
                Message <span class="text-red-500">*</span>
            </label>
            <textarea
                id="message"
                name="message"
                required
                rows="5"
                placeholder="Décrivez votre demande en quelques mots…"
                class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-5 py-4 text-base font-medium text-stone-900 transition-all placeholder:text-stone-400 hover:bg-stone-100/80 focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-500/10 resize-none"
            >{{ old('message') }}</textarea>
        </div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <button
                type="submit"
                class="btn-primary w-full sm:w-auto px-4 py-4 text-sm font-bold shadow-sunset rounded-full"
            >
                Envoyer le message
            </button>

            <p class="text-xs leading-relaxed text-stone-500 max-w-[22rem]">
                Réponse sous 24h (jours ouvrés). Nous ne partagerons jamais vos informations.
            </p>
        </div>

        <div
            id="contact-success"
            class="hidden rounded-2xl border border-green-200/90 bg-green-50 p-5 shadow-sm"
            role="status"
            aria-live="polite"
        >
            <div class="flex items-start gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-600 text-white font-black text-sm"
                    aria-hidden="true">✓</span>
                <div class="min-w-0">
                    <p class="font-bold text-green-900">{{ $successTitle }}</p>
                    <p class="mt-1 text-sm leading-relaxed text-green-800">{{ $successMessage }}</p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    (function () {
        const form = document.getElementById('contactForm');
        const success = document.getElementById('contact-success');
        if (!form || !success) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalLabel = submitBtn ? submitBtn.textContent : null;

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Envoi…';
            }

            window.setTimeout(function () {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalLabel || 'Envoyer le message';
                }

                success.classList.remove('hidden');
                form.reset();
            }, 800);
        });
    })();
</script>

