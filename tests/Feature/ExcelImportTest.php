<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Risk;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class ExcelImportTest extends TestCase
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

    protected function createExcelFile(array $rows, string $fileName = 'data.xlsx'): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($rows as $rowIndex => $row) {
            foreach ($row as $columnIndex => $value) {
                $sheet->setCellValueByColumnAndRow($columnIndex + 1, $rowIndex + 1, $value);
            }
        }

        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $fileName;
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        return $path;
    }

    public function test_admin_can_access_import_page_and_other_users_are_denied(): void
    {
        $admin = $this->createUser('admin', null, 'admin.import@aisyala.local', 'Admin Import');
        $assetUser = $this->createUser('user', 'aset', 'asset.import@aisyala.local', 'User Aset');
        $riskUser = $this->createUser('user', 'risiko', 'risk.import@aisyala.local', 'User Risiko');
        $serviceUser = $this->createUser('user', 'layanan', 'service.import@aisyala.local', 'User Layanan');

        $this->actingAs($admin)->get('/admin/import')->assertOk();
        $this->actingAs($assetUser)->get('/admin/import')->assertForbidden();
        $this->actingAs($riskUser)->get('/admin/import')->assertForbidden();
        $this->actingAs($serviceUser)->get('/admin/import')->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_for_import_pages(): void
    {
        $this->get('/admin/import')->assertRedirect('/login');
    }

    public function test_admin_can_import_asset_risk_and_service_data_from_excel_and_db_receives_data(): void
    {
        $admin = $this->createUser('admin', null, 'admin.import.success@aisyala.local', 'Admin Import Success');

        $assetRows = [
            ['asset_code', 'asset_name', 'asset_classification', 'acquisition_date', 'acquisition_value', 'vendor', 'condition_status', 'location', 'responsible_person', 'usage_status', 'useful_life_years', 'data_storage_information', 'lifecycle_status', 'final_handling'],
            ['AST-100', 'Server Utama', 'Perangkat Keras', '2024-01-15', '15000000', 'Vendor A', 'Baik', 'Jakarta', 'Budi', 'Digunakan', '5', 'Storage A', 'Aktif', 'Dipelihara'],
        ];

        $assetFile = $this->createExcelFile($assetRows, 'asset-import.xlsx');
        $this->actingAs($admin)
            ->post('/admin/import', [
                'type' => 'asset',
                'excel_file' => new UploadedFile($assetFile, 'asset-import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true),
            ])
            ->assertRedirect('/admin/import');

        $this->assertDatabaseHas('assets', ['asset_code' => 'AST-100', 'asset_name' => 'Server Utama']);

        $riskRows = [
            ['risk_code', 'risk_name', 'cause', 'impact', 'likelihood', 'risk_level', 'control_measures', 'mitigation_plan', 'risk_status', 'monitoring', 'evaluation'],
            ['RIS-100', 'Gangguan Jaringan', 'Bandwith overload', 'Layanan terganggu', 'Tinggi', 'Tinggi', 'Monitoring bandwidth', 'Upgrade jaringan', 'Aktif', 'Pantau 24 jam', 'Evaluasi bulanan'],
        ];

        $riskFile = $this->createExcelFile($riskRows, 'risk-import.xlsx');
        $this->actingAs($admin)
            ->post('/admin/import', [
                'type' => 'risk',
                'excel_file' => new UploadedFile($riskFile, 'risk-import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true),
            ])
            ->assertRedirect('/admin/import');

        $this->assertDatabaseHas('risks', ['risk_code' => 'RIS-100', 'risk_name' => 'Gangguan Jaringan']);

        $serviceRows = [
            ['service_code', 'service_name', 'service_description', 'service_type', 'service_owner', 'service_status', 'supporting_information', 'monitoring', 'evaluation'],
            ['SRV-100', 'Layanan Email', 'Email internal', 'Internal', 'Tim IT', 'Aktif', 'Dokumen pendukung', 'Pantau rutin', 'Evaluasi bulanan'],
        ];

        $serviceFile = $this->createExcelFile($serviceRows, 'service-import.xlsx');
        $this->actingAs($admin)
            ->post('/admin/import', [
                'type' => 'service',
                'excel_file' => new UploadedFile($serviceFile, 'service-import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true),
            ])
            ->assertRedirect('/admin/import');

        $this->assertDatabaseHas('services', ['service_code' => 'SRV-100', 'service_name' => 'Layanan Email']);
    }

    public function test_invalid_and_duplicate_imports_are_rejected(): void
    {
        $admin = $this->createUser('admin', null, 'admin.import.invalid@aisyala.local', 'Admin Invalid');

        $invalidFile = $this->createExcelFile([
            ['asset_code', 'asset_name'],
            ['AST-200', 'Server Valid'],
            ['', 'Server Kosong'],
        ], 'invalid-import.xlsx');

        $this->actingAs($admin)
            ->post('/admin/import', [
                'type' => 'asset',
                'excel_file' => new UploadedFile($invalidFile, 'invalid-import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true),
            ])
            ->assertSessionHas('error');

        $duplicateRows = [
            ['asset_code', 'asset_name', 'asset_classification', 'acquisition_date', 'acquisition_value', 'vendor', 'condition_status', 'location', 'responsible_person', 'usage_status', 'useful_life_years', 'data_storage_information', 'lifecycle_status', 'final_handling'],
            ['AST-200', 'Server Duplikat', 'Perangkat Keras', '2024-01-15', '2000000', 'Vendor A', 'Baik', 'Jakarta', 'Budi', 'Digunakan', '3', 'Storage A', 'Aktif', 'Dipelihara'],
        ];

        $duplicateFile = $this->createExcelFile($duplicateRows, 'duplicate-import.xlsx');
        $this->actingAs($admin)
            ->post('/admin/import', [
                'type' => 'asset',
                'excel_file' => new UploadedFile($duplicateFile, 'duplicate-import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true),
            ])
            ->assertRedirect('/admin/import');

        $this->assertDatabaseCount('assets', 1);
    }
}
