<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Risk;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RiskMonitoringEvaluationReportTest extends TestCase
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
            'asset_code' => $overrides['asset_code'] ?? 'AST-MON-001',
            'asset_name' => $overrides['asset_name'] ?? 'Server Monitoring',
            'asset_classification' => $overrides['asset_classification'] ?? 'Perangkat Keras',
            'acquisition_date' => $overrides['acquisition_date'] ?? '2024-01-15',
            'acquisition_value' => $overrides['acquisition_value'] ?? 20000000,
            'vendor' => $overrides['vendor'] ?? 'Vendor Monitoring',
            'condition_status' => $overrides['condition_status'] ?? 'Baik',
            'location' => $overrides['location'] ?? 'Data Center',
            'responsible_person' => $overrides['responsible_person'] ?? 'Budi',
            'usage_status' => $overrides['usage_status'] ?? 'Digunakan',
            'useful_life_years' => $overrides['useful_life_years'] ?? 5,
            'data_storage_information' => $overrides['data_storage_information'] ?? 'Server utama',
            'lifecycle_status' => $overrides['lifecycle_status'] ?? 'Aktif',
            'final_handling' => $overrides['final_handling'] ?? 'Pemeliharaan rutin',
        ]);
    }

    protected function createService(array $overrides = []): Service
    {
        return Service::create([
            'service_code' => $overrides['service_code'] ?? 'SRV-MON-001',
            'service_name' => $overrides['service_name'] ?? 'Layanan Email',
            'service_description' => $overrides['service_description'] ?? 'Deskripsi layanan',
            'service_type' => $overrides['service_type'] ?? 'Internal',
            'service_owner' => $overrides['service_owner'] ?? 'Tim IT',
            'service_status' => $overrides['service_status'] ?? 'Aktif',
            'supporting_information' => $overrides['supporting_information'] ?? 'Catatan',
            'monitoring' => $overrides['monitoring'] ?? 'Monitoring rutin',
            'evaluation' => $overrides['evaluation'] ?? 'Evaluasi bulanan',
        ]);
    }

    protected function createRisk(array $overrides = []): Risk
    {
        return Risk::create([
            'risk_code' => $overrides['risk_code'] ?? 'R-MON-001',
            'risk_name' => $overrides['risk_name'] ?? 'Gangguan Server',
            'cause' => $overrides['cause'] ?? 'Pemadaman listrik',
            'impact' => $overrides['impact'] ?? 'Layanan terganggu',
            'likelihood' => $overrides['likelihood'] ?? 'Tinggi',
            'risk_level' => $overrides['risk_level'] ?? 'Tinggi',
            'control_measures' => $overrides['control_measures'] ?? 'UPS dan backup',
            'mitigation_plan' => $overrides['mitigation_plan'] ?? 'Generator cadangan',
            'risk_status' => $overrides['risk_status'] ?? 'Aktif',
            'monitoring' => $overrides['monitoring'] ?? 'Pemantauan harian',
            'evaluation' => $overrides['evaluation'] ?? 'Evaluasi mingguan',
        ]);
    }

    public function test_risk_monitoring_pages_are_authorized_and_accessible(): void
    {
        $admin = $this->createUser('admin', null, 'admin.monitor@aisyala.local', 'Admin Monitoring');
        $riskUser = $this->createUser('user', 'risiko', 'risk.monitor@aisyala.local', 'Risk Monitoring User');
        $assetUser = $this->createUser('user', 'aset', 'aset.monitor@aisyala.local', 'Aset Monitoring User');
        $serviceUser = $this->createUser('user', 'layanan', 'service.monitor@aisyala.local', 'Layanan Monitoring User');

        $this->actingAs($admin)->get('/risiko/monitoring')->assertOk();
        $this->actingAs($admin)->get('/risiko/evaluasi')->assertOk();
        $this->actingAs($admin)->get('/risiko/laporan')->assertOk();

        $this->actingAs($riskUser)->get('/risiko/monitoring')->assertOk();
        $this->actingAs($riskUser)->get('/risiko/evaluasi')->assertOk();
        $this->actingAs($riskUser)->get('/risiko/laporan')->assertOk();

        $this->actingAs($assetUser)->get('/risiko/monitoring')->assertForbidden();
        $this->actingAs($assetUser)->get('/risiko/evaluasi')->assertForbidden();
        $this->actingAs($assetUser)->get('/risiko/laporan')->assertForbidden();

        $this->actingAs($serviceUser)->get('/risiko/monitoring')->assertForbidden();
        $this->actingAs($serviceUser)->get('/risiko/evaluasi')->assertForbidden();
        $this->actingAs($serviceUser)->get('/risiko/laporan')->assertForbidden();
    }

    public function test_risk_monitoring_evaluation_and_report_pages_redirect_guests_to_login(): void
    {
        $this->get('/risiko/monitoring')->assertRedirect('/login');
        $this->get('/risiko/evaluasi')->assertRedirect('/login');
        $this->get('/risiko/laporan')->assertRedirect('/login');
    }

    public function test_monitoring_page_displays_data_filters_and_updates_status(): void
    {
        $riskUser = $this->createUser('user', 'risiko', 'risk.filter@aisyala.local', 'Risk Filter User');
        $asset = $this->createAsset(['asset_code' => 'AST-1001', 'asset_name' => 'Server Utama']);
        $service = $this->createService(['service_code' => 'SRV-1001', 'service_name' => 'Layanan Data']);
        $risk = $this->createRisk([
            'risk_code' => 'R-1001',
            'risk_name' => 'Kegagalan Server',
            'likelihood' => 'Tinggi',
            'risk_level' => 'Tinggi',
            'risk_status' => 'Aktif',
            'monitoring' => 'Pantau setiap jam',
        ]);
        $risk->assets()->sync([$asset->id]);
        $risk->services()->sync([$service->id]);

        $this->actingAs($riskUser)
            ->get('/risiko/monitoring?search=Kegagalan&risk_level=Tinggi&risk_status=Aktif&likelihood=Tinggi')
            ->assertOk()
            ->assertSeeText('Kegagalan Server')
            ->assertSeeText('Pantau setiap jam');

        $this->actingAs($riskUser)
            ->put('/risiko/monitoring/' . $risk->id, [
                'risk_status' => 'Dalam Pemantauan',
                'monitoring' => 'Pemantauan real-time',
            ])
            ->assertRedirect('/risiko/monitoring');

        $risk->refresh();
        $this->assertEquals('Dalam Pemantauan', $risk->risk_status);
        $this->assertEquals('Pemantauan real-time', $risk->monitoring);
    }

    public function test_evaluation_page_displays_data_and_updates_evaluation(): void
    {
        $riskUser = $this->createUser('user', 'risiko', 'risk.eval@aisyala.local', 'Risk Evaluation User');
        $risk = $this->createRisk([
            'risk_code' => 'R-2002',
            'risk_name' => 'Gangguan Akses',
            'risk_level' => 'Menengah',
            'risk_status' => 'Aktif',
            'evaluation' => 'Evaluasi awal',
        ]);

        $this->actingAs($riskUser)
            ->get('/risiko/evaluasi?search=Gangguan&risk_level=Menengah&risk_status=Aktif')
            ->assertOk()
            ->assertSeeText('Gangguan Akses')
            ->assertSeeText('Evaluasi awal');

        $this->actingAs($riskUser)
            ->put('/risiko/evaluasi/' . $risk->id, [
                'risk_status' => 'Ditutup',
                'evaluation' => 'Evaluasi final setelah penanganan',
            ])
            ->assertRedirect('/risiko/evaluasi');

        $risk->refresh();
        $this->assertEquals('Ditutup', $risk->risk_status);
        $this->assertEquals('Evaluasi final setelah penanganan', $risk->evaluation);
    }

    public function test_report_page_works_for_data_and_empty_database(): void
    {
        $riskUser = $this->createUser('user', 'risiko', 'risk.report@aisyala.local', 'Risk Report User');

        $this->actingAs($riskUser)->get('/risiko/laporan')->assertOk()->assertSeeText('Laporan Risiko');

        $this->actingAs($riskUser)->get('/risiko/laporan?risk_level=Tinggi&risk_status=Aktif&likelihood=Tinggi')->assertOk();

        $asset = $this->createAsset(['asset_code' => 'AST-REPORT-1', 'asset_name' => 'Asset Laporan']);
        $service = $this->createService(['service_code' => 'SRV-REPORT-1', 'service_name' => 'Layanan Laporan']);
        $risk = $this->createRisk([
            'risk_code' => 'R-REPORT-1',
            'risk_name' => 'Risiko Laporan',
            'likelihood' => 'Tinggi',
            'risk_level' => 'Tinggi',
            'risk_status' => 'Aktif',
            'monitoring' => 'Pantau bulanan',
            'evaluation' => 'Evaluasi laporan',
        ]);
        $risk->assets()->sync([$asset->id]);
        $risk->services()->sync([$service->id]);

        $this->actingAs($riskUser)
            ->get('/risiko/laporan?risk_level=Tinggi&risk_status=Aktif&likelihood=Tinggi')
            ->assertOk()
            ->assertSeeText('Risiko Laporan')
            ->assertSeeText('Asset Laporan')
            ->assertSeeText('Layanan Laporan');

        $this->assertDatabaseHas('risks', ['id' => $risk->id]);
    }

    public function test_risk_relationships_are_safe_when_empty_on_monitoring_and_report_pages(): void
    {
        $riskUser = $this->createUser('user', 'risiko', 'risk.empty@aisyala.local', 'Risk Empty User');
        $risk = $this->createRisk([
            'risk_code' => 'R-EMPTY-1',
            'risk_name' => 'Risiko Tanpa Relasi',
            'risk_level' => 'Rendah',
            'risk_status' => 'Aktif',
        ]);

        $this->actingAs($riskUser)->get('/risiko/monitoring')->assertOk();
        $this->actingAs($riskUser)->get('/risiko/evaluasi')->assertOk();
        $this->actingAs($riskUser)->get('/risiko/laporan')->assertOk();

        $this->assertDatabaseHas('risks', ['id' => $risk->id]);
        $this->assertEquals(0, $risk->assets()->count());
        $this->assertEquals(0, $risk->services()->count());
    }
}
