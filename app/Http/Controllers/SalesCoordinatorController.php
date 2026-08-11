<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesCoordinatorController extends Controller
{
    public function dashboard()
    {
        // Yahan par hum check laga sakte hain (Optional security)
        if (Auth::user()->role !== 'sales coordinator') {
            return redirect()->route('login');
        }
        
        return view('sales_coordinator.dashboard');
    }
}