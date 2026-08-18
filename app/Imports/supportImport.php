<?php

namespace App\Imports;

use App\Models\support;
use App\Models\ExpiredSupport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class supportImport implements ToCollection, WithHeadingRow
{
    protected $expiry_date;
    protected $assigned_to;
    protected $limit;

    // Constructor mein expiry_date, assigned_to, aur custom limit receive ho rahi hai
    public function __construct($expiry_date, $assigned_to, $limit = null)
    {
        $this->expiry_date = $expiry_date;
        $this->assigned_to = $assigned_to;
        $this->limit = $limit;
    }

    public function headingRow(): int
    {
        return 2; // Kyunki aapki sheet ki heading 2nd row par hai
    }

    public function collection(Collection $rows)
    {
        $importedCount = 0; // Track karne ke liye ke kitna data successfully import hua

        foreach ($rows as $row) {
            // Agar user ne custom limit enter ki hai aur limit poori ho gayi hai, toh loop wahin rok dein
            if ($this->limit !== null && $importedCount >= (int)$this->limit) {
                break;
            }

            $rowClean = array_change_key_case($row->toArray(), CASE_LOWER);
            $statusRaw = $rowClean['status'] ?? '';
            $status    = strtolower(trim((string)$statusRaw));

            // Agar status 'sale' nahi hai, toh is row ko skip kar dein
            if ($status !== 'sale') {
                continue;
            }

            $customerName = $rowClean['customer_name'] ?? $rowClean['name'] ?? 'No Name';
            $cleanNumber  = preg_replace('/[^0-9]/', '', (string)($rowClean['customer_phone'] ?? $rowClean['number'] ?? ''));
            $agentName    = $rowClean['agent_name'] ?? $rowClean['agent'] ?? 'N/A';

            // Agar number empty hai ya pehle se support / expired table mein majood hai, toh skip karein (Duplicates avoid)
            if (empty($cleanNumber)) {
                continue;
            }

            $existsInSupport = support::where('number', $cleanNumber)->exists();
            $existsInExpired = ExpiredSupport::where('number', $cleanNumber)->exists();

            if ($existsInSupport || $existsInExpired) {
                continue;
            }

            // Naya record support table mein store karein
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

            $importedCount++; // Sirf valid aur non-duplicate row par count barhega
        }
    }
}