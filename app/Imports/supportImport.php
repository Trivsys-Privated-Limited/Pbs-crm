<?php

namespace App\Imports;

use App\Models\support;
use App\Models\ExpiredSupport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class supportImport implements ToModel, WithHeadingRow
{
    protected $expiry_date;
    protected $assigned_to;

    // Constructor me assigned_to pass kiya
    public function __construct($expiry_date, $assigned_to)
    {
        $this->expiry_date = $expiry_date;
        $this->assigned_to = $assigned_to;
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $rowClean = array_change_key_case($row, CASE_LOWER);
        $statusRaw = $rowClean['status'] ?? '';
        $status    = strtolower(trim((string)$statusRaw));

        if ($status !== 'sale') return null;

        $customerName = $rowClean['customer_name'] ?? $rowClean['name'] ?? 'No Name';
        $cleanNumber = preg_replace('/[^0-9]/', '', (string)($rowClean['customer_phone'] ?? $rowClean['number'] ?? ''));
        $agentName = $rowClean['agent_name'] ?? $rowClean['agent'] ?? 'N/A';

        $existsInSupport = support::where('number', $cleanNumber)->exists();
        $existsInExpired = ExpiredSupport::where('number', $cleanNumber)->exists();

        if (empty($cleanNumber) || $existsInSupport || $existsInExpired) return null;

        return new support([
            'name'             => trim((string)$customerName),
            'number'           => $cleanNumber,
            'agent_name'       => $agentName,
            'expiry_date'      => $this->expiry_date,
            'show_status'      => 'Sale',
            'assigned_by_name' => Auth::user()->name,
            'assigned_by_role' => Auth::user()->role,
            'assigned_date'    => now()->toDateString(),
            'assigned_to'      => $this->assigned_to, // Yahan assigned user save hoga
        ]);
    }
}