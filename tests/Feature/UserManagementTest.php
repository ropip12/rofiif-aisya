<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
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

    public function test_admin_can_access_admin_user_pages_and_other_users_are_denied(): void
    {
        $admin = $this->createUser('admin', null, 'admin.user.manage@aisyala.local', 'Admin User Manager');
        $assetUser = $this->createUser('user', 'aset', 'asset.user.manage@aisyala.local', 'User Aset');
        $riskUser = $this->createUser('user', 'risiko', 'risk.user.manage@aisyala.local', 'User Risiko');
        $serviceUser = $this->createUser('user', 'layanan', 'service.user.manage@aisyala.local', 'User Layanan');

        $this->actingAs($admin)->get('/admin/pengguna')->assertOk();
        $this->actingAs($admin)->get('/admin/pengguna/create')->assertOk();

        $this->actingAs($assetUser)->get('/admin/pengguna')->assertForbidden();
        $this->actingAs($riskUser)->get('/admin/pengguna')->assertForbidden();
        $this->actingAs($serviceUser)->get('/admin/pengguna')->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_for_admin_user_pages(): void
    {
        $this->get('/admin/pengguna')->assertRedirect('/login');
        $this->get('/admin/pengguna/create')->assertRedirect('/login');
    }

    public function test_admin_can_create_user_with_valid_role_management_and_hashed_password(): void
    {
        $admin = $this->createUser('admin', null, 'admin.create.user@aisyala.local', 'Admin Create User');

        $this->actingAs($admin)
            ->post('/admin/pengguna', [
                'name' => 'User Baru Aset',
                'email' => 'new.asset.user@aisyala.local',
                'password' => 'Password123!',
                'role' => 'user',
                'management' => 'aset',
            ])
            ->assertRedirect('/admin/pengguna');

        $user = User::where('email', 'new.asset.user@aisyala.local')->firstOrFail();
        $this->assertTrue(Hash::check('Password123!', $user->password));
        $this->assertEquals('user', $user->role);
        $this->assertEquals('aset', $user->management);

        $this->actingAs($admin)
            ->post('/admin/pengguna', [
                'name' => 'Admin Baru',
                'email' => 'new.admin@aisyala.local',
                'password' => 'Password123!',
                'role' => 'admin',
                'management' => 'aset',
            ])
            ->assertSessionHasErrors(['management']);

        $this->actingAs($admin)
            ->post('/admin/pengguna', [
                'name' => 'Admin Baru',
                'email' => 'new.admin2@aisyala.local',
                'password' => 'Password123!',
                'role' => 'admin',
                'management' => null,
            ])
            ->assertRedirect('/admin/pengguna');
    }

    public function test_admin_can_update_user_and_keep_or_replace_password(): void
    {
        $admin = $this->createUser('admin', null, 'admin.update.user@aisyala.local', 'Admin Update User');
        $user = $this->createUser('user', 'risiko', 'risk.user.update@aisyala.local', 'User Update Risiko');

        $this->actingAs($admin)
            ->put('/admin/pengguna/' . $user->id, [
                'name' => 'User Risiko Diperbarui',
                'email' => 'risk.user.updated@aisyala.local',
                'role' => 'user',
                'management' => 'layanan',
                'password' => '',
            ])
            ->assertRedirect('/admin/pengguna/' . $user->id);

        $user->refresh();
        $this->assertEquals('User Risiko Diperbarui', $user->name);
        $this->assertEquals('layanan', $user->management);
        $this->assertTrue(Hash::check('Password123!', $user->password));

        $this->actingAs($admin)
            ->put('/admin/pengguna/' . $user->id, [
                'name' => 'User Risiko Baru',
                'email' => 'risk.user.new@aisyala.local',
                'role' => 'user',
                'management' => 'aset',
                'password' => 'Password456!',
            ])
            ->assertRedirect('/admin/pengguna/' . $user->id);

        $user->refresh();
        $this->assertTrue(Hash::check('Password456!', $user->password));
    }

    public function test_admin_can_view_user_detail_without_exposing_password(): void
    {
        $admin = $this->createUser('admin', null, 'admin.detail.user@aisyala.local', 'Admin Detail User');
        $user = $this->createUser('user', 'aset', 'asset.detail.user@aisyala.local', 'User Aset Detail');

        $this->actingAs($admin)
            ->get('/admin/pengguna/' . $user->id)
            ->assertOk()
            ->assertSeeText('User Aset Detail')
            ->assertSeeText('aset')
            ->assertDontSeeText('Password123!');
    }

    public function test_admin_can_delete_user_but_not_self(): void
    {
        $admin = $this->createUser('admin', null, 'admin.delete.self@aisyala.local', 'Admin Delete User');
        $user = $this->createUser('user', 'aset', 'asset.delete.user@aisyala.local', 'User Aset Delete');

        $this->actingAs($admin)
            ->delete('/admin/pengguna/' . $user->id)
            ->assertRedirect('/admin/pengguna');

        $this->assertDatabaseMissing('users', ['email' => 'asset.delete.user@aisyala.local']);

        $this->actingAs($admin)
            ->delete('/admin/pengguna/' . $admin->id)
            ->assertSessionHas('error');
    }

    public function test_search_filter_and_pagination_work_for_user_management(): void
    {
        $admin = $this->createUser('admin', null, 'admin.filter@aisyala.local', 'Admin Filter');

        foreach (range(1, 25) as $index) {
            $this->createUser('user', $index % 2 === 0 ? 'aset' : 'risiko', 'user' . $index . '@aisyala.local', 'User ' . $index);
        }

        $this->actingAs($admin)
            ->get('/admin/pengguna?search=User 2&role=user&management=aset')
            ->assertOk()
            ->assertSeeText('User 2');

        $this->actingAs($admin)
            ->get('/admin/pengguna?role=admin')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/pengguna?page=2')
            ->assertOk();
    }
}
