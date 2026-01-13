<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email:rfc'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => 'Identifiants invalides.',
            ]);
        }

        $request->session()->regenerate();

        // Rediriger selon le rôle de l'utilisateur
        $user = Auth::user();
        $user->loadMissing('roles');

        // Vérifier l'URL de redirection prévue (intended)
        $intended = $request->session()->pull('url.intended');

        if ($user->hasAnyRole(['admin'])) {
            // Si l'URL prévue est valide et accessible, l'utiliser, sinon dashboard admin
            if ($intended && str_starts_with($intended, url('/admin'))) {
                return redirect($intended);
            }
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasAnyRole(['controller'])) {
            // Si l'URL prévue est valide et accessible (scanner), l'utiliser, sinon scanner home
            if ($intended && str_starts_with($intended, url('/scanner'))) {
                return redirect($intended);
            }
            return redirect()->route('scanner.home');
        }

        // Par défaut, rediriger vers le dashboard admin
        return redirect()->route('admin.dashboard');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
