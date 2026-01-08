<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_scanner_requires_authentication(): void
    {
        $response = $this->get(route('scanner.home'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_requires_admin_role(): void
    {
        $adminRole = Role::create(['name' => 'Administrateur', 'slug' => 'admin']);
        $controllerRole = Role::create(['name' => 'Contrôleur', 'slug' => 'controller']);

        $controller = User::create([
            'name' => 'Ctrl',
            'email' => 'ctrl@example.test',
            'password' => Hash::make('password'),
        ]);
        $controller->roles()->attach($controllerRole);

        $this->actingAs($controller);
        $this->get(route('admin.dashboard'))->assertForbidden();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach($adminRole);

        $this->actingAs($admin);
        $this->get(route('admin.dashboard'))->assertOk();
    }
}
