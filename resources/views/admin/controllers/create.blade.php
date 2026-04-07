@extends('layouts.admin')

@section('title', 'Admin · Ajouter un contrôleur')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="mb-2 text-xs font-bold uppercase tracking-wider text-orange-600">Équipe</p>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Ajouter un contrôleur</h1>
            <p class="mt-2 max-w-xl text-sm text-slate-600">
                Créez un compte ou attribuez le rôle <span class="font-semibold text-slate-800">controller</span> pour l’accès au scanner.
            </p>
        </div>
        <a href="{{ route('admin.controllers.index') }}"
            class="inline-flex shrink-0 items-center justify-center rounded-full border border-stone-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-stone-50">
            ← Retour à la liste
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50/90 p-4 text-sm text-red-900 shadow-sm">
            <p class="font-bold">Erreurs de validation</p>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.controllers.store') }}" class="space-y-6">
        @csrf

        <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="text-lg font-extrabold text-slate-900">Informations</h2>
            <!-- <p class="mt-1 text-sm text-slate-600">E-mail obligatoire ; nom et mot de passe optionnels selon le cas.</p> -->

            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="ctrl-name" class="mb-1.5 block text-sm font-semibold text-slate-800">Nom complet <span class="text-red-600">*</span></label>
                    <input id="ctrl-name" name="name" value="{{ old('name') }}" required type="text" placeholder="Ex. Fatou Ndiaye"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-orange-300 focus:outline-none focus:ring-2 focus:ring-orange-500/20" />
                    <!-- <p class="mt-1.5 text-xs text-slate-500">Optionnel — affichage dans l’interface.</p> -->
                </div>
                <div>
                    <label for="ctrl-email" class="mb-1.5 block text-sm font-semibold text-slate-800">Adresse e-mail <span class="text-red-600">*</span></label>
                    <input id="ctrl-email" name="email" value="{{ old('email') }}" type="email" required placeholder="controleur@exemple.com"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-orange-300 focus:outline-none focus:ring-2 focus:ring-orange-500/20" />
                    <p class="mt-1.5 text-xs text-slate-500">Si le compte existe déjà, le rôle contrôleur sera ajouté.</p>
                </div>
            </div>

            <div class="mt-5">
                <label for="ctrl-password" class="mb-1.5 block text-sm font-semibold text-slate-800">Mot de passe</label>
                <input id="ctrl-password" name="password" type="password" autocomplete="new-password"
                    placeholder="Laisser vide pour générer automatiquement (nouveau compte)"
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-orange-300 focus:outline-none focus:ring-2 focus:ring-orange-500/20" />
                <p class="mt-1.5 text-xs text-slate-500">
                    <strong>Nouveau compte :</strong> vide = mot de passe généré et envoyé par e-mail.
                    <strong>Compte existant :</strong> renseigner pour forcer un nouveau mot de passe.
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-end gap-6">
            <a href="{{ route('admin.controllers.index') }}" class="rounded-full border border-stone-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-stone-50">
                Annuler
            </a>
            <button type="submit"
                class="inline-flex cursor-pointer rounded-full bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition hover:from-orange-600 hover:to-orange-700">
                Enregistrer
            </button>
        </div>
    </form>
@endsection
