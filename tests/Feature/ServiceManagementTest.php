<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Risk;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ServiceManagementTest extends TestCase
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
            'asset_code' => $overrides['asset_code'] ?? 'AST-SRV-001',
            'asset_name' => $overrides['asset_name'] ?? 'Server Layanan',
            'asset_classification' => $overrides['asset_classification'] ?? 'Perangkat Keras',
            'acquisition_date' => $overrides['acquisition_date'] ?? '2024-01-15',
            'acquisition_value' => $overrides['acquisition_value'] ?? 12000000,
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
            'risk_code' => $overrides['risk_code'] ?? 'RIS-001',
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
            'service_code' => $overrides['service_code'] ?? 'SRV-100',
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

    public function test_service_pages_are_authorized_and_accessible(): void
    {
        $admin = $this->createUser('admin', null, 'admin.service@aisyala.local', 'Admin Layanan');
        $serviceUser = $this->createUser('user', 'layanan', 'service.user@aisyala.local', 'User Layanan');
        $assetUser = $this->createUser('user', 'aset', 'asset.user@aisyala.local', 'User Aset');
        $riskUser = $this->createUser('user', 'risiko', 'risk.user@aisyala.local', 'User Risiko');

        $this->actingAs($admin)->get('/layanan')->assertOk();
        $this->actingAs($admin)->get('/layanan/create')->assertOk();
        $this->actingAs($admin)->get('/layanan/pengelolaan')->assertOk();

        $this->actingAs($serviceUser)->get('/layanan')->assertOk();
        $this->actingAs($serviceUser)->get('/layanan/create')->assertOk();
        $this->actingAs($serviceUser)->get('/layanan/pengelolaan')->assertOk();

        $this->actingAs($assetUser)->get('/layanan')->assertForbidden();
        $this->actingAs($assetUser)->get('/layanan/pengelolaan')->assertForbidden();

        $this->actingAs($riskUser)->get('/layanan')->assertForbidden();
        $this->actingAs($riskUser)->get('/layanan/pengelolaan')->assertForbidden();
    }

    public function test_service_pages_redirect_guests_to_login(): void
    {
        $this->get('/layanan')->assertRedirect('/login');
        $this->get('/layanan/create')->assertRedirect('/login');
        $this->get('/layanan/pengelolaan')->assertRedirect('/login');
    }

    public function test_service_crud_create_validation_update_delete_search_filter_and_pagination(): void
    {
        $admin = $this->createUser('admin', null, 'admin.service.crud@aisyala.local', 'Admin Crud');
        $asset = $this->createAsset(['asset_code' => 'AST-SRV-010']);
        $risk = $this->createRisk(['risk_code' => 'RIS-010']);

        $this->actingAs($admin)
            ->post('/layanan', [
                'service_code' => 'SRV-201',
                'service_name' => 'Layanan Email Bisnis',
                'service_description' => 'Layanan email bisnis untuk internal',
                'service_type' => 'Internal',
                'service_owner' => 'Tim IT',
                'service_status' => 'Aktif',
                'supporting_information' => 'Dukungan 24 jam',
                'monitoring' => 'Pemantauan harian',
                'evaluation' => 'Evaluasi bulanan',
                'assets' => [$asset->id],
                'risks' => [$risk->id],
            ])
            ->assertRedirect('/layanan');

        $service = Service::first();
        $this->assertNotNull($service);
        $this->assertEquals('Layanan Email Bisnis', $service->service_name);
        $this->assertTrue($service->assets()->whereKey($asset->id)->exists());
        $this->assertTrue($service->risks()->whereKey($risk->id)->exists());

        $this->actingAs($admin)
            ->post('/layanan', [
                'service_code' => '',
                'service_name' => '',
                'service_description' => 'x',
                'service_type' => '',
                'service_owner' => '',
                'service_status' => '',
            ])
            ->assertSessionHasErrors(['service_code', 'service_name', 'service_type', 'service_status']);

        $this->actingAs($admin)
            ->get('/layanan?search=Email')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/layanan?service_type=Internal&service_status=Aktif')
            ->assertOk();

        $this->actingAs($admin)
            ->put('/layanan/' . $service->id, [
                'service_code' => 'SRV-201',
                'service_name' => 'Layanan Email Enterprise',
                'service_description' => 'Deskripsi updated',
                'service_type' => 'Internal',
                'service_owner' => 'Tim Infrastruktur',
                'service_status' => 'Dalam Pemantauan',
                'supporting_information' => 'Informasi pendukung baru',
                'monitoring' => 'Pemantauan real-time',
                'evaluation' => 'Evaluasi mingguan',
                'assets' => [$asset->id],
                'risks' => [$risk->id],
            ])
            ->assertRedirect('/layanan/' . $service->id);

        $service->refresh();
        $this->assertEquals('Layanan Email Enterprise', $service->service_name);
        $this->assertEquals('Tim Infrastruktur', $service->service_owner);

        $this->actingAs($admin)->get('/layanan?page=2')->assertOk();

        for ($i = 0; $i < 12; $i++) {
            $this->createService([
                'service_code' => 'SRV-' . (500 + $i),
                'service_name' => 'Layanan Paginasi ' . $i,
                'service_type' => 'Eksternal',
                'service_status' => 'Aktif',
            ]);
        }

        $this->actingAs($admin)
            ->get('/layanan?page=2')
            ->assertOk();

        $this->actingAs($admin)
            ->delete('/layanan/' . $service->id)
            ->assertRedirect('/layanan');

        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_service_management_authorization_data_search_filter_and_update(): void
    {
        $admin = $this->createUser('admin', null, 'admin.service.management@aisyala.local', 'Admin Kelola');
        $serviceUser = $this->createUser('user', 'layanan', 'service.manage@aisyala.local', 'User Kelola Layanan');
        $assetUser = $this->createUser('user', 'aset', 'aset.manage@aisyala.local', 'User Aset');

        $this->createService([
            'service_code' => 'SRV-300',
            'service_name' => 'Layanan Operasional',
            'service_type' => 'Internal',
            'service_status' => 'Aktif',
            'service_owner' => 'Tim Operasional',
            'supporting_information' => 'Informasi awal',
            'monitoring' => 'Pemantauan awal',
            'evaluation' => 'Evaluasi awal',
        ]);

        $this->actingAs($admin)->get('/layanan/pengelolaan')->assertOk();
        $this->actingAs($serviceUser)->get('/layanan/pengelolaan')->assertOk();
        $this->actingAs($assetUser)->get('/layanan/pengelolaan')->assertForbidden();

        $service = Service::first();

        $this->actingAs($serviceUser)
            ->get('/layanan/pengelolaan?search=Operasional&service_type=Internal&service_status=Aktif')
            ->assertOk();

        $this->actingAs($serviceUser)
            ->put('/layanan/pengelolaan/' . $service->id, [
                'service_owner' => 'Tim Pengelola Baru',
                'service_status' => 'Dalam Pemantauan',
                'supporting_information' => 'Informasi terbaru',
                'monitoring' => 'Pemantauan setengah bulanan',
                'evaluation' => 'Evaluasi triwulan',
            ])
            ->assertRedirect('/layanan/pengelolaan');

        $service->refresh();
        $this->assertEquals('Tim Pengelola Baru', $service->service_owner);
        $this->assertEquals('Dalam Pemantauan', $service->service_status);

        $this->actingAs($serviceUser)
            ->put('/layanan/pengelolaan/' . $service->id, [
                'service_owner' => '',
                'service_status' => '',
            ])
            ->assertSessionHasErrors(['service_owner', 'service_status']);
    }

    public function test_service_relationships_are_safe_when_empty_or_populated(): void
    {
        $admin = $this->createUser('admin', null, 'admin.service.relationship@aisyala.local', 'Admin Relasi');

        $serviceWithoutRelations = $this->createService([
            'service_code' => 'SRV-400',
            'service_name' => 'Layanan Tanpa Relasi',
        ]);

        $assetOne = $this->createAsset(['asset_code' => 'AST-REL-1', 'asset_name' => 'Server Relasi 1']);
        $assetTwo = $this->createAsset(['asset_code' => 'AST-REL-2', 'asset_name' => 'Server Relasi 2']);
        $riskOne = $this->createRisk(['risk_code' => 'RIS-REL-1', 'risk_name' => 'Risiko A']);
        $riskTwo = $this->createRisk(['risk_code' => 'RIS-REL-2', 'risk_name' => 'Risiko B']);

        $this->actingAs($admin)
            ->get('/layanan/' . $serviceWithoutRelations->id)
            ->assertOk();

        $serviceWithRelations = $this->createService([
            'service_code' => 'SRV-401',
            'service_name' => 'Layanan Dengan Relasi',
        ]);
        $serviceWithRelations->assets()->sync([$assetOne->id, $assetTwo->id]);
        $serviceWithRelations->risks()->sync([$riskOne->id, $riskTwo->id]);

        $this->actingAs($admin)
            ->get('/layanan/' . $serviceWithRelations->id)
            ->assertOk()
            ->assertSeeText('Server Relasi 1')
            ->assertSeeText('Risiko A');
    }
}
