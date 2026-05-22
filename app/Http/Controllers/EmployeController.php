<?php
namespace App\Http\Controllers;

use App\Models\attendance;
use App\Models\customer;
use App\Models\employe;
use App\Models\oldCustomer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeController extends Controller
{
    public function index()
    {
        $getAllEmployees = employe::with('user')
            ->whereHas('user', function ($query) {
                $query->where('ip_address', '1');
            })
            ->get();

        return view('admin.hr.employee.employee', compact('getAllEmployees'));
    }

    public function create()
    {
        $getAllUsers = User::where('role', 'user')->get();
        return view('admin.hr.employee.add_employee', compact('getAllUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required',
            'salary'        => 'required|numeric',
            'target'        => 'required|numeric',
            'late'          => 'nullable|numeric',
            'leave'         => 'nullable|numeric',
            'offer_letter'  => 'nullable|file|max:2048',
            'resume'        => 'required|max:2048',
            'cnic'          => 'required',
            'employe_type'  => 'required',
            'profile_img'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $offerLetterPath = null;
        $resumePath      = null;
        $profileImgPath  = null;
        $cnicPath        = null;

        if ($request->hasFile('offer_letter')) {
            $offerLetter     = $request->file('offer_letter');
            $offerLetterName = time() . '_offer.' . $offerLetter->getClientOriginalExtension();
            $offerLetter->move(public_path('employee'), $offerLetterName);

            $offerLetterPath = 'employee/' . $offerLetterName;
        }

        if ($request->hasFile('resume')) {
            $resume     = $request->file('resume');
            $resumeName = time() . '_resume.' . $resume->getClientOriginalExtension();
            $resume->move(public_path('employee'), $resumeName);

            $resumePath = 'employee/' . $resumeName;
        }

        if ($request->hasFile('profile_img')) {
            $image     = $request->file('profile_img');
            $imageName = time() . '_profile.' . $image->getClientOriginalExtension();
            $image->move(public_path('employee'), $imageName);

            $profileImgPath = 'employee/' . $imageName;
        }

        if ($request->hasFile('cnic')) {
            $cnic     = $request->file('cnic');
            $cnicName = time() . '_cnic.' . $cnic->getClientOriginalExtension();
            $cnic->move(public_path('employee'), $cnicName);

            $cnicPath = 'employee/' . $cnicName;
        }

        employe::create([
            'employe_id'   => $request->employee_name,
            'salary'       => $request->salary,
            'target'       => $request->target,
            'late'         => $request->late,
            'leave'        => $request->leave,
            'offer_letter' => $offerLetterPath,
            'resume'       => $resumePath,
            'cnic'         => $cnicPath,
            'employe_type' => $request->employe_type,
            'profile_img'  => $profileImgPath,
        ]);

        return redirect()->route('employee.index')->with('success', 'Employee Created Successfully');
    }

    public function show($id)
    {
        $employee  = employe::with('user')->findOrFail($id);
        $agentName = $employee->user->name;
        $agentId   = $employee->user->id;

        $currentMonth = now()->month;
        $currentYear  = now()->year;

        $start = now()->startOfMonth();
        $end   = now()->endOfMonth();

        $attendances = attendance::where('employee_name', $agentName)
            ->whereBetween('date', [$start, $end])
            ->get();

        $monthlyAttendance = $attendances->where('status', 'Present')->count();
        $monthlyHalfDays   = $attendances->where('status', 'Half Day')->count();
        $monthlyAbsent     = $attendances->where('status', 'Absent')->count();

        $customer = customer::where('status', 'sale')
            ->where('price', '>', 0)
            ->where('a_name', $agentId)
            ->whereMonth('regitr_date', $currentMonth)
            ->whereYear('regitr_date', $currentYear)
            ->get();

        $oldcustomer = oldCustomer::where('status', 'sale')
            ->where('price', '>', 0)
            ->where('agent', $agentId)
            ->whereMonth('regitr_date', $currentMonth)
            ->whereYear('regitr_date', $currentYear)
            ->get();

        $sales = $customer->count() + $oldcustomer->count();

        $SalePrice = $customer->sum('price') + $oldcustomer->sum('price');

        $leads = customer::where('status', 'lead')
            ->where('a_name', $agentId)
            ->whereMonth('regitr_date', $currentMonth)
            ->whereYear('regitr_date', $currentYear)
            ->count();

        $trials = customer::where('status', 'trial')
            ->where('a_name', $agentId)
            ->whereMonth('regitr_date', $currentMonth)
            ->whereYear('regitr_date', $currentYear)
            ->count();

        return view('admin.hr.employee.view_employee', compact(
            'employee',
            'sales',
            'leads',
            'trials',
            'SalePrice',
            'monthlyAttendance',
            'monthlyHalfDays',
            'monthlyAbsent'
        ));
    }

    public function edit($id)
    {
        $employee    = employe::with('user')->findOrFail($id);
        $getAllUsers = User::where('role', 'user')->get();
        return view('admin.hr.employee.edit_employee', compact('employee', 'getAllUsers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_name' => 'required',
            'salary'        => 'required|numeric',
            'target'        => 'required|numeric',
            'late'          => 'nullable|numeric',
            'leave'         => 'nullable|numeric',
            'offer_letter'  => 'nullable',
            'resume'        => 'nullable',
            'cnic'          => 'nullable|file',
            'employe_type'  => 'required',
            'profile_img'   => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $employee = employe::findOrFail($id);

        $offerLetterPath = $employee->offer_letter;
        $resumePath      = $employee->resume;
        $profileImgPath  = $employee->profile_img;
        $cnicPath        = $employee->cnic;

        if ($request->hasFile('offer_letter')) {
            $offerLetter     = $request->file('offer_letter');
            $offerLetterName = time() . '_offer.' . $offerLetter->getClientOriginalExtension();
            $offerLetter->move(public_path('employee'), $offerLetterName);
            $offerLetterPath = 'employee/' . $offerLetterName;
        }

        if ($request->hasFile('resume')) {
            $resume     = $request->file('resume');
            $resumeName = time() . '_resume.' . $resume->getClientOriginalExtension();
            $resume->move(public_path('employee'), $resumeName);
            $resumePath = 'employee/' . $resumeName;
        }

        if ($request->hasFile('profile_img')) {
            $image     = $request->file('profile_img');
            $imageName = time() . '_profile.' . $image->getClientOriginalExtension();
            $image->move(public_path('employee'), $imageName);
            $profileImgPath = 'employee/' . $imageName;
        }

        if ($request->hasFile('cnic')) {
            $cnic     = $request->file('cnic');
            $cnicName = time() . '_cnic.' . $cnic->getClientOriginalExtension();
            $cnic->move(public_path('employee'), $cnicName);
            $cnicPath = 'employee/' . $cnicName;
        }

        $employee->update([
            'employe_id'   => $request->employee_name,
            'salary'       => $request->salary,
            'target'       => $request->target,
            'late'         => $request->late,
            'leave'        => $request->leave,
            'offer_letter' => $offerLetterPath,
            'resume'       => $resumePath,
            'cnic'         => $cnicPath,
            'employe_type' => $request->employe_type,
            'profile_img'  => $profileImgPath,
        ]);

        return redirect()->route('employee.index')->with('success', 'Employee Updated Successfully');
    }

    public function viewEmployeeProfile()
    {
        $employee = employe::where('employe_id', auth()->user()->id)
            ->with('user')
            ->first();

        $agentId   = Auth::id();
        $agentName = Auth::user()->name;

        $currentMonth = now()->month;
        $currentYear  = now()->year;

        $start = now()->startOfMonth();
        $end   = now()->endOfMonth();

        $attendances = attendance::where('employee_name', $agentName)
            ->whereBetween('date', [$start, $end])
            ->get();

        $monthlyAttendance = $attendances->where('status', 'Present')->count();
        $monthlyHalfDays   = $attendances->where('status', 'Half Day')->count();
        $monthlyAbsent     = $attendances->where('status', 'Absent')->count();

        $customer = customer::where('status', 'sale')
            ->where('price', '>', 0)
            ->where('a_name', $agentId)
            ->whereMonth('regitr_date', $currentMonth)
            ->whereYear('regitr_date', $currentYear)
            ->get();

        $oldcustomer = oldCustomer::where('status', 'sale')
            ->where('price', '>', 0)
            ->where('agent', $agentId)
            ->whereMonth('regitr_date', $currentMonth)
            ->whereYear('regitr_date', $currentYear)
            ->get();

        $sales = $customer->count() + $oldcustomer->count();

        $SalePrice = $customer->sum('price') + $oldcustomer->sum('price');

        $leads = customer::where('status', 'lead')
            ->where('a_name', $agentId)
            ->whereMonth('regitr_date', $currentMonth)
            ->whereYear('regitr_date', $currentYear)
            ->count();

        $trials = customer::where('status', 'trial')
            ->where('a_name', $agentId)
            ->whereMonth('regitr_date', $currentMonth)
            ->whereYear('regitr_date', $currentYear)
            ->count();

        return view('front.employee.profile', compact(
            'employee',
            'sales',
            'leads',
            'trials',
            'SalePrice',
            'monthlyAttendance',
            'monthlyHalfDays',
            'monthlyAbsent'
        ));
    }

}
