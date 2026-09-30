<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\customer;
use App\Models\customerNumber;
use App\Models\old_number;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class homeController extends Controller
{
   
/*public function viewHome()
    {
        $customers = Customer::where('a_name', Auth::id())
            ->where('status', 'lead')
            ->orWhere('status', 'trial')
            ->get();

        $user = User::where('id', Auth::id())->first();

        // Expired numbers fetch karein
        $expiredNumbers = CustomerNumber::where('date', '<', Carbon::now())->get();
       // $expiredNumbers = CustomerNumber::where('date', '<', Carbon::now()->toDateString())->get();


        // Aapki original array_unique wali logic
        $numbers = array_unique($expiredNumbers->pluck('customer_number')->toArray());

        foreach ($numbers as $num) {
            // Expired collection se is unique number ka record dhoond kar region/date nikalein
            $record = $expiredNumbers->firstWhere('customer_number', $num);

            old_number::firstOrCreate(
                ['number' => $num], // Check karega ke number pehle se old_numbers mein na ho
                [
                    'region' => $record->region ?? 'us', // Agar null ho toh default 'us'
                    'date'   => $record->date ?? now()
                ]
            );
        }

        // Expired numbers ko delete karein
        CustomerNumber::where('date', '<', Carbon::now())->delete();
       // CustomerNumber::where('date', '<', Carbon::now()->toDateString())->delete();


        return view('front.home', compact('customers', 'user'));
    }*/
public function viewHome()
{
    $customers = Customer::where('a_name', Auth::id())
        ->whereIn('status', ['lead', 'trial'])
        ->get();

    $user = User::where('id', Auth::id())->first();

    $today = Carbon::now()->toDateString();

    // Expired numbers ko chunks mein old_numbers mein move karein (fast, bulk)
    CustomerNumber::where('date', '<', $today)
        ->select('id', 'customer_number', 'region')
        ->chunkById(2000, function ($rows) {
            $numbers = $rows->pluck('customer_number')->unique()->values();

            // Jo numbers pehle se old_numbers mein hain unhein ek hi query mein nikaal lein
            $existing = old_number::whereIn('number', $numbers)
                ->pluck('number')
                ->flip();

            $now = now();
            $insert = [];
            foreach ($rows->unique('customer_number') as $row) {
                if (!isset($existing[$row->customer_number])) {
                    $insert[] = [
                        'number'     => $row->customer_number,
                        'region'     => $row->region ?? 'us',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            DB::transaction(function () use ($insert, $rows) {
                if (!empty($insert)) {
                    old_number::insert($insert);
                }
                CustomerNumber::whereIn('id', $rows->pluck('id'))->delete();
            });
        });

    return view('front.home', compact('customers', 'user'));
}
}