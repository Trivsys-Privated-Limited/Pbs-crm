<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\customer;
use App\Models\customerNumber;
use App\Models\old_number;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class homeController extends Controller
{
    public function viewHome()
    {
        $customers = Customer::where('a_name', Auth::id())
            ->where('status', 'lead')
            ->orWhere('status', 'trial')
            ->get();

        $user = User::where('id', Auth::id())->first();

        $expiredNumbers = CustomerNumber::where('date', '<', Carbon::now())->get();

        $numbers = array_unique($expiredNumbers->pluck('customer_number')->toArray());

        foreach ($numbers as $num) {
            old_number::firstOrCreate(['number' => $num]);
        }

        CustomerNumber::where('date', '<', Carbon::now())->delete();

        return view('front.home', compact('customers', 'user'));
    }
}
