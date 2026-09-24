<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Risk;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RiskManagementTest extends TestCase
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

    protected function createService(array $overrides = []): Service
    {
        return Service::create([
            'service_code' => $overrides['service_code'] ?? 'SRV-001',
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

    protected function createRisk(array $overrides = []): Risk
    {
        return Risk::create([
            'risk_code' => $overrides['risk_code'] ?? 'R-001',
            'risk_name' => $overrides['risk_name'] ?? 'Gangguan Server',
            'cause' => $overrides['cause'] ?? 'Pemadaman listrik',
            'impact' => $overrides['impact'] ?? 'Layanan terganggu',
            'likelihood' => $overrides['likelihood'] ?? 'Tinggi',
            'risk_level' => $overrides['risk_level'] ?? 'Tinggi',
            'control_measures' => $overrides['control_measures'] ?? 'UPS dan backup',
            'mitigation_plan' => $overrides['mitigation_plan'] ?? 'Menyediakan backup generator',
            'risk_status' => $overrides['risk_status'] ?? 'Aktif',
            'monitoring' => $overrides['monitoring'] ?? 'Monitoring harian',
            'evaluation' => $overrides['evaluation'] ?? 'Evaluasi mingguan',
        ]);
    }

    public function test_admin_and_user_risiko_can_access_risk_pages(): void
    {
        $admin = $this->createUser('admin', null, 'admin@aisyala.local', 'Admin');
        $riskUser = $this->createUser('user', 'risiko', 'risk.user@aisyala.local', 'Risk User');

        $this->actingAs($admin)->get('/risiko')->assertOk();
        $this->actingAs($admin)->get('/risiko/identifikasi')->assertOk();
        $this->actingAs($admin)->get('/risiko/penilaian')->assertOk();
        $this->actingAs($admin)->get('/risiko/pengendalian')->assertOk();

        $this->actingAs($riskUser)->get('/risiko')->assertOk();
        $this->actingAs($riskUser)->get('/risiko/identifikasi')->assertOk();
        $this->actingAs($riskUser)->get('/risiko/penilaian')->assertOk();
        $this->actingAs($riskUser)->get('/risiko/pengendalian')->assertOk();
    }

    public function test_user_aset_and_user_layanan_cannot_access_risk_pages(): void
    {
        $assetUser = $this->createUser('user', 'aset', 'asset.user@aisyala.local', 'Aset User');
        $serviceUser = $this->createUser('user', 'layanan', 'service.user@aisyala.local', 'Layanan User');

        $this->actingAs($assetUser)->get('/risiko')->assertForbidden();
        $this->actingAs($assetUser)->get('/risiko/identifikasi')->assertForbidden();
        $this->actingAs($assetUser)->get('/risiko/penilaian')->assertForbidden();
        $this->actingAs($assetUser)->get('/risiko/pengendalian')->assertForbidden();

        $this->actingAs($serviceUser)->get('/risiko')->assertForbidden();
        $this->actingAs($serviceUser)->get('/risiko/identifikasi')->assertForbidden();
        $this->actingAs($serviceUser)->get('/risiko/penilaian')->assertForbidden();
        $this->actingAs($serviceUser)->get('/risiko/pengendalian')->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_for_risk_pages(): void
    {
        $this->get('/risiko')->assertRedirect('/login');
        $this->get('/risiko/identifikasi')->assertRedirect('/login');
        $this->get('/risiko/penilaian')->assertRedirect('/login');
        $this->get('/risiko/pengendalian')->assertRedirect('/login');
    }

    public function test_risk_crud_with_validation_and_relationships(): void
    {
        $user = $this->createUser('user', 'risiko', 'risk.manager@aisyala.local', 'Risk Manager');
        $asset = $this->createAsset();
        $service = $this->createService();

        $this->actingAs($user)
            ->post('/risiko', [
                'risk_code' => 'R-101',
                'risk_name' => 'Gangguan Jaringan',
                'cause' => 'Kabel putus',
                'impact' => 'Layanan komunikasi terganggu',
                'likelihood' => 'Sedang',
                'risk_level' => 'Menengah',
                'control_measures' => 'Monitoring jaringan',
                'mitigation_plan' => 'Menambah redundansi',
                'risk_status' => 'Aktif',
                'monitoring' => 'Pantau setiap hari',
                'evaluation' => 'Evaluasi bulanan',
                'assets' => [$asset->id],
                'services' => [$service->id],
            ])
            ->assertRedirect('/risiko');

        $risk = Risk::first();
        $this->assertNotNull($risk);
        $this->assertEquals('R-101', $risk->risk_code);
        $this->assertTrue($risk->assets()->whereKey($asset->id)->exists());
        $this->assertTrue($risk->services()->whereKey($service->id)->exists());

        $this->actingAs($user)
            ->put('/risiko/' . $risk->id, [
                'risk_code' => 'R-101',
                'risk_name' => 'Gangguan Jaringan Update',
                'cause' => 'Kabel putus dan korsleting',
                'impact' => 'Layanan komunikasi terganggu berat',
                'likelihood' => 'Tinggi',
                'risk_level' => 'Tinggi',
                'control_measures' => 'Monitoring jaringan dan pengecekan kabel',
                'mitigation_plan' => 'Menambah redundansi jalur dan backup perangkat',
                'risk_status' => 'Dalam Pemantauan',
                'monitoring' => 'Pantau setiap shift',
                'evaluation' => 'Evaluasi harian',
                'assets' => [$asset->id],
                'services' => [$service->id],
            ])
            ->assertRedirect('/risiko/' . $risk->id);

        $risk->refresh();
        $this->assertEquals('Gangguan Jaringan Update', $risk->risk_name);

        $this->actingAs($user)
            ->delete('/risiko/' . $risk->id)
            ->assertRedirect('/risiko');

        $this->assertDatabaseMissing('risks', ['id' => $risk->id]);
    }

    public function test_risk_identification_assessment_and_control_pages_work(): void
    {
        $user = $this->createUser('user', 'risiko', 'risk.admin2@aisyala.local', 'Risk Manager 2');
        $asset = $this->createAsset(['asset_code' => 'AST-200', 'asset_name' => 'Server']);
        $service = $this->createService(['service_code' => 'SRV-200', 'service_name' => 'Layanan Data']);
        $risk = $this->createRisk(['risk_code' => 'R-200', 'risk_name' => 'Kehilangan Data']);
        $risk->assets()->sync([$asset->id]);
        $risk->services()->sync([$service->id]);

        $this->actingAs($user)->get('/risiko/identifikasi')->assertOk();
        $this->actingAs($user)->put('/risiko/identifikasi/' . $risk->id, [
            'cause' => 'Server crash',
            'impact' => 'Data hilang sementara',
            'monitoring' => 'Pengawasan rutin',
            'assets' => [$asset->id],
            'services' => [$service->id],
        ])->assertRedirect('/risiko/identifikasi');

        $this->actingAs($user)->get('/risiko/penilaian')->assertOk();
        $this->actingAs($user)->put('/risiko/penilaian/' . $risk->id, [
            'likelihood' => 'Rendah',
            'risk_level' => 'Rendah',
        ])->assertRedirect('/risiko/penilaian');

        $this->actingAs($user)->get('/risiko/pengendalian')->assertOk();
        $this->actingAs($user)->put('/risiko/pengendalian/' . $risk->id, [
            'control_measures' => 'Pencadangan rutin',
            'mitigation_plan' => 'Backup otomatis',
            'risk_status' => 'Ditutup',
        ])->assertRedirect('/risiko/pengendalian');

        $risk->refresh();
        $this->assertEquals('Pencadangan rutin', $risk->control_measures);
        $this->assertEquals('Ditutup', $risk->risk_status);
    }
}
