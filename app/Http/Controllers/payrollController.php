<?php
namespace App\Http\Controllers;

use App\Models\advance;
use App\Models\advance_deduction;
use App\Models\attendance;
use App\Models\employe;
use App\Models\payroll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = payroll::with('user')
            ->orderBy('employee_id')
            ->orderBy('month')
            ->get();

        $groupedPayrolls = $payrolls->groupBy('employee_id');

        return view('admin.hr.payroll.payroll', compact('groupedPayrolls'));
    }

   /* public function create()
    {
       // $employees = User::where('role', 'user')->get();
        // 'user' aur 'support' dono roles ke employees fetch hongay
        $employees = User::whereIn('role', ['user', 'support'])->get();
        return view('admin.hr.payroll.add_payroll', compact('employees'));
    } */ 


        //// testing 9/8/2026 ////

            public function create()
    {
        // 'user' aur 'support' dono roles ke users fetch honge
        $employees = User::whereIn('role', ['user', 'support', 'Support'])->get();
        return view('admin.hr.payroll.add_payroll', compact('employees'));
    }

    

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'month'       => 'required',
        ]);

     /*   $employeeId = $request->employee_id;
        $month      = $request->month;
        $commission = (int) ($request->commission ?? 0);

        $existingPayroll = payroll::where('employee_id', $employeeId)
            ->where('month', $month)
            ->first();

        if ($existingPayroll) {
            return redirect()->route('payroll.index')
                ->with('error', "Payroll for this employee for $month is already generated.");
        }

        $employee = employe::with('user')->where('employe_id', $employeeId)->first();

        if (! $employee) {
            return back()->with('error', 'Employee not found');
        }

        $basicSalary = $request->filled('manual_salary') ? (int) $request->manual_salary : (int) $employee->salary;
        $manualDeduction = $request->filled('manual_deduction') ? (int) $request->manual_deduction : 0;

        $carbon   = Carbon::createFromFormat('Y-m', $month);
        $year     = $carbon->year;
        $monthNum = $carbon->month; */


        /// testing code 9/8/2026  ///

        $employeeId = $request->employee_id;
        
        // Month ko flexibly parse karein taake separation symbol ka error na aaye
        $carbon   = Carbon::parse($request->month);
        $month    = $carbon->format('Y-m');
        $year     = $carbon->year;
        $monthNum = $carbon->month;

        $commission = (int) ($request->commission ?? 0);

        $existingPayroll = payroll::where('employee_id', $employeeId)
            ->where('month', $month)
            ->first();

        if ($existingPayroll) {
            return redirect()->route('payroll.index')
                ->with('error', "Payroll for this employee for $month is already generated.");
        }

        $employee = employe::with('user')->where('employe_id', $employeeId)->first();

        if (! $employee) {
            return back()->with('error', 'Employee not found');
        }

        $basicSalary = $request->filled('manual_salary') ? (int) $request->manual_salary : (int) $employee->salary;
        $manualDeduction = $request->filled('manual_deduction') ? (int) $request->manual_deduction : 0;
        


      /*  $absentDays      = 0;
        $lateCount       = 0;
        $absentDeduction = 0;
        $lateDeduction   = 0;

        if ($request->attendance_deduction === 'yes') {

            $absentDays = attendance::where('employee_name', $employee->user->name)
                ->whereYear('date', $year)
                ->whereMonth('date', $monthNum)
                ->where('status', 'Absent')
                ->count();

            $lateCount = attendance::where('employee_name', $employee->user->name)
                ->whereYear('date', $year)
                ->whereMonth('date', $monthNum)
                ->whereIn('status', ['Late', 'Half Day'])
                ->count();

            $absentDeduction = $absentDays * 1000;
            $lateDeduction   = $lateCount > 3 ? 1000 : 0;
        }

        $advanceDeduction = $this->handleAdvance($employeeId, $month); */


        //// Testing Code 11/09/2026 start ////
        // Attendance Calculation (Manual Input ko Pehle Priority Milegi)
        $absentDays      = 0;
        $lateCount       = 0;
        $absentDeduction = 0;
        $lateDeduction   = 0;

        if ($request->filled('manual_absent_days') || $request->filled('manual_absent_deduction')) {
            $absentDays      = (int) ($request->manual_absent_days ?? 0);
            $absentDeduction = (int) ($request->manual_absent_deduction ?? 0);

            // Agar user ne sirf absent days dale hon aur amount 0 chhor di ho, to 1000 per day ke hisab se calculate kare
            if ($absentDays > 0 && $absentDeduction == 0) {
                $absentDeduction = $absentDays * 1000;
            }
        } elseif ($request->attendance_deduction === 'yes') {
            $absentDays = attendance::where('employee_name', $employee->user->name)
                ->whereYear('date', $year)
                ->whereMonth('date', $monthNum)
                ->where('status', 'Absent')
                ->count();

            $lateCount = attendance::where('employee_name', $employee->user->name)
                ->whereYear('date', $year)
                ->whereMonth('date', $monthNum)
                ->whereIn('status', ['Late', 'Half Day'])
                ->count();

            $absentDeduction = $absentDays * 1000;
            $lateDeduction   = $lateCount > 3 ? 1000 : 0;
        }

        // Advance Calculation (Manual advance support ke sath)
        $manualAdvance = $request->filled('manual_advance_deduction') ? (int) $request->manual_advance_deduction : null;
        $advanceDeduction = $this->handleAdvance($employeeId, $month, $manualAdvance);


        /// Testing Code 10/09/2026 end ///


            $netSalary =
            $basicSalary
             + $commission
             - $absentDeduction
             - $lateDeduction
             - $advanceDeduction
             - $manualDeduction;

        payroll::create([
            'employee_id'       => $employeeId,
            'month'             => $month,
            'basic_salary'      => $basicSalary,
            'absent_days'       => $absentDays,
            'late_count'        => $lateCount,
            'absent_deduction'  => $absentDeduction,
            'late_deduction'    => $lateDeduction,
            'advance_deduction' => $advanceDeduction,
            'manual_deduction'  => $manualDeduction,
            'commission'        => $commission,
            'net_salary'        => $netSalary,
        ]);

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll generated successfully');
    }
/*
    private function handleAdvance($employeeId, $month)
    {
        $advance = advance::where('employee_id', $employeeId)
            ->where('status', 'active')
            ->first();

        if (! $advance || $advance->remaining_amount <= 0) {
            return 0;
        }

        $deduct = min($advance->monthly_amount, $advance->remaining_amount);

        advance_deduction::create([
            'advance_id'      => $advance->id,
            'employee_id'     => $employeeId,
            'month'           => $month,
            'deducted_amount' => $deduct,
        ]);

        $advance->remaining_amount -= $deduct;

        if ($advance->remaining_amount == 0) {
            $advance->status = 'completed';
        }

        $advance->save();

        return $deduct;
    }
        */
    /// Testing Code Add 10/09/2026 ///
        private function handleAdvance($employeeId, $month, $manualAdvance = null)
    {
        $advance = advance::where('employee_id', $employeeId)
            ->where('status', 'active')
            ->first();

        // 1. Agar user ne form me manually advance enter kiya ho
        if ($manualAdvance !== null) {
            $deduct = (int) $manualAdvance;

            // Agar database me active advance exist karta hai to uski remaining amount aur log update karein
            if ($deduct > 0 && $advance) {
                $actualDeduct = min($deduct, $advance->remaining_amount);

                advance_deduction::create([
                    'advance_id'      => $advance->id,
                    'employee_id'     => $employeeId,
                    'month'           => $month,
                    'deducted_amount' => $actualDeduct,
                ]);

                $advance->remaining_amount -= $actualDeduct;

                if ($advance->remaining_amount <= 0) {
                    $advance->remaining_amount = 0;
                    $advance->status = 'completed';
                }

                $advance->save();
            }

            return $deduct;
        }

        // 2. Agar manual advance enter nahi kiya, to automated system chalega
        if (! $advance || $advance->remaining_amount <= 0) {
            return 0;
        }

        $deduct = min($advance->monthly_amount, $advance->remaining_amount);

        advance_deduction::create([
            'advance_id'      => $advance->id,
            'employee_id'     => $employeeId,
            'month'           => $month,
            'deducted_amount' => $deduct,
        ]);

        $advance->remaining_amount -= $deduct;

        if ($advance->remaining_amount == 0) {
            $advance->status = 'completed';
        }

        $advance->save();

        return $deduct;
    }

    /// Testing Code End 10/09/2026 ///
    

    public function show($id)
    {
        $payrolls = payroll::with('user')
            ->where('employee_id', $id)
            ->orderBy('employee_id')
            ->orderBy('month')
            ->get();

        $EmployeePayroll = $payrolls->groupBy('employee_id');
        return view('admin.hr.payroll.payroll_detail', compact('EmployeePayroll'));
    }

    public function showPayroll($id)
    {
        $payroll = payroll::with('user')->findOrFail($id);
        return view('admin.hr.payroll.payroll_slip', compact('payroll'));
    }

    //// Code on 11-09-2026  edit / delete ////
    // Edit Form Show Karne Ka Function
    public function edit($id)
    {
        $payroll = payroll::with('user')->findOrFail($id);
        $employees = User::whereIn('role', ['user', 'support', 'Support'])->get();
        return view('admin.hr.payroll.edit_payroll', compact('payroll', 'employees'));
    }

    // Edit Data Ko Update Karne Ka Function
    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required',
            'month'       => 'required',
        ]);

        $payroll = payroll::findOrFail($id);
        $employeeId = $request->employee_id;
        
        $carbon   = Carbon::parse($request->month);
        $month    = $carbon->format('Y-m');

        // Check if another payroll exists for this month and employee (except the current one)
        $existingPayroll = payroll::where('employee_id', $employeeId)
            ->where('month', $month)
            ->where('id', '!=', $id)
            ->first();

        if ($existingPayroll) {
            return redirect()->back()->with('error', "Payroll for this employee for $month is already generated.");
        }

        $employee = employe::with('user')->where('employe_id', $employeeId)->first();

        $basicSalary = $request->filled('manual_salary') ? (int) $request->manual_salary : (int) $employee->salary;
        $manualDeduction = $request->filled('manual_deduction') ? (int) $request->manual_deduction : (int)$payroll->manual_deduction;
        $commission = (int) ($request->commission ?? 0);

        // Fetching Manual Deductions
        $absentDeduction = $request->filled('manual_absent_deduction') ? (int) $request->manual_absent_deduction : 0;
        $advanceDeduction = $request->filled('manual_advance_deduction') ? (int) $request->manual_advance_deduction : 0;
        
        // Retain late deduction if it existed
        $lateDeduction = $payroll->late_deduction;

        $netSalary = $basicSalary + $commission - $absentDeduction - $lateDeduction - $advanceDeduction - $manualDeduction;

        $payroll->update([
            'employee_id'       => $employeeId,
            'month'             => $month,
            'basic_salary'      => $basicSalary,
            'absent_deduction'  => $absentDeduction,
            'advance_deduction' => $advanceDeduction,
            'manual_deduction'  => $manualDeduction,
            'commission'        => $commission,
            'net_salary'        => $netSalary,
        ]);

        return redirect()->route('payroll.show', $employeeId)
            ->with('success', 'Payroll updated successfully');
    }

    // Payroll Delete Karne Ka Function
    public function destroy($id)
    {
        $payroll = payroll::findOrFail($id);
        $employeeId = $payroll->employee_id;
        $payroll->delete();
        
        return redirect()->route('payroll.show', $employeeId)
            ->with('success', 'Payroll deleted successfully');
    }
    /// End Code 11-09-2026 edit / delete ////

    /// Code add for Payroll slip approve 12/09/2026 ///
    public function approveSlip($id)
{
    $payroll = \App\Models\payroll::findOrFail($id);
    $payroll->slip_status = 'approved';
    $payroll->save();

    return redirect()->back()->with('success', 'Payroll slip request approved for employee.');
}
    // Employee approved slip dekhne ke liye function
    public function showEmployeeSlip($id)
    {
        $payroll = payroll::with('user')->findOrFail($id);

        // Security Check: Agar user employee hai to check karein ke slip usi ki ho aur status 'approved' ho
       if (auth()->user()->role !== 'admin') {
            if ($payroll->employee_id != auth()->id() || $payroll->slip_status !== 'approved') {
                return redirect()->back()->with('error', 'Slip is not approved yet or unauthorized access.');
            }
        }

        return view('admin.hr.payroll.payroll_slip', compact('payroll'));
    }

/// Code End for Payroll slip approve 12/09/2026 ///
    /// Code add for Payroll slip reject 14/09/2026 ///
    public function rejectSlip($id)
    {
        $payroll = \App\Models\payroll::findOrFail($id);
        $payroll->slip_status = 'none';
        $payroll->save();

        return redirect()->back()->with('success', 'Payroll slip request has been rejected.');
    }
    /// Code End for Payroll slip reject 14/09/2026 ///




}
