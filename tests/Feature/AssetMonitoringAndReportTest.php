<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AssetMonitoringAndReportTest extends TestCase
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
            'asset_code' => $overrides['asset_code'] ?? 'AST-300',
            'asset_name' => $overrides['asset_name'] ?? 'Laptop Monitoring',
            'asset_classification' => $overrides['asset_classification'] ?? 'Perangkat Keras',
            'acquisition_date' => $overrides['acquisition_date'] ?? '2024-01-15',
            'acquisition_value' => $overrides['acquisition_value'] ?? 18000000,
            'vendor' => $overrides['vendor'] ?? 'Vendor Monitoring',
            'condition_status' => $overrides['condition_status'] ?? 'Baik',
            'location' => $overrides['location'] ?? 'Ruang Server',
            'responsible_person' => $overrides['responsible_person'] ?? 'Budi',
            'usage_status' => $overrides['usage_status'] ?? 'Digunakan',
            'useful_life_years' => $overrides['useful_life_years'] ?? 5,
            'data_storage_information' => $overrides['data_storage_information'] ?? 'Server A',
            'lifecycle_status' => $overrides['lifecycle_status'] ?? 'Aktif',
            'final_handling' => $overrides['final_handling'] ?? 'Dipelihara',
        ]);
    }

    public function test_monitoring_page_access_and_filtering(): void
    {
        $admin = $this->createUser('admin', null, 'admin.monitor@aisyala.local', 'Admin Monitoring');
        $userAset = $this->createUser('user', 'aset', 'aset.monitor@aisyala.local', 'User Aset Monitoring');
        $riskUser = $this->createUser('user', 'risiko', 'risk.monitor@aisyala.local', 'User Risiko Monitoring');
        $serviceUser = $this->createUser('user', 'layanan', 'service.monitor@aisyala.local', 'User Layanan Monitoring');

        $asset = $this->createAsset();

        $this->actingAs($admin)->get('/aset/monitoring')->assertOk()->assertSeeText('Monitoring Aset');
        $this->actingAs($userAset)->get('/aset/monitoring')->assertOk();
        $this->actingAs($riskUser)->get('/aset/monitoring')->assertForbidden();
        $this->actingAs($serviceUser)->get('/aset/monitoring')->assertForbidden();

        auth()->logout();

        $this->get('/aset/monitoring')->assertRedirect('/login');

        $this->actingAs($admin)
            ->get('/aset/monitoring?search=' . urlencode('Laptop Monitoring') . '&classification=Perangkat%20Keras&condition=Baik&usage_status=Digunakan&lifecycle=Aktif&location=Ruang%20Server')
            ->assertOk()
            ->assertSeeText($asset->asset_name);
    }

    public function test_report_page_access_and_filtering(): void
    {
        $admin = $this->createUser('admin', null, 'admin.report@aisyala.local', 'Admin Report');
        $userAset = $this->createUser('user', 'aset', 'aset.report@aisyala.local', 'User Aset Report');
        $riskUser = $this->createUser('user', 'risiko', 'risk.report@aisyala.local', 'User Risiko Report');
        $serviceUser = $this->createUser('user', 'layanan', 'service.report@aisyala.local', 'User Layanan Report');

        $asset = $this->createAsset();

        $this->actingAs($admin)->get('/aset/laporan')->assertOk()->assertSeeText('Laporan Aset');
        $this->actingAs($userAset)->get('/aset/laporan')->assertOk();
        $this->actingAs($riskUser)->get('/aset/laporan')->assertForbidden();
        $this->actingAs($serviceUser)->get('/aset/laporan')->assertForbidden();

        auth()->logout();

        $this->get('/aset/laporan')->assertRedirect('/login');

        $this->actingAs($admin)
            ->get('/aset/laporan?from_date=2024-01-01&to_date=2024-12-31&classification=Perangkat%20Keras&condition=Baik&location=Ruang%20Server&usage_status=Digunakan&lifecycle=Aktif')
            ->assertOk()
            ->assertSeeText($asset->asset_name);

        $this->actingAs($admin)
            ->get('/aset/laporan?from_date=2025-01-01&to_date=2024-12-31')
            ->assertSessionHasErrors(['from_date']);
    }

    public function test_report_export_download_works(): void
    {
        $admin = $this->createUser('admin', null, 'admin.export@aisyala.local', 'Admin Export');
        $this->createAsset();

        $this->actingAs($admin)
            ->get('/aset/laporan/export?classification=Perangkat%20Keras')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}
