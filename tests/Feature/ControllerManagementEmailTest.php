<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Notifications\ControllerCredentialsNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ControllerManagementEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_creating_controller_with_generated_password_sends_email(): void
    {
        Notification::fake();

        $adminRole = Role::create(['name' => 'Administrateur', 'slug' => 'admin']);
        Role::create(['name' => 'Contrôleur', 'slug' => 'controller']);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach($adminRole);

        $this->actingAs($admin);

        $payload = [
            'name' => 'Controleur 1',
            'email' => 'c1@example.test',
            // password volontairement omis => généré => email attendu
        ];

        $res = $this->post(route('admin.controllers.store'), $payload);
        $res->assertRedirect(route('admin.controllers.index'));

        $createdUser = User::where('email', 'c1@example.test')->firstOrFail();

        Notification::assertSentTo(
            $createdUser,
            ControllerCredentialsNotification::class
        );

    }
}
