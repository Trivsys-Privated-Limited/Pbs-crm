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

    public function create()
    {
        $employees = User::where('role', 'user')->get();
        return view('admin.hr.payroll.add_payroll', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'month'       => 'required',
        ]);

        $employeeId = $request->employee_id;
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
        $monthNum = $carbon->month;

        $absentDays      = 0;
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

        $advanceDeduction = $this->handleAdvance($employeeId, $month);

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

}
