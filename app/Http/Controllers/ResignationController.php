<?php
namespace App\Http\Controllers;

use App\Models\resignation;
use Illuminate\Http\Request;

class ResignationController extends Controller
{
    public function index()
    {
        $resignations = resignation::with('employee')->get();
        return view('admin.hr.employee.manage_resignation', compact('resignations'));
    }

    public function create()
    {
        return view('front.employee.add_resignation');
    }

    public function store(Request $request)
    {
        $request->validate([
            'resignation_date' => 'required|date',
            'reason'           => 'required|string|max:1000',
        ]);

        resignation::create([
            'employee_id'      => auth()->user()->id,
            'resignation_date' => $request->resignation_date,
            'reason'           => $request->reason,
            'status'           => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Resignation request submitted successfully.');

    }

    public function accept($id)
    {
        $resignation         = resignation::findOrFail($id);
        $resignation->status = 'Accepted';
        $resignation->save();

        return redirect()->back()->with('success', 'Resignation accepted successfully.');
    }

    public function reject($id)
    {
        $resignation         = resignation::findOrFail($id);
        $resignation->status = 'Declined';
        $resignation->save();

        return redirect()->back()->with('success', 'Resignation declined successfully.');
    }

    public function show($id)
    {
        $resignation = Resignation::with('employee')->findOrFail($id);
        return view('admin.hr.employee.resignation_letter', compact('resignation'));
    }
}
