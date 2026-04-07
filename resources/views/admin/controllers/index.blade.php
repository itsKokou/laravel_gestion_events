@extends('layouts.admin')

@section('title', 'Admin · Contrôleurs')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="mb-2 text-xs font-bold uppercase tracking-wider text-orange-600">Équipe</p>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Contrôleurs</h1>
            <p class="mt-2 max-w-xl text-sm text-slate-600">
                Comptes autorisés à ouvrir le <a href="{{ route('scanner.home') }}" class="font-semibold text-orange-700 underline-offset-2 hover:underline">scanner</a> pour contrôler les entrées.
            </p>
        </div>
        <a href="{{ route('admin.controllers.create') }}"
            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-full border border-orange-600 bg-white px-5 py-2.5 text-sm font-bold text-orange-700 shadow-sm transition hover:bg-orange-600 hover:text-white">
            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nouveau contrôleur
        </a>
    </div>

    @if ($controllers->count() > 0)
        <div class="overflow-hidden rounded-3xl border border-stone-100 bg-white shadow-sm">
            <div class="-mx-px overflow-x-auto overscroll-x-contain">
                <table class="min-w-[720px] w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="border-b border-stone-200 bg-gradient-to-r from-orange-500/5 to-amber-500/5">
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-slate-700 sm:px-5">Nom</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-slate-700 sm:px-5">E-mail</th>
                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wide text-slate-700 sm:px-5">Rôles</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wide text-slate-700 sm:px-5">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($controllers as $user)
                            <tr class="border-t border-stone-100 transition-colors hover:bg-stone-50/80">
                                <td class="px-4 py-4 align-middle sm:px-5">
                                    <p class="font-bold text-slate-900">{{ $user->name }}</p>
                                </td>
                                <td class="px-4 py-4 align-middle sm:px-5">
                                    <p class="font-medium text-slate-800">{{ $user->email }}</p>
                                </td>
                                <td class="px-4 py-4 align-middle sm:px-5">
                                    <div class="flex flex-wrap items-center justify-center gap-1.5">
                                        @foreach ($user->roles as $role)
                                            @php
                                                $roleColors = [
                                                    'admin' => 'bg-orange-100 text-orange-800 ring-orange-200/80',
                                                    'controller' => 'bg-blue-100 text-blue-900 ring-blue-200/80',
                                                ];
                                                $cls = $roleColors[$role->slug] ?? 'bg-slate-100 text-slate-700 ring-slate-200/80';
                                            @endphp
                                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide ring-1 {{ $cls }}">
                                                {{ $role->slug }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-4 align-middle text-right sm:px-5">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <form id="admin-reset-controller-form-{{ $user->id }}" method="POST"
                                            action="{{ route('admin.controllers.reset_password', $user) }}" class="m-0 inline">
                                            @csrf
                                            <button type="button"
                                                class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-gray-200 bg-white-50 px-3 py-2 text-xs font-extrabold tracking-wide text-gray-700 transition hover:border-gray-300 hover:bg-gray-100"
                                                onclick='window.adminOpenControllerResetModal(document.getElementById("admin-reset-controller-form-{{ $user->id }}"), @json($user->name))'>
                                                Réinitialiser MDP
                                            </button>
                                        </form>
                                        <form id="admin-revoke-controller-form-{{ $user->id }}" method="POST"
                                            action="{{ route('admin.controllers.revoke', $user) }}" class="m-0 inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-extrabold tracking-wide text-red-700 transition hover:border-red-300 hover:bg-red-100"
                                                onclick='window.adminOpenControllerRevokeModal(document.getElementById("admin-revoke-controller-form-{{ $user->id }}"), @json($user->name))'>
                                                Retirer le rôle
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($controllers->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $controllers->links() }}
            </div>
        @endif
    @else
        <div class="rounded-2xl border border-stone-200 bg-white px-8 py-16 text-center shadow-sm">
            <p class="text-4xl" aria-hidden="true">👥</p>
            <h2 class="mt-4 text-xl font-black text-slate-900">Aucun contrôleur</h2>
            <p class="mx-auto mt-2 max-w-md text-sm text-slate-600">
                Créez des comptes pour votre équipe afin qu’ils puissent scanner les billets sur place.
            </p>
            <a href="{{ route('admin.controllers.create') }}"
                class="mt-6 inline-flex rounded-full bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:from-orange-600 hover:to-orange-700">
                Ajouter un contrôleur
            </a>
        </div>
    @endif

    <div id="controller-reset-confirm-modal" class="fixed inset-0 z-[105] hidden" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="controller-reset-confirm-title">
        <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-sm transition-opacity" data-controller-reset-backdrop></div>
        <div class="pointer-events-none relative z-10 mx-auto flex min-h-full items-center justify-center p-4 sm:p-6">
            <div class="pointer-events-auto w-full max-w-md rounded-2xl border border-stone-200/80 bg-white p-6 shadow-2xl shadow-stone-900/15 sm:p-8">
                <h2 id="controller-reset-confirm-title" class="text-center text-lg font-black tracking-tight text-slate-900 sm:text-xl">
                    Réinitialiser le mot de passe ?
                </h2>
                <p class="mt-2 text-center text-sm leading-relaxed text-slate-600">
                    <span class="font-semibold text-slate-800" id="controller-reset-confirm-name"></span>
                    <span class="mt-2 block">Un nouveau mot de passe sera généré et envoyé par e-mail à cette personne.</span>
                </p>
                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button type="button"
                        class="w-full cursor-pointer rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-stone-50"
                        data-controller-reset-dismiss>
                        Annuler
                    </button>
                    <button type="button" id="controller-reset-confirm-submit"
                        class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl bg-orange-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-orange-700">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="controller-revoke-confirm-modal" class="fixed inset-0 z-[105] hidden" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="controller-revoke-confirm-title">
        <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-sm transition-opacity" data-controller-revoke-backdrop></div>
        <div class="pointer-events-none relative z-10 mx-auto flex min-h-full items-center justify-center p-4 sm:p-6">
            <div class="pointer-events-auto w-full max-w-md rounded-2xl border border-stone-200/80 bg-white p-6 shadow-2xl shadow-stone-900/15 sm:p-8">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600 ring-1 ring-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <h2 id="controller-revoke-confirm-title" class="text-center text-lg font-black tracking-tight text-slate-900 sm:text-xl">
                    Retirer le rôle contrôleur ?
                </h2>
                <p class="mt-2 text-center text-sm leading-relaxed text-slate-600">
                    <span class="font-semibold text-slate-800" id="controller-revoke-confirm-name"></span>
                    <span class="mt-2 block">Cette personne ne pourra plus accéder au scanner tant que le rôle n’est pas réattribué.</span>
                </p>
                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button type="button"
                        class="w-full cursor-pointer rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-stone-50"
                        data-controller-revoke-dismiss>
                        Retour
                    </button>
                    <button type="button" id="controller-revoke-confirm-submit"
                        class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl bg-red-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-red-700">
                        Retirer le rôle
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            let resetFormPending = null;
            let revokeFormPending = null;

            function syncBodyScroll() {
                const open =
                    (document.getElementById('controller-reset-confirm-modal')?.classList.contains('hidden') === false) ||
                    (document.getElementById('controller-revoke-confirm-modal')?.classList.contains('hidden') === false);
                document.body.classList.toggle('overflow-hidden', open);
            }

            function closeResetModal() {
                resetFormPending = null;
                const m = document.getElementById('controller-reset-confirm-modal');
                if (m) {
                    m.classList.add('hidden');
                    m.setAttribute('aria-hidden', 'true');
                }
                syncBodyScroll();
            }

            function closeRevokeModal() {
                revokeFormPending = null;
                const m = document.getElementById('controller-revoke-confirm-modal');
                if (m) {
                    m.classList.add('hidden');
                    m.setAttribute('aria-hidden', 'true');
                }
                syncBodyScroll();
            }

            window.adminOpenControllerResetModal = function (form, displayName) {
                if (!form) return;
                resetFormPending = form;
                const el = document.getElementById('controller-reset-confirm-name');
                if (el) el.textContent = displayName != null ? String(displayName) : '';
                const m = document.getElementById('controller-reset-confirm-modal');
                if (m) {
                    m.classList.remove('hidden');
                    m.setAttribute('aria-hidden', 'false');
                    syncBodyScroll();
                    document.getElementById('controller-reset-confirm-submit')?.focus();
                }
            };

            window.adminOpenControllerRevokeModal = function (form, displayName) {
                if (!form) return;
                revokeFormPending = form;
                const el = document.getElementById('controller-revoke-confirm-name');
                if (el) el.textContent = displayName != null ? String(displayName) : '';
                const m = document.getElementById('controller-revoke-confirm-modal');
                if (m) {
                    m.classList.remove('hidden');
                    m.setAttribute('aria-hidden', 'false');
                    syncBodyScroll();
                    document.getElementById('controller-revoke-confirm-submit')?.focus();
                }
            };

            document.getElementById('controller-reset-confirm-submit')?.addEventListener('click', function () {
                if (resetFormPending) resetFormPending.submit();
            });
            document.getElementById('controller-revoke-confirm-submit')?.addEventListener('click', function () {
                if (revokeFormPending) revokeFormPending.submit();
            });

            document.querySelector('[data-controller-reset-dismiss]')?.addEventListener('click', closeResetModal);
            document.querySelector('[data-controller-revoke-dismiss]')?.addEventListener('click', closeRevokeModal);

            document.addEventListener('click', function (ev) {
                const t = ev.target;
                if (t instanceof Element) {
                    if (t.matches('[data-controller-reset-backdrop]')) closeResetModal();
                    if (t.matches('[data-controller-revoke-backdrop]')) closeRevokeModal();
                }
            });

            document.addEventListener('keydown', function (ev) {
                if (ev.key !== 'Escape') return;
                if (document.getElementById('controller-reset-confirm-modal') && !document.getElementById('controller-reset-confirm-modal').classList.contains('hidden')) {
                    closeResetModal();
                }
                if (document.getElementById('controller-revoke-confirm-modal') && !document.getElementById('controller-revoke-confirm-modal').classList.contains('hidden')) {
                    closeRevokeModal();
                }
            });
        })();
    </script>
@endsection
