<?php
namespace App\Http\Controllers;

use App\Models\advance;
use App\Models\advance_deduction;
use App\Models\User;
use Illuminate\Http\Request;

class AdvanceController extends Controller
{
    public function index()
    {
        $advances = advance::with('user')->get();
        return view('admin.hr.advance.advance', compact('advances'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.hr.advance.add_advance', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee'       => 'required',
            'amount'         => 'required',
            'monthly_amount' => 'required',
            'start_month'    => 'required',
        ]);

        advance::create([
            'employee_id'      => $request->employee,
            'advance_amount'   => $request->amount,
            'monthly_amount'   => $request->monthly_amount,
            'start_month'      => $request->start_month,
            'remaining_amount' => $request->amount,
        ]);
        return redirect()->route('advance.index')->with('success', 'Advance record added successfully.');
    }

    public function show($id)
    {
        $advances = advance_deduction::with('user', 'advance')
            ->where('advance_id', $id)
            ->get();
        return view('admin.hr.advance.advance_detail',compact('advances'));
    }
}
