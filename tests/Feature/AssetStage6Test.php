<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetFinalHandling;
use App\Models\AssetHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AssetStage6Test extends TestCase
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
            'asset_code' => $overrides['asset_code'] ?? 'AST-200',
            'asset_name' => $overrides['asset_name'] ?? 'Laptop Uji 2',
            'asset_classification' => $overrides['asset_classification'] ?? 'Perangkat Keras',
            'acquisition_date' => $overrides['acquisition_date'] ?? '2024-01-10',
            'acquisition_value' => $overrides['acquisition_value'] ?? 15000000,
            'vendor' => $overrides['vendor'] ?? 'Vendor Uji',
            'condition_status' => $overrides['condition_status'] ?? 'Baik',
            'location' => $overrides['location'] ?? 'Ruang Umum',
            'responsible_person' => $overrides['responsible_person'] ?? 'Budi',
            'usage_status' => $overrides['usage_status'] ?? 'Digunakan',
            'useful_life_years' => $overrides['useful_life_years'] ?? 5,
            'data_storage_information' => $overrides['data_storage_information'] ?? 'Server A',
            'lifecycle_status' => $overrides['lifecycle_status'] ?? 'Aktif',
            'final_handling' => $overrides['final_handling'] ?? 'Dipelihara',
        ]);
    }

    public function test_asset_final_handling_module_can_be_accessed_and_crud(): void
    {
        $admin = $this->createUser('admin', null, 'admin6@aisyala.local', 'Admin 6');
        $userAset = $this->createUser('user', 'aset', 'aset.user6@aisyala.local', 'User Aset 6');
        $riskUser = $this->createUser('user', 'risiko', 'risk.user6@aisyala.local', 'User Risiko 6');
        $serviceUser = $this->createUser('user', 'layanan', 'service.user6@aisyala.local', 'User Layanan 6');
        $asset = $this->createAsset();

        $this->actingAs($admin)->get('/aset/penanganan-akhir')->assertOk();
        $this->actingAs($userAset)->get('/aset/penanganan-akhir')->assertOk();
        $this->actingAs($riskUser)->get('/aset/penanganan-akhir')->assertForbidden();
        $this->actingAs($serviceUser)->get('/aset/penanganan-akhir')->assertForbidden();

        $this->actingAs($userAset)
            ->post('/aset/penanganan-akhir', [
                'asset_id' => $asset->id,
                'final_handling_type' => 'Dipindahkan',
                'handling_date' => '2024-08-01',
                'reason' => 'Perubahan lokasi kerja',
                'description' => 'Aset dipindahkan ke unit lain',
                'status' => 'Selesai',
            ])
            ->assertRedirect('/aset/penanganan-akhir');

        $finalHandling = AssetFinalHandling::first();

        $this->actingAs($userAset)
            ->get('/aset/penanganan-akhir/' . $finalHandling->id . '/edit')
            ->assertOk();

        $this->actingAs($userAset)
            ->put('/aset/penanganan-akhir/' . $finalHandling->id, [
                'asset_id' => $asset->id,
                'final_handling_type' => 'Diperbaiki',
                'handling_date' => '2024-08-02',
                'reason' => 'Perbaikan teknis',
                'description' => 'Setelah dipindahkan, aset masuk tahap perbaikan',
                'status' => 'Dalam Proses',
            ])
            ->assertRedirect('/aset/penanganan-akhir/' . $finalHandling->id);

        $this->actingAs($userAset)
            ->post('/aset/penanganan-akhir', [
                'asset_id' => $asset->id,
                'final_handling_type' => '',
                'handling_date' => 'invalid-date',
                'reason' => '',
            ])
            ->assertSessionHasErrors(['final_handling_type', 'handling_date', 'reason']);

        $this->actingAs($userAset)
            ->delete('/aset/penanganan-akhir/' . $finalHandling->id)
            ->assertRedirect('/aset/penanganan-akhir');
    }

    public function test_asset_history_module_can_be_accessed_and_data_is_recorded(): void
    {
        $admin = $this->createUser('admin', null, 'admin7@aisyala.local', 'Admin 7');
        $userAset = $this->createUser('user', 'aset', 'aset.user7@aisyala.local', 'User Aset 7');
        $riskUser = $this->createUser('user', 'risiko', 'risk.user7@aisyala.local', 'User Risiko 7');

        $asset = $this->createAsset();

        AssetHistory::create([
            'asset_id' => $asset->id,
            'activity_type' => 'Aset dibuat',
            'activity_date' => '2024-09-01',
            'change_info' => 'Data aset baru dibuat',
            'description' => 'Pembuatan master aset awal',
        ]);

        $this->actingAs($admin)->get('/aset/riwayat')->assertOk();
        $this->actingAs($userAset)->get('/aset/riwayat')->assertOk();
        $this->actingAs($riskUser)->get('/aset/riwayat')->assertForbidden();

        $this->actingAs($userAset)
            ->get('/aset/riwayat')
            ->assertSeeText('Aset dibuat')
            ->assertSeeText('Data aset baru dibuat');

        $this->assertDatabaseHas('asset_histories', [
            'asset_id' => $asset->id,
            'activity_type' => 'Aset dibuat',
        ]);
    }
}
