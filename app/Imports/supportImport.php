<?php

namespace App\Imports;

use App\Models\support;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class supportImport implements ToModel, WithHeadingRow
{
    /**
     * Header Row 2 par hai (Jaise screenshot mein Row 2 par CUSTOMER NAME, CUSTOMER PHONE, STATUS hain)
     */
    public function headingRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $rowClean = array_change_key_case($row, CASE_LOWER);

        // Status check - Sirf 'sale' walo ko store karna hai
        $statusRaw = $rowClean['status'] ?? '';
        $status    = strtolower(trim((string)$statusRaw));

        if ($status !== 'sale') {
            return null;
        }

        // Customer Name (CUSTOMER NAME column)
        $customerName = $rowClean['customer_name'] 
            ?? $rowClean['customer name'] 
            ?? $rowClean['name'] 
            ?? 'No Name';

        // Customer Phone (CUSTOMER PHONE column)
        $rawNumber = $rowClean['customer_phone'] 
            ?? $rowClean['customer phone'] 
            ?? $rowClean['phone'] 
            ?? $rowClean['customer_number'] 
            ?? $rowClean['number'] 
            ?? '';

        $cleanNumber = preg_replace('/[^0-9]/', '', (string)$rawNumber);

        if (empty($cleanNumber)) {
            return null;
        }

        // Duplicate Check (Phone number pehle se exist na karta ho)
        if (support::where('number', $cleanNumber)->exists()) {
            return null;
        }

        // Store only Name, Phone, and show_status = 'Sale' (Baaki saare extra columns skip)
        return new support([
            'name'        => trim((string)$customerName),
            'number'      => $cleanNumber,
            'show_status' => 'Sale',
        ]);
    }
}