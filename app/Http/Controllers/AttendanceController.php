<?php
namespace App\Http\Controllers;

use App\Models\attendance;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Imports\AttendanceImport;
use Maatwebsite\Excel\Facades\Excel;


class AttendanceController extends Controller
{
    public function index()
    {
        $users = User::where('ip_address', '1')
            ->pluck('name')
            ->toArray();

        $attendances = Attendance::whereIn('employee_name', $users)
            ->select('employee_name')
            ->distinct()
            ->get();
        return view('admin.hr.employee.attendance', compact('attendances'));
    }

    public function create()
    {
        $employees = user::where('ip_address', '1')->where('role', 'user')->get();
        return view('admin.hr.employee.add_attendance', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required',
            'date'          => 'required|date',
            'checkin'       => 'required',
            'checkout'      => 'required',
            'status'        => 'required',
        ]);

        $checkIn  = Carbon::parse($request->checkin);
        $checkOut = Carbon::parse($request->checkout);

        Attendance::create([
            'employee_name' => $request->employee_name,
            'date'          => $request->date,
            'check_in'      => $request->checkin,
            'check_out'     => $request->checkout,
            'status'        => $request->status,
        ]);

        return redirect()->route('attendance.index');
    }

    public function show($employee_name)
    {
        $attendances = Attendance::where('employee_name', $employee_name)
            ->orderBy('date', 'desc')
            ->get();
        return view('admin.hr.employee.attendance_details', compact('attendances', 'employee_name'));
    }

    public function importAttendance()
    {
        return view('admin.import_attendance');
    }

    public function importAttendanceStore(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        Excel::import(new AttendanceImport, $request->file('file'));

        return redirect()->back()->with('success', 'Excel file imported successfully!');
    }
}
