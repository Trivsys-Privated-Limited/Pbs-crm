<?php

namespace App\Imports;

use App\Models\customer; // Apne model ka naam check kar lein agar small 'c' se hai toh theek hai
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class AgentSaleImport implements ToModel, WithHeadingRow
{
    protected $agent_id;
    protected $agent_name;

    public function __construct($agent_id)
    {
        $this->agent_id = $agent_id;
        $agent = User::find($agent_id);
        $this->agent_name = $agent ? $agent->name : null;
    }

    public function model(array $row)
    {
        // Skip empty rows (Agar dono name aur number nahi hain toh row skip ho jayegi)
      //  if (empty($row['customer_name']) && empty($row['customer_number'])) {
        //    return null;
        //}

        // ---- NEW LOGIC START ----
        // By default hum us agent ki ID/Name rakh rahe hain jiske page se hum import kar rahe hain
        $final_agent_id = $this->agent_id;
        $final_agent_name = $this->agent_name;

        // Check karte hain ke kya excel sheet mein 'agent_name' ka column hai aur wo empty toh nahi?
        if (!empty($row['agent_name'])) {
            // Excel sheet waly agent ko DB mein find karein
            $sheetAgent = User::where('name', $row['agent_name'])->first();
            
            // Agar agent DB mein mil gaya, toh final IDs update kar dein
            if ($sheetAgent) {
                $final_agent_id = $sheetAgent->id;
                $final_agent_name = $sheetAgent->name;
            }
        }
        
        // ---- NEW LOGIC END ----

        return new customer([
            'customer_name'   => $row['customer_name'] ?? 'No Name',
            'customer_email'  => $row['customer_email'] ?? 'No Email',
            'customer_number' => $row['customer_number'] ?? null,
            'price'           => $row['price'] ?? 0,
            'remarks'         => $row['remarks'] ?? null,
            'status'          => 'sale', 
            'regitr_date'     => isset($row['registration_date']) ? Carbon::parse($row['registration_date'])->format('Y-m-d') : now()->format('Y-m-d'),
            
            // Yahan humne naye variables pass kar diye hain
            'a_name'          => $final_agent_id,
            'user_name'       => $final_agent_name,
            
            'make_address'    => $row['mac_address'] ?? null,
            'expiry_date'     => isset($row['expiry_date']) ? Carbon::parse($row['expiry_date'])->format('Y-m-d') : null,
        ]);
    }
}