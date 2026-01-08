<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertControllerRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminControllerManagementController extends Controller
{
    public function index()
    {
        $controllerRole = Role::firstOrCreate(['slug' => 'controller'], ['name' => 'Contrôleur', 'slug' => 'controller']);

        $controllers = User::query()
            ->whereHas('roles', fn ($q) => $q->where('roles.id', $controllerRole->id))
            ->with('roles')
            ->orderBy('name')
            ->paginate(30);

        return view('admin.controllers.index', [
            'controllers' => $controllers,
        ]);
    }

    public function create()
    {
        return view('admin.controllers.create');
    }

    public function store(UpsertControllerRequest $request)
    {
        $data = $request->validated();

        $controllerRole = Role::firstOrCreate(['slug' => 'controller'], ['name' => 'Contrôleur', 'slug' => 'controller']);

        $email = strtolower(trim($data['email']));
        $name = $data['name'] ?? null;
        $password = $data['password'] ?? null;

        $user = User::query()->where('email', $email)->first();
        $created = false;

        if (!$user) {
            $created = true;
            $generatedPassword = $password ?: Str::password(12);

            $user = User::create([
                'name' => $name ?: $email,
                'email' => $email,
                'password' => Hash::make($generatedPassword),
            ]);
        } else {
            if ($name) {
                $user->name = $name;
            }
            if ($password) {
                $user->password = Hash::make($password);
            }
            $user->save();
        }

        $user->roles()->syncWithoutDetaching([$controllerRole->id]);

        return redirect()
            ->route('admin.controllers.index')
            ->with('status', $created ? 'Contrôleur créé et autorisé.' : 'Rôle contrôleur attribué.');
    }

    public function revoke(User $user)
    {
        $controllerRole = Role::where('slug', 'controller')->first();
        if ($controllerRole) {
            $user->roles()->detach($controllerRole->id);
        }

        return redirect()->route('admin.controllers.index')->with('status', 'Rôle contrôleur retiré.');
    }
}
