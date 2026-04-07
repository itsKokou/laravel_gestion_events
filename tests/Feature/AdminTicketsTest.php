<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTicketsTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        $adminRole = Role::create(['name' => 'Administrateur', 'slug' => 'admin']);
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => bcrypt('password'),
        ]);
        $admin->roles()->attach($adminRole);

        return $admin;
    }

    public function test_guest_cannot_access_admin_tickets_index(): void
    {
        $this->get(route('admin.tickets.index'))->assertRedirect();
    }

    public function test_admin_can_view_tickets_index(): void
    {
        $this->actingAs($this->adminUser());

        $response = $this->get(route('admin.tickets.index'));
        $response->assertOk();
        $response->assertSee('Tickets & participants', false);
    }
}
