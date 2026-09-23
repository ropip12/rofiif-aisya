<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function createUser(string $role, ?string $management, string $email, string $name): User
    {
        return User::factory()->create([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'management' => $management,
            'password' => Hash::make('Password123!'),
        ]);
    }

    public function test_admin_can_access_all_management_pages(): void
    {
        $user = $this->createUser('admin', null, 'admin@aisyala.local', 'Admin User');

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();

        $this->actingAs($user)
            ->get('/aset')
            ->assertOk();

        $this->actingAs($user)
            ->get('/risiko')
            ->assertOk();

        $this->actingAs($user)
            ->get('/layanan')
            ->assertOk();
    }

    public function test_user_aset_can_only_access_aset(): void
    {
        $user = $this->createUser('user', 'aset', 'asset.user@aisyala.local', 'User Aset');

        $this->actingAs($user)->get('/aset')->assertOk();
        $this->actingAs($user)->get('/risiko')->assertForbidden();
        $this->actingAs($user)->get('/layanan')->assertForbidden();
    }

    public function test_user_risiko_can_only_access_risiko(): void
    {
        $user = $this->createUser('user', 'risiko', 'risk.user@aisyala.local', 'User Risiko');

        $this->actingAs($user)->get('/risiko')->assertOk();
        $this->actingAs($user)->get('/aset')->assertForbidden();
        $this->actingAs($user)->get('/layanan')->assertForbidden();
    }

    public function test_user_layanan_can_only_access_layanan(): void
    {
        $user = $this->createUser('user', 'layanan', 'service.user@aisyala.local', 'User Layanan');

        $this->actingAs($user)->get('/layanan')->assertOk();
        $this->actingAs($user)->get('/aset')->assertForbidden();
        $this->actingAs($user)->get('/risiko')->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/aset')->assertRedirect('/login');
        $this->get('/risiko')->assertRedirect('/login');
        $this->get('/layanan')->assertRedirect('/login');
    }

    public function test_login_success_redirects_based_on_role_and_management(): void
    {
        $this->createUser('admin', null, 'admin@aisyala.local', 'Admin User');

        $this->post('/login', [
            'email' => 'admin@aisyala.local',
            'password' => 'Password123!',
        ])->assertRedirect('/dashboard');

        $this->post('/logout');

        $this->createUser('user', 'aset', 'asset.user@aisyala.local', 'User Aset');

        $this->post('/login', [
            'email' => 'asset.user@aisyala.local',
            'password' => 'Password123!',
        ])->assertRedirect('/aset');
    }
}
