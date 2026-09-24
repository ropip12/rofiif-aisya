<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Risk;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ServiceMonitoringEvaluationReportTest extends TestCase
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
            'asset_code' => $overrides['asset_code'] ?? 'AST-SVC-001',
            'asset_name' => $overrides['asset_name'] ?? 'Server Layanan',
            'asset_classification' => $overrides['asset_classification'] ?? 'Perangkat Keras',
            'acquisition_date' => $overrides['acquisition_date'] ?? '2024-01-01',
            'acquisition_value' => $overrides['acquisition_value'] ?? 10000000,
            'vendor' => $overrides['vendor'] ?? 'Vendor A',
            'condition_status' => $overrides['condition_status'] ?? 'Baik',
            'location' => $overrides['location'] ?? 'Ruang Server',
            'responsible_person' => $overrides['responsible_person'] ?? 'Budi',
            'usage_status' => $overrides['usage_status'] ?? 'Digunakan',
            'useful_life_years' => $overrides['useful_life_years'] ?? 5,
            'data_storage_information' => $overrides['data_storage_information'] ?? 'Storage A',
            'lifecycle_status' => $overrides['lifecycle_status'] ?? 'Aktif',
            'final_handling' => $overrides['final_handling'] ?? 'Dipelihara',
        ]);
    }

    protected function createRisk(array $overrides = []): Risk
    {
        return Risk::create([
            'risk_code' => $overrides['risk_code'] ?? 'RIS-SVC-001',
            'risk_name' => $overrides['risk_name'] ?? 'Gangguan Jaringan',
            'cause' => $overrides['cause'] ?? 'Jaringan overload',
            'impact' => $overrides['impact'] ?? 'Layanan terganggu',
            'likelihood' => $overrides['likelihood'] ?? 'Tinggi',
            'risk_level' => $overrides['risk_level'] ?? 'Tinggi',
            'risk_status' => $overrides['risk_status'] ?? 'Aktif',
            'monitoring' => $overrides['monitoring'] ?? 'Pantau setiap hari',
            'evaluation' => $overrides['evaluation'] ?? 'Evaluasi bulanan',
            'control_measures' => $overrides['control_measures'] ?? 'Cadangan bandwidth',
            'mitigation_plan' => $overrides['mitigation_plan'] ?? 'Upgrade jaringan',
        ]);
    }

    protected function createService(array $overrides = []): Service
    {
        return Service::create([
            'service_code' => $overrides['service_code'] ?? 'SRV-900',
            'service_name' => $overrides['service_name'] ?? 'Layanan Email',
            'service_description' => $overrides['service_description'] ?? 'Deskripsi layanan',
            'service_type' => $overrides['service_type'] ?? 'Internal',
            'service_owner' => $overrides['service_owner'] ?? 'Tim IT',
            'service_status' => $overrides['service_status'] ?? 'Aktif',
            'supporting_information' => $overrides['supporting_information'] ?? 'Catatan pendukung',
            'monitoring' => $overrides['monitoring'] ?? 'Monitoring rutin',
            'evaluation' => $overrides['evaluation'] ?? 'Evaluasi bulanan',
        ]);
    }

    public function test_service_monitoring_evaluation_and_report_pages_are_authorized_and_accessible(): void
    {
        $admin = $this->createUser('admin', null, 'admin.service.monitor@aisyala.local', 'Admin Service Monitor');
        $serviceUser = $this->createUser('user', 'layanan', 'service.user.monitor@aisyala.local', 'User Layanan Monitoring');
        $assetUser = $this->createUser('user', 'aset', 'asset.user.monitor@aisyala.local', 'User Aset Monitor');
        $riskUser = $this->createUser('user', 'risiko', 'risk.user.monitor@aisyala.local', 'User Risiko Monitor');

        $this->actingAs($admin)->get('/layanan/monitoring')->assertOk();
        $this->actingAs($admin)->get('/layanan/evaluasi')->assertOk();
        $this->actingAs($admin)->get('/layanan/laporan')->assertOk();

        $this->actingAs($serviceUser)->get('/layanan/monitoring')->assertOk();
        $this->actingAs($serviceUser)->get('/layanan/evaluasi')->assertOk();
        $this->actingAs($serviceUser)->get('/layanan/laporan')->assertOk();

        $this->actingAs($assetUser)->get('/layanan/monitoring')->assertForbidden();
        $this->actingAs($assetUser)->get('/layanan/evaluasi')->assertForbidden();
        $this->actingAs($assetUser)->get('/layanan/laporan')->assertForbidden();

        $this->actingAs($riskUser)->get('/layanan/monitoring')->assertForbidden();
        $this->actingAs($riskUser)->get('/layanan/evaluasi')->assertForbidden();
        $this->actingAs($riskUser)->get('/layanan/laporan')->assertForbidden();
    }

    public function test_service_monitoring_evaluation_and_report_pages_redirect_guests_to_login(): void
    {
        $this->get('/layanan/monitoring')->assertRedirect('/login');
        $this->get('/layanan/evaluasi')->assertRedirect('/login');
        $this->get('/layanan/laporan')->assertRedirect('/login');
    }

    public function test_monitoring_page_displays_data_filters_and_updates_monitoring(): void
    {
        $serviceUser = $this->createUser('user', 'layanan', 'service.monitoring@aisyala.local', 'User Monitoring Layanan');
        $asset = $this->createAsset(['asset_code' => 'AST-SVC-010', 'asset_name' => 'Server Utama']);
        $risk = $this->createRisk(['risk_code' => 'RIS-SVC-010', 'risk_name' => 'Kerusakan Komponen']);

        $service = $this->createService([
            'service_code' => 'SRV-901',
            'service_name' => 'Layanan Aplikasi',
            'service_type' => 'Internal',
            'service_owner' => 'Tim Aplikasi',
            'service_status' => 'Aktif',
            'monitoring' => 'Pantau tiap jam',
            'evaluation' => 'Evaluasi bulanan',
        ]);
        $service->assets()->sync([$asset->id]);
        $service->risks()->sync([$risk->id]);

        $this->actingAs($serviceUser)
            ->get('/layanan/monitoring?search=Aplikasi&service_type=Internal&service_status=Aktif&service_owner=Tim%20Aplikasi')
            ->assertOk()
            ->assertSeeText('Layanan Aplikasi')
            ->assertSeeText('Pantau tiap jam');

        $this->actingAs($serviceUser)
            ->put('/layanan/monitoring/' . $service->id, [
                'service_status' => 'Dalam Pemantauan',
                'monitoring' => 'Pemantauan real-time',
            ])
            ->assertRedirect('/layanan/monitoring');

        $service->refresh();
        $this->assertEquals('Dalam Pemantauan', $service->service_status);
        $this->assertEquals('Pemantauan real-time', $service->monitoring);
    }

    public function test_evaluation_page_displays_data_updates_evaluation_and_validates(): void
    {
        $serviceUser = $this->createUser('user', 'layanan', 'service.evaluation@aisyala.local', 'User Evaluasi Layanan');
        $asset = $this->createAsset(['asset_code' => 'AST-SVC-020', 'asset_name' => 'Storage Data']);
        $risk = $this->createRisk(['risk_code' => 'RIS-SVC-020', 'risk_name' => 'Risiko Keamanan']);

        $service = $this->createService([
            'service_code' => 'SRV-902',
            'service_name' => 'Layanan Data',
            'service_type' => 'Eksternal',
            'service_owner' => 'Tim Data',
            'service_status' => 'Aktif',
            'monitoring' => 'Pemantauan mingguan',
            'evaluation' => 'Evaluasi awal',
        ]);
        $service->assets()->sync([$asset->id]);
        $service->risks()->sync([$risk->id]);

        $this->actingAs($serviceUser)
            ->get('/layanan/evaluasi?search=Data&service_type=Eksternal&service_status=Aktif')
            ->assertOk()
            ->assertSeeText('Layanan Data')
            ->assertSeeText('Evaluasi awal');

        $this->actingAs($serviceUser)
            ->put('/layanan/evaluasi/' . $service->id, [
                'service_status' => 'Dalam Pemantauan',
                'evaluation' => 'Evaluasi final setelah penanganan',
            ])
            ->assertRedirect('/layanan/evaluasi');

        $service->refresh();
        $this->assertEquals('Dalam Pemantauan', $service->service_status);
        $this->assertEquals('Evaluasi final setelah penanganan', $service->evaluation);

        $this->actingAs($serviceUser)
            ->put('/layanan/evaluasi/' . $service->id, [
                'service_status' => '',
                'evaluation' => '',
            ])
            ->assertSessionHasErrors(['service_status']);
    }

    public function test_report_page_works_for_database_data_and_empty_database(): void
    {
        $admin = $this->createUser('admin', null, 'admin.service.report@aisyala.local', 'Admin Report Layanan');
        $service = $this->createService([
            'service_code' => 'SRV-903',
            'service_name' => 'Layanan Laporan',
            'service_type' => 'Campuran',
            'service_owner' => 'Tim Pelaporan',
            'service_status' => 'Aktif',
            'monitoring' => 'Pemantauan bulanan',
            'evaluation' => 'Evaluasi laporan',
        ]);
        $asset = $this->createAsset(['asset_code' => 'AST-SVC-030', 'asset_name' => 'Server Pelaporan']);
        $risk = $this->createRisk(['risk_code' => 'RIS-SVC-030', 'risk_name' => 'Kegagalan Backup']);
        $service->assets()->sync([$asset->id]);
        $service->risks()->sync([$risk->id]);

        $this->actingAs($admin)->get('/layanan/laporan?service_type=Campuran&service_status=Aktif&service_owner=Tim%20Pelaporan')->assertOk()->assertSeeText('Layanan Laporan');

        $this->actingAs($admin)->get('/layanan/laporan')->assertOk();

        Service::query()->delete();

        $this->actingAs($admin)->get('/layanan/laporan')->assertOk();
    }

    public function test_service_relationships_are_safe_when_empty_or_populated_on_monitoring_and_report_pages(): void
    {
        $admin = $this->createUser('admin', null, 'admin.service.relationship@aisyala.local', 'Admin Relasi Layanan');

        $serviceWithoutRelations = $this->createService([
            'service_code' => 'SRV-904',
            'service_name' => 'Layanan Tanpa Relasi',
            'service_type' => 'Internal',
            'service_owner' => 'Tim Internal',
            'service_status' => 'Aktif',
        ]);

        $this->actingAs($admin)->get('/layanan/monitoring')->assertOk();
        $this->actingAs($admin)->get('/layanan/laporan')->assertOk();

        $assetOne = $this->createAsset(['asset_code' => 'AST-SVC-040', 'asset_name' => 'Asset A']);
        $assetTwo = $this->createAsset(['asset_code' => 'AST-SVC-041', 'asset_name' => 'Asset B']);
        $riskOne = $this->createRisk(['risk_code' => 'RIS-SVC-040', 'risk_name' => 'Risiko A']);
        $riskTwo = $this->createRisk(['risk_code' => 'RIS-SVC-041', 'risk_name' => 'Risiko B']);

        $serviceWithRelations = $this->createService([
            'service_code' => 'SRV-905',
            'service_name' => 'Layanan Dengan Relasi',
            'service_type' => 'Internal',
            'service_owner' => 'Tim Internal',
            'service_status' => 'Aktif',
        ]);
        $serviceWithRelations->assets()->sync([$assetOne->id, $assetTwo->id]);
        $serviceWithRelations->risks()->sync([$riskOne->id, $riskTwo->id]);

        $this->actingAs($admin)
            ->get('/layanan/' . $serviceWithRelations->id)
            ->assertOk()
            ->assertSeeText('Asset A')
            ->assertSeeText('Risiko A');
    }
}
