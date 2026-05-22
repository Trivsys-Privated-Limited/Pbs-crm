<?php
namespace App\Imports;

use App\Models\support;
use Maatwebsite\Excel\Concerns\ToModel;

class supportImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $cleanNumber = preg_replace('/[^0-9]/', '', $row[1]);

        if (Support::where('number', $cleanNumber)->exists()) {
            return null;
        }

        return new support([
            'name'        => $row[0],
            'number'      => $cleanNumber,
            'show_status' => $row[2],
        ]);
    }
}
