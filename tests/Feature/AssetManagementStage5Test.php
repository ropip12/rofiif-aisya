<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetEvaluation;
use App\Models\AssetMaintenance;
use App\Models\AssetProcurement;
use App\Models\AssetUsage;
use App\Models\AssetWarehouse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AssetManagementStage5Test extends TestCase
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
            'asset_code' => $overrides['asset_code'] ?? 'AST-100',
            'asset_name' => $overrides['asset_name'] ?? 'Laptop Uji',
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

    protected function createProcurement(Asset $asset): AssetProcurement
    {
        return AssetProcurement::create([
            'asset_id' => $asset->id,
            'procurement_date' => '2024-02-01',
            'procurement_method' => 'Pembelian',
            'source_vendor' => 'Vendor Utama',
            'procurement_value' => 15000000,
            'description' => 'Pembelian awal',
        ]);
    }

    public function test_procurement_module_can_be_accessed_and_crud(): void
    {
        $admin = $this->createUser('admin', null, 'admin@aisyala.local', 'Admin');
        $userAset = $this->createUser('user', 'aset', 'aset.user@aisyala.local', 'User Aset');
        $riskUser = $this->createUser('user', 'risiko', 'risk.user@aisyala.local', 'User Risiko');

        $this->actingAs($admin)->get('/aset/pengadaan')->assertOk();
        $this->actingAs($userAset)->get('/aset/pengadaan')->assertOk();
        $this->actingAs($riskUser)->get('/aset/pengadaan')->assertForbidden();

        $asset = $this->createAsset();

        $this->actingAs($userAset)
            ->post('/aset/pengadaan', [
                'asset_id' => $asset->id,
                'procurement_date' => '2024-02-01',
                'procurement_method' => 'Pembelian',
                'source_vendor' => 'Vendor Baru',
                'procurement_value' => 20000000,
                'description' => 'Pengadaan baru',
            ])
            ->assertRedirect('/aset/pengadaan');

        $procurement = AssetProcurement::first();

        $this->actingAs($userAset)
            ->get('/aset/pengadaan/' . $procurement->id . '/edit')
            ->assertOk();

        $this->actingAs($userAset)
            ->put('/aset/pengadaan/' . $procurement->id, [
                'asset_id' => $asset->id,
                'procurement_date' => '2024-02-02',
                'procurement_method' => 'Pembelian',
                'source_vendor' => 'Vendor Diperbarui',
                'procurement_value' => 21000000,
                'description' => 'Pengadaan diperbarui',
            ])
            ->assertRedirect('/aset/pengadaan/' . $procurement->id);

        $this->actingAs($userAset)
            ->delete('/aset/pengadaan/' . $procurement->id)
            ->assertRedirect('/aset/pengadaan');
    }

    public function test_usage_module_can_be_accessed_and_crud(): void
    {
        $admin = $this->createUser('admin', null, 'admin2@aisyala.local', 'Admin 2');
        $userAset = $this->createUser('user', 'aset', 'aset.user2@aisyala.local', 'User Aset 2');
        $serviceUser = $this->createUser('user', 'layanan', 'service.user@aisyala.local', 'User Layanan');

        $asset = $this->createAsset();

        $this->actingAs($admin)->get('/aset/penggunaan')->assertOk();
        $this->actingAs($userAset)->get('/aset/penggunaan')->assertOk();
        $this->actingAs($serviceUser)->get('/aset/penggunaan')->assertForbidden();

        $this->actingAs($userAset)
            ->post('/aset/penggunaan', [
                'asset_id' => $asset->id,
                'location' => 'Ruang Server',
                'responsible_person' => 'Sari',
                'usage_status' => 'Digunakan',
                'start_date' => '2024-03-10',
                'description' => 'Dipakai oleh divisi IT',
            ])
            ->assertRedirect('/aset/penggunaan');

        $usage = AssetUsage::first();

        $this->actingAs($userAset)
            ->get('/aset/penggunaan/' . $usage->id . '/edit')
            ->assertOk();

        $this->actingAs($userAset)
            ->put('/aset/penggunaan/' . $usage->id, [
                'asset_id' => $asset->id,
                'location' => 'Ruang Data',
                'responsible_person' => 'Dina',
                'usage_status' => 'Dipinjam',
                'start_date' => '2024-03-15',
                'description' => 'Dipinjam untuk pemeliharaan',
            ])
            ->assertRedirect('/aset/penggunaan/' . $usage->id);

        $this->actingAs($userAset)
            ->delete('/aset/penggunaan/' . $usage->id)
            ->assertRedirect('/aset/penggunaan');
    }

    public function test_warehouse_module_can_be_accessed_and_crud(): void
    {
        $admin = $this->createUser('admin', null, 'admin3@aisyala.local', 'Admin 3');
        $userAset = $this->createUser('user', 'aset', 'aset.user3@aisyala.local', 'User Aset 3');
        $riskUser = $this->createUser('user', 'risiko', 'risk.user3@aisyala.local', 'User Risiko 3');

        $asset = $this->createAsset();

        $this->actingAs($admin)->get('/aset/gudang')->assertOk();
        $this->actingAs($userAset)->get('/aset/gudang')->assertOk();
        $this->actingAs($riskUser)->get('/aset/gudang')->assertForbidden();

        $this->actingAs($userAset)
            ->post('/aset/gudang', [
                'asset_id' => $asset->id,
                'warehouse_location' => 'Gudang Utama',
                'storage_status' => 'Disimpan',
                'entry_date' => '2024-04-01',
                'exit_date' => null,
                'description' => 'Menunggu penempatan',
            ])
            ->assertRedirect('/aset/gudang');

        $warehouse = AssetWarehouse::first();

        $this->actingAs($userAset)
            ->get('/aset/gudang/' . $warehouse->id . '/edit')
            ->assertOk();

        $this->actingAs($userAset)
            ->put('/aset/gudang/' . $warehouse->id, [
                'asset_id' => $asset->id,
                'warehouse_location' => 'Gudang Cadangan',
                'storage_status' => 'Dikeluarkan',
                'entry_date' => '2024-04-01',
                'exit_date' => '2024-04-20',
                'description' => 'Sudah dikeluarkan',
            ])
            ->assertRedirect('/aset/gudang/' . $warehouse->id);

        $this->actingAs($userAset)
            ->delete('/aset/gudang/' . $warehouse->id)
            ->assertRedirect('/aset/gudang');
    }

    public function test_maintenance_module_can_be_accessed_and_crud(): void
    {
        $admin = $this->createUser('admin', null, 'admin4@aisyala.local', 'Admin 4');
        $userAset = $this->createUser('user', 'aset', 'aset.user4@aisyala.local', 'User Aset 4');
        $serviceUser = $this->createUser('user', 'layanan', 'service.user4@aisyala.local', 'User Layanan 4');

        $asset = $this->createAsset();

        $this->actingAs($admin)->get('/aset/pemeliharaan')->assertOk();
        $this->actingAs($userAset)->get('/aset/pemeliharaan')->assertOk();
        $this->actingAs($serviceUser)->get('/aset/pemeliharaan')->assertForbidden();

        $this->actingAs($userAset)
            ->post('/aset/pemeliharaan', [
                'asset_id' => $asset->id,
                'maintenance_date' => '2024-05-02',
                'maintenance_type' => 'Preventif',
                'vendor' => 'Vendor Service',
                'cost' => 2500000,
                'maintenance_result' => 'Selesai',
                'description' => 'Pembersihan dan pengecekan',
            ])
            ->assertRedirect('/aset/pemeliharaan');

        $maintenance = AssetMaintenance::first();

        $this->actingAs($userAset)
            ->get('/aset/pemeliharaan/' . $maintenance->id . '/edit')
            ->assertOk();

        $this->actingAs($userAset)
            ->put('/aset/pemeliharaan/' . $maintenance->id, [
                'asset_id' => $asset->id,
                'maintenance_date' => '2024-05-05',
                'maintenance_type' => 'Korektif',
                'vendor' => 'Vendor Service Baru',
                'cost' => 3000000,
                'maintenance_result' => 'Selesai',
                'description' => 'Perbaikan komponen utama',
            ])
            ->assertRedirect('/aset/pemeliharaan/' . $maintenance->id);

        $this->actingAs($userAset)
            ->delete('/aset/pemeliharaan/' . $maintenance->id)
            ->assertRedirect('/aset/pemeliharaan');
    }

    public function test_evaluation_module_can_be_accessed_and_crud(): void
    {
        $admin = $this->createUser('admin', null, 'admin5@aisyala.local', 'Admin 5');
        $userAset = $this->createUser('user', 'aset', 'aset.user5@aisyala.local', 'User Aset 5');
        $riskUser = $this->createUser('user', 'risiko', 'risk.user5@aisyala.local', 'User Risiko 5');

        $asset = $this->createAsset();

        $this->actingAs($admin)->get('/aset/evaluasi')->assertOk();
        $this->actingAs($userAset)->get('/aset/evaluasi')->assertOk();
        $this->actingAs($riskUser)->get('/aset/evaluasi')->assertForbidden();

        $this->actingAs($userAset)
            ->post('/aset/evaluasi', [
                'asset_id' => $asset->id,
                'evaluation_date' => '2024-06-01',
                'condition' => 'Baik',
                'evaluation_result' => 'Layak dipakai',
                'recommendation' => 'Tetap digunakan dengan pemeliharaan rutin',
                'follow_up_status' => 'Dilanjutkan',
                'description' => 'Evaluasi rutin kuartal',
            ])
            ->assertRedirect('/aset/evaluasi');

        $evaluation = AssetEvaluation::first();

        $this->actingAs($userAset)
            ->get('/aset/evaluasi/' . $evaluation->id . '/edit')
            ->assertOk();

        $this->actingAs($userAset)
            ->put('/aset/evaluasi/' . $evaluation->id, [
                'asset_id' => $asset->id,
                'evaluation_date' => '2024-06-05',
                'condition' => 'Rusak Ringan',
                'evaluation_result' => 'Perlu perbaikan',
                'recommendation' => 'Perbaikan segera',
                'follow_up_status' => 'Perlu Tindak Lanjut',
                'description' => 'Evaluasi setelah perawatan',
            ])
            ->assertRedirect('/aset/evaluasi/' . $evaluation->id);

        $this->actingAs($userAset)
            ->delete('/aset/evaluasi/' . $evaluation->id)
            ->assertRedirect('/aset/evaluasi');
    }
}
