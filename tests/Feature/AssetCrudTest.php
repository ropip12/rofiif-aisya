<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AssetCrudTest extends TestCase
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

    protected function createAsset(array $overrides = []): Asset
    {
        return Asset::create([
            'asset_code' => $overrides['asset_code'] ?? 'AST-001',
            'asset_name' => $overrides['asset_name'] ?? 'Laptop',
            'asset_classification' => $overrides['asset_classification'] ?? 'Perangkat Keras',
            'acquisition_date' => $overrides['acquisition_date'] ?? '2024-01-15',
            'acquisition_value' => $overrides['acquisition_value'] ?? 12000000,
            'vendor' => $overrides['vendor'] ?? 'Vendor A',
            'condition_status' => $overrides['condition_status'] ?? 'Baik',
            'location' => $overrides['location'] ?? 'Gudang 1',
            'responsible_person' => $overrides['responsible_person'] ?? 'Budi',
            'usage_status' => $overrides['usage_status'] ?? 'Digunakan',
            'useful_life_years' => $overrides['useful_life_years'] ?? 5,
            'data_storage_information' => $overrides['data_storage_information'] ?? 'Server A',
            'lifecycle_status' => $overrides['lifecycle_status'] ?? 'Aktif',
            'final_handling' => $overrides['final_handling'] ?? 'Dipelihara',
        ]);
    }

    public function test_admin_can_access_asset_crud_pages(): void
    {
        $user = $this->createUser('admin', null, 'admin@aisyala.local', 'Admin User');

        $this->actingAs($user)->get('/aset')->assertOk();
        $this->actingAs($user)->get('/aset/create')->assertOk();

        $asset = $this->createAsset();

        $this->actingAs($user)->get('/aset/' . $asset->id)->assertOk();
        $this->actingAs($user)->get('/aset/' . $asset->id . '/edit')->assertOk();
    }

    public function test_user_aset_can_create_and_update_asset(): void
    {
        $user = $this->createUser('user', 'aset', 'asset.user@aisyala.local', 'User Aset');

        $this->actingAs($user)
            ->post('/aset', [
                'asset_code' => 'AST-101',
                'asset_name' => 'Monitor 24 Inch',
                'asset_classification' => 'Perangkat Keras',
                'acquisition_date' => '2024-03-10',
                'acquisition_value' => '2500000',
                'vendor' => 'Vendor B',
                'condition_status' => 'Baik',
                'location' => 'Ruang Server',
                'responsible_person' => 'Sari',
                'usage_status' => 'Digunakan',
                'useful_life_years' => 4,
                'data_storage_information' => 'Database PC',
                'lifecycle_status' => 'Aktif',
                'final_handling' => 'Dipelihara',
            ])
            ->assertRedirect('/aset');

        $asset = Asset::first();

        $this->actingAs($user)
            ->put('/aset/' . $asset->id, [
                'asset_code' => 'AST-101',
                'asset_name' => 'Monitor 27 Inch',
                'asset_classification' => 'Perangkat Keras',
                'acquisition_date' => '2024-03-10',
                'acquisition_value' => '3000000',
                'vendor' => 'Vendor B',
                'condition_status' => 'Baik',
                'location' => 'Ruang Server',
                'responsible_person' => 'Sari',
                'usage_status' => 'Digunakan',
                'useful_life_years' => 4,
                'data_storage_information' => 'Database PC',
                'lifecycle_status' => 'Aktif',
                'final_handling' => 'Dipelihara',
            ])
            ->assertRedirect('/aset/' . $asset->id);
    }

    public function test_user_risiko_and_user_layanan_cannot_access_asset_crud(): void
    {
        $riskUser = $this->createUser('user', 'risiko', 'risk.user@aisyala.local', 'User Risiko');
        $serviceUser = $this->createUser('user', 'layanan', 'service.user@aisyala.local', 'User Layanan');

        $this->actingAs($riskUser)->get('/aset')->assertForbidden();
        $this->actingAs($riskUser)->get('/aset/create')->assertForbidden();
        $this->actingAs($riskUser)->post('/aset', [
            'asset_code' => 'FORBIDDEN-1',
            'asset_name' => 'Aset Salah',
            'asset_classification' => 'Perangkat Keras',
            'acquisition_date' => '2024-02-01',
            'acquisition_value' => '1000',
        ])->assertForbidden();

        $this->actingAs($serviceUser)->get('/aset')->assertForbidden();
        $this->actingAs($serviceUser)->get('/aset/create')->assertForbidden();
    }

    public function test_guest_cannot_access_asset_crud(): void
    {
        $this->get('/aset')->assertRedirect('/login');
        $this->get('/aset/create')->assertRedirect('/login');
    }

    public function test_asset_search_filters_by_code_and_name(): void
    {
        $user = $this->createUser('admin', null, 'admin2@aisyala.local', 'Admin User 2');

        $this->createAsset([
            'asset_code' => 'AST-ALPHA',
            'asset_name' => 'Server Utama',
            'asset_classification' => 'Perangkat Keras',
        ]);

        $this->createAsset([
            'asset_code' => 'AST-BETA',
            'asset_name' => 'Laptop Pro',
            'asset_classification' => 'Perangkat Lunak',
        ]);

        $this->actingAs($user)
            ->get('/aset?search=ALPHA')
            ->assertOk();

        $this->actingAs($user)
            ->get('/aset?classification=Perangkat%20Keras')
            ->assertOk();
    }
}
