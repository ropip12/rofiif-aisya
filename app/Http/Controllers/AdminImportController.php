<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Risk;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AdminImportController extends Controller
{
    public function index(): View
    {
        return view('admin.import.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:asset,risk,service'],
            'excel_file' => ['required', 'file', 'mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv', 'max:20480'],
        ]);

        $file = $request->file('excel_file');

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);
        } catch (\Throwable $e) {
            return redirect()->route('admin.import.index')->with('error', 'File Excel tidak valid atau tidak dapat dibaca.');
        }

        if (empty($rows)) {
            return redirect()->route('admin.import.index')->with('error', 'File Excel kosong.');
        }

        try {
            $this->validateRows($validated['type'], $rows);
            $records = $this->extractRecords($validated['type'], $rows);
        } catch (\Throwable $e) {
            return redirect()->route('admin.import.index')->with('error', $e->getMessage());
        }

        $modelClass = match ($validated['type']) {
            'asset' => Asset::class,
            'risk' => Risk::class,
            'service' => Service::class,
        };

        $uniqueKey = match ($validated['type']) {
            'asset' => 'asset_code',
            'risk' => 'risk_code',
            'service' => 'service_code',
        };

        $seen = [];
        foreach ($records as $record) {
            $code = trim((string) ($record[$uniqueKey] ?? ''));

            if ($code === '') {
                return redirect()->route('admin.import.index')->with('error', 'Kode data tidak boleh kosong.');
            }

            if (isset($seen[$code])) {
                return redirect()->route('admin.import.index')->with('error', 'Terdapat kode duplikat dalam file Excel yang sama.');
            }

            if ($modelClass::query()->where($uniqueKey, $code)->exists()) {
                return redirect()->route('admin.import.index')->with('error', 'Data dengan kode ' . $code . ' sudah ada di database.');
            }

            $seen[$code] = true;
        }

        try {
            DB::transaction(function () use ($records, $modelClass) {
                foreach ($records as $record) {
                    $modelClass::create($record);
                }
            });
        } catch (\Throwable $e) {
            return redirect()->route('admin.import.index')->with('error', 'Import gagal: ' . $e->getMessage());
        }

        $labels = [
            'asset' => 'aset',
            'risk' => 'risiko',
            'service' => 'layanan',
        ];

        return redirect()->route('admin.import.index')->with('success', 'Data ' . $labels[$validated['type']] . ' berhasil diimport.');
    }

    protected function validateRows(string $type, array $rows): void
    {
        $header = $this->normalizeHeader($rows[0] ?? []);
        $required = match ($type) {
            'asset' => ['asset_code', 'asset_name'],
            'risk' => ['risk_code', 'risk_name'],
            'service' => ['service_code', 'service_name'],
        };

        if (empty($header)) {
            throw new \RuntimeException('Header Excel kosong.');
        }

        $dataRows = array_slice($rows, 1);

        foreach ($dataRows as $rowIndex => $row) {
            $values = $this->normalizeRow($header, $row);
            $isEmpty = true;

            foreach ($values as $value) {
                if (trim((string) $value) !== '') {
                    $isEmpty = false;
                    break;
                }
            }

            if ($isEmpty) {
                continue;
            }

            foreach ($required as $field) {
                if (! array_key_exists($field, $values) || trim((string) ($values[$field] ?? '')) === '') {
                    throw new \RuntimeException('Kolom ' . $field . ' pada baris ' . ($rowIndex + 2) . ' tidak boleh kosong.');
                }
            }
        }
    }

    protected function extractRecords(string $type, array $rows): array
    {
        $header = $this->normalizeHeader($rows[0] ?? []);
        $records = [];

        foreach (array_slice($rows, 1) as $row) {
            $values = $this->normalizeRow($header, $row);
            $isEmpty = true;

            foreach ($values as $value) {
                if (trim((string) $value) !== '') {
                    $isEmpty = false;
                    break;
                }
            }

            if ($isEmpty) {
                continue;
            }

            $records[] = match ($type) {
                'asset' => [
                    'asset_code' => trim((string) ($values['asset_code'] ?? '')),
                    'asset_name' => trim((string) ($values['asset_name'] ?? '')),
                    'asset_classification' => trim((string) ($values['asset_classification'] ?? '')),
                    'acquisition_date' => $this->cleanDate($values['acquisition_date'] ?? null),
                    'acquisition_value' => $this->cleanNumeric($values['acquisition_value'] ?? null),
                    'vendor' => trim((string) ($values['vendor'] ?? '')),
                    'condition_status' => trim((string) ($values['condition_status'] ?? '')),
                    'location' => trim((string) ($values['location'] ?? '')),
                    'responsible_person' => trim((string) ($values['responsible_person'] ?? '')),
                    'usage_status' => trim((string) ($values['usage_status'] ?? '')),
                    'useful_life_years' => $this->cleanInteger($values['useful_life_years'] ?? null),
                    'data_storage_information' => trim((string) ($values['data_storage_information'] ?? '')),
                    'lifecycle_status' => trim((string) ($values['lifecycle_status'] ?? '')),
                    'final_handling' => trim((string) ($values['final_handling'] ?? '')),
                ],
                'risk' => [
                    'risk_code' => trim((string) ($values['risk_code'] ?? '')),
                    'risk_name' => trim((string) ($values['risk_name'] ?? '')),
                    'cause' => trim((string) ($values['cause'] ?? '')),
                    'impact' => trim((string) ($values['impact'] ?? '')),
                    'likelihood' => trim((string) ($values['likelihood'] ?? '')),
                    'risk_level' => trim((string) ($values['risk_level'] ?? '')),
                    'control_measures' => trim((string) ($values['control_measures'] ?? '')),
                    'mitigation_plan' => trim((string) ($values['mitigation_plan'] ?? '')),
                    'risk_status' => trim((string) ($values['risk_status'] ?? '')),
                    'monitoring' => trim((string) ($values['monitoring'] ?? '')),
                    'evaluation' => trim((string) ($values['evaluation'] ?? '')),
                ],
                'service' => [
                    'service_code' => trim((string) ($values['service_code'] ?? '')),
                    'service_name' => trim((string) ($values['service_name'] ?? '')),
                    'service_description' => trim((string) ($values['service_description'] ?? '')),
                    'service_type' => trim((string) ($values['service_type'] ?? '')),
                    'service_owner' => trim((string) ($values['service_owner'] ?? '')),
                    'service_status' => trim((string) ($values['service_status'] ?? '')),
                    'supporting_information' => trim((string) ($values['supporting_information'] ?? '')),
                    'monitoring' => trim((string) ($values['monitoring'] ?? '')),
                    'evaluation' => trim((string) ($values['evaluation'] ?? '')),
                ],
            };
        }

        return $records;
    }

    protected function normalizeHeader(array $header): array
    {
        $normalized = [];

        foreach ($header as $key => $value) {
            $label = strtolower(trim((string) $value));
            if ($label !== '') {
                $normalized[$key] = $label;
            }
        }

        return $normalized;
    }

    protected function normalizeRow(array $header, array $row): array
    {
        $values = [];

        foreach ($header as $index => $label) {
            $values[$label] = $row[$index] ?? null;
        }

        return $values;
    }

    protected function cleanDate(mixed $value): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        $date = date('Y-m-d', strtotime((string) $value));

        return $date !== false && $date !== '1970-01-01' ? $date : null;
    }

    protected function cleanNumeric(mixed $value): ?float
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        return (float) str_replace(['.', ','], ['', '.'], (string) $value);
    }

    protected function cleanInteger(mixed $value): ?int
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        return (int) $value;
    }
}
