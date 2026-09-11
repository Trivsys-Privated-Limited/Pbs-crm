<?php

namespace App\Imports;

use App\Models\support;
use App\Models\ExpiredSupport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class NewSupportImport implements ToCollection, WithHeadingRow
{
    protected $expiry_date;
    protected $assigned_to;
    protected $limit;
    protected $defaultData;

    // Constructor mein default missing columns ka data (jaise agent name) receive hoga
    public function __construct($expiry_date, $assigned_to, $limit = null, $defaultData = [])
    {
        $this->expiry_date = $expiry_date;
        $this->assigned_to = $assigned_to;
        $this->limit = $limit;
        $this->defaultData = $defaultData;
    }

    public function headingRow(): int
    {
        return 1; // Nayi sheet mein heading 1st row par hai
    }

   /* public function collection(Collection $rows)
    {
        $importedCount = 0; 

        foreach ($rows as $row) {
            if ($this->limit !== null && $importedCount >= (int)$this->limit) {
                break;
            }

            $rowClean = array_change_key_case($row->toArray(), CASE_LOWER);
            
            // Agar status sheet mein nahi hai toh default jo form se aya hai wo use karein
            $statusRaw = $rowClean['status'] ?? $this->defaultData['status'] ?? '';
            $status    = strtolower(trim((string)$statusRaw));

            if ($status !== 'sale') {
                continue;
            }

            // Name aur Phone number
            $customerName = $rowClean['customer_name'] ?? $rowClean['name'] ?? 'No Name';
            $cleanNumber  = preg_replace('/[^0-9]/', '', (string)($rowClean['customer_phone'] ?? $rowClean['number'] ?? ''));
            
            // Agar Agent sheet mein nahi hai toh default jo form mein likha gaya wo use hoga
            $agentName = $rowClean['agent_name'] ?? $rowClean['agent'] ?? $this->defaultData['agent_name'] ?? 'N/A';

            if (empty($cleanNumber)) {
                continue;
            }

            $existsInSupport = support::where('number', $cleanNumber)->exists();
            $existsInExpired = ExpiredSupport::where('number', $cleanNumber)->exists();

            if ($existsInSupport || $existsInExpired) {
                continue;
            }

            support::create([
                'name'             => trim((string)$customerName),
                'number'           => $cleanNumber,
                'agent_name'       => $agentName,
                'expiry_date'      => $this->expiry_date,
                'show_status'      => 'Sale',
                'assigned_by_name' => Auth::user()->name,
                'assigned_by_role' => Auth::user()->role,
                'assigned_date'    => now()->toDateString(),
                'assigned_to'      => $this->assigned_to,
            ]);

            $importedCount++;
        }
    }
        */
    public function collection(Collection $rows)
    {
        $importedCount = 0; 

        foreach ($rows as $row) {
            if ($this->limit !== null && $importedCount >= (int)$this->limit) {
                break;
            }

            // Headers normalize ho jayenge jaise: customer_registration_date, customer_name, customer_phone
            $rowClean = array_change_key_case($row->toArray(), CASE_LOWER);
            
            // Default inputs se data receive karna
            $statusRaw = $rowClean['status'] ?? $this->defaultData['status'] ?? 'sale';
            $status    = strtolower(trim((string)$statusRaw));

            if ($status !== 'sale') {
                continue;
            }

            // Column mapping according to new template
            $customerName = $rowClean['customer_name'] ?? 'No Name';
            $cleanNumber  = preg_replace('/[^0-9]/', '', (string)($rowClean['customer_phone'] ?? ''));
            
            // Agent Name from input field
            $agentName = $this->defaultData['agent_name'] ?? 'N/A';

            if (empty($cleanNumber)) {
                continue;
            }

            // Duplication Check in both tables
            $existsInSupport = support::where('number', $cleanNumber)->exists();
            $existsInExpired = ExpiredSupport::where('number', $cleanNumber)->exists();

            if ($existsInSupport || $existsInExpired) {
                continue; // Agar exist karta hai toh skip karein
            }

            // New Insert
            support::create([
                'name'             => trim((string)$customerName),
                'number'           => $cleanNumber,
                'agent_name'       => $agentName,
                'expiry_date'      => $this->expiry_date,
                'show_status'      => 'Sale',
                'assigned_by_name' => Auth::user()->name,
                'assigned_by_role' => Auth::user()->role,
                'assigned_date'    => now()->toDateString(),
                'assigned_to'      => $this->assigned_to,
            ]);

            $importedCount++;
        }
    }
}