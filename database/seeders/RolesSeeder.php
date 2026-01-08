<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Administrateur', 'slug' => 'admin']
        );

        $controllerRole = Role::firstOrCreate(
            ['slug' => 'controller'],
            ['name' => 'Contrôleur', 'slug' => 'controller']
        );

        // Comptes par défaut (développement) - changez ces identifiants en prod.
        $adminEmail = (string) env('DEFAULT_ADMIN_EMAIL', 'admin@example.test');
        $adminPassword = (string) env('DEFAULT_ADMIN_PASSWORD', 'password');

        $controllerEmail = (string) env('DEFAULT_CONTROLLER_EMAIL', 'controller@example.test');
        $controllerPassword = (string) env('DEFAULT_CONTROLLER_PASSWORD', 'password');

        $admin = User::firstOrCreate(
            ['email' => $adminEmail],
            ['name' => 'Admin', 'password' => Hash::make($adminPassword)]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        $controller = User::firstOrCreate(
            ['email' => $controllerEmail],
            ['name' => 'Contrôleur', 'password' => Hash::make($controllerPassword)]
        );
        $controller->roles()->syncWithoutDetaching([$controllerRole->id]);
    }
}

