<?php
namespace App\Http\Controllers;

use App\Models\leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = leave::with('user')->where('employee_id', Auth::id())->get();
        return view('front.employee.leave_list', compact('leaves'));
    }
    public function create()
    {
        return view('front.employee.add_leave');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'img'        => 'nullable|image|max:2048',
            'reason'     => 'required|string',
        ]);

        if ($request->hasFile('img')) {
            $image     = $request->file('img');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('employee'), $imageName);
            $validatedData['img'] = $imageName;
        } else {
            $validatedData['img'] = null;
        }

        leave::create([
            'start_date'  => $validatedData['start_date'],
            'end_date'    => $validatedData['end_date'],
            'reason'      => $validatedData['reason'],
            'img'         => $validatedData['img'],
            'employee_id' => auth()->user()->id,
            'status'      => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Leave submitted successfully!');
    }
    public function approve($id)
    {
        $leave         = leave::findOrFail($id);
        $leave->status = 'Approved';
        $leave->save();

        return back()->with('success', 'Leave Approved');
    }

    public function reject(Request $request, $id)
    {
        $leave                   = leave::findOrFail($id);
        $leave->status           = 'Rejected';
        $leave->reason_to_reject = $request->reason_to_reject;
        $leave->save();

        return back()->with('error', 'Leave Rejected');
    }

}
