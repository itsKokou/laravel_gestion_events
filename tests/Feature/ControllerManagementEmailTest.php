<?php

namespace Tests\Feature;

use App\Mail\ControllerCredentialsMail;
use App\Models\Role;
use App\Models\User;
use App\Notifications\ControllerCredentialsNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_admin_can_view_controllers_index(): void
    {
        $adminRole = Role::create(['name' => 'Administrateur', 'slug' => 'admin']);
        Role::create(['name' => 'Contrôleur', 'slug' => 'controller']);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach($adminRole);

        $this->actingAs($admin);

        $this->get(route('admin.controllers.index'))
            ->assertOk()
            ->assertSee('Contrôleurs', false);
    }

    public function test_controller_credentials_mailable_envelope_has_to_recipient(): void
    {
        $user = User::create([
            'name' => 'Jean Scanner',
            'email' => 'jean@example.test',
            'password' => Hash::make('password'),
        ]);

        $mailable = new ControllerCredentialsMail($user, 'mot-de-passe-secret', 'created');
        $to = $mailable->envelope()->to;

        $this->assertCount(1, $to);
        $this->assertSame('jean@example.test', $to[0]->address);
    }
}
