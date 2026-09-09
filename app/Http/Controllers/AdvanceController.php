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

   /* public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:active,completed',
    ]);

    $advance = advance::findOrFail($id);
    $advance->status = $request->status;
    $advance->save();

    return redirect()->back()->with('success', 'Advance status updated successfully.');
}
    */

/*
public function destroy($id)
{
    $advance = advance::findOrFail($id);
    
    // Agar advance ke sath uski deductions linked hain to unhe bhi pehle delete kar sakte hain:
     advance_deduction::where('advance_id', $id)->delete();

    $advance->delete();

    return redirect()->back()->with('success', 'Advance record deleted successfully.');
}
*/

/*
public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,completed',
        ]);

        $advance = advance::findOrFail($id);
        
        // LOGIC: Agar advance pura pay ho chuka hai (remaining_amount 0 hai) aur user usko dobara Active kar raha hai
        if ($request->status == 'active' && $advance->remaining_amount <= 0) {
            return redirect()->back()->with('error', 'This advance completely payroll Generated (Remaining Amount: 0). Kindly do not Active This Advance.');
        }

        $advance->status = $request->status;

        // LOGIC: Agar user khud manual completed kar raha hai, toh baqaya raqam ko 0 kar dein taa ke aglay payroll mein deduct na ho.
        if ($request->status == 'completed') {
            $advance->remaining_amount = 0;
        }

        $advance->save();

        return redirect()->back()->with('success', 'Advance status successfully update ho gaya hai.');
    }
        */
    /////
public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,completed',
        ]);

        $advance = advance::findOrFail($id);

        // Professional Check 1: Agar balance 0 hai aur user Active karne ki koshish kare
        if ($request->status == 'active' && $advance->remaining_amount <= 0) {
            return redirect()->back()->with('error', 'This advance completely payroll Generated (Remaining Amount: 0). Kindly do not Active This Advance.');
        }

        // Professional Check 2: Jab Status Completed set ho, tou Remaining Amount ko 0 kardain
        if ($request->status == 'completed') {
            $advance->status = 'completed';
            $advance->remaining_amount = 0; // Baqaya raqam zero kar di taake payroll me deduction na ho
        } else {
            $advance->status = 'active';
        }

        $advance->save();

        return redirect()->back()->with('success', 'Advance status successfully updated.');
    }
    ///////

    public function destroy($id)
    {
        $advance = advance::findOrFail($id);
        
        // LOGIC: Pehle un Advance deductions ko get karo jo is advance se cut chuki hain
        $deductions = advance_deduction::where('advance_id', $id)->get();
        
        // Har deduction ko existing payroll me se revert (khatam) karo
        foreach ($deductions as $deduction) {
            $payroll = \App\Models\payroll::where('employee_id', $deduction->employee_id)
                ->where('month', $deduction->month)
                ->first();
                
            if ($payroll) {
                // Puraane payroll me advance deduction ki amount ko minus karein aur net salary wapas barha dein
                $payroll->advance_deduction -= $deduction->deducted_amount;
                $payroll->net_salary += $deduction->deducted_amount;
                $payroll->save();
            }
        }
        
        // Phir advance deductions table se records ko delete karo
        advance_deduction::where('advance_id', $id)->delete();

        // Aakhir me main Advance record delete kardo
        $advance->delete();

        return redirect()->back()->with('success', 'Advance Record Deleted Successfully. And Related Payrolls Deductions Rollback Successfully.');
    }

}
