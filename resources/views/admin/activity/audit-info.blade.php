{{-- Informations sur l’audit append-only (aucune logique serveur) --}}
<section id="journal-audit" class="scroll-mt-24 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    <h2 class="text-lg font-extrabold tracking-tight text-slate-900">Journal d’activité</h2>
    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
        Les événements métier sont enregistrés de façon append-only dans la table
        <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs font-mono text-slate-800">activity_logs</code>
        (création de commande, paiement réussi, expiration de réservation).
    </p>
    <ul class="mt-4 list-inside list-disc space-y-1 text-sm text-slate-600">
        <li><span class="font-mono text-xs text-slate-800">order.created</span> — nouvelle réservation</li>
        <li><span class="font-mono text-xs text-slate-800">payment.success</span> — paiement confirmé</li>
        <li><span class="font-mono text-xs text-slate-800">reservation.expired</span> — session expirée</li>
    </ul>
    <p class="mt-4 text-xs text-slate-500">
        Pour une exploration avancée (filtres, export), branchez un outil BI ou une future vue liste dédiée sur les
        mêmes données.
    </p>
</section>