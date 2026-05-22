<?php
namespace App\Imports;

use App\Models\attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class AttendanceImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['date'])) {
            return null;
        }

        if (is_numeric($row['date'])) {
            $date = Carbon::instance(ExcelDate::excelToDateTimeObject($row['date']))->format('Y-m-d');
        } else {
            $date = Carbon::parse($row['date'])->format('Y-m-d');
        }

        $check_in = ! empty($row['check_in'])
            ? (is_numeric($row['check_in'])
                ? Carbon::instance(ExcelDate::excelToDateTimeObject($row['check_in']))->format('H:i:s')
                : Carbon::parse($row['check_in'])->format('H:i:s'))
            : null;

        $check_out = ! empty($row['check_out'])
            ? (is_numeric($row['check_out'])
                ? Carbon::instance(ExcelDate::excelToDateTimeObject($row['check_out']))->format('H:i:s')
                : Carbon::parse($row['check_out'])->format('H:i:s'))
            : null;

        return new attendance([
            'date'          => $date,
            'check_in'      => $check_in,
            'check_out'     => $check_out ?? '06:00:00',
            'employee_name' => $row['employee'] ?? null,
            'status'        => $row['status'] ?? null,
        ]);
    }
}
