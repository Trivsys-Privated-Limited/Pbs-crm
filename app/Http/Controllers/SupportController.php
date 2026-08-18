<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\supportImport;
use App\Models\support;
use App\Models\ExpiredSupport;
use App\Models\User; // User model add kiya
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    // 1. Import Form Dikhane Ke Liye
    public function index()
    {
        // Support role waly tamam users ko fetch kiya (Agar role name kuch aur hai toh change kar lein)
        $supportUsers = User::where('role', 'Support')->orWhere('role', 'support')->get(); 
        return view('admin.import_Form', compact('supportUsers'));
    }

    // 2. Excel File Import Karne Ke Liye
   /* public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt',
            'expiry_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id' // ID lazmi honi chahiye
        ]);

        Excel::import(new supportImport($request->expiry_date, $request->assigned_to), $request->file('file'));

        return redirect()->back()->with('success', 'Excel file imported and assigned successfully!');
    }
        */
    

    // File choose import py count hoga data kitna hy iss sheet file ma. 

    public function countExcelRows(Request $request)
    {
        if ($request->hasFile('file')) {
            // Excel file ko array mein convert karein
            $data = Excel::toArray([], $request->file('file'));
            
            // Sheet 1 ka data count karein (1st row heading hoti hai isliye -1 kiya)
            $totalRows = count($data[0]) - 2; 
            
            return response()->json(['success' => true, 'count' => $totalRows]);
        }
        return response()->json(['success' => false, 'count' => 0]);
    }

    // 2. Apne purane store method ko is se replace karein
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt',
            'expiry_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
            'import_limit' => 'nullable|integer|min:1' // Naya field add kiya limit ke liye
        ]);

        // Import class mein expiry_date, assigned_to aur limit pass karein
        Excel::import(new supportImport(
            $request->expiry_date, 
            $request->assigned_to, 
            $request->import_limit // Custom number jo admin ne form mein dala
        ), $request->file('file'));

        return redirect()->back()->with('success', 'Excel file imported successfully without duplicates!');
    }

    // 3. Expired Data Ko Find Karna
    public function expiredSupportData()
    {
        $expiredData = support::whereDate('expiry_date', '<=', now()->toDateString())
            ->whereNull('status')
            ->get();

        foreach ($expiredData as $data) {
            ExpiredSupport::create([
                'name'            => $data->name,
                'number'          => $data->number,
                'agent_name'      => $data->agent_name,
                'old_expiry_date' => $data->expiry_date,
                'show_status'     => $data->show_status,
                'assigned_to'     => $data->assigned_to, // Purana assign bnda bhi save rakhein
            ]);
            $data->delete();
        }

        $expiredSupports = ExpiredSupport::orderBy('id', 'desc')->paginate(100);
        $supportUsers = User::where('role', 'Support')->orWhere('role', 'support')->get(); // Dropdown ky liye

        return view('admin.expired_supports', compact('expiredSupports', 'supportUsers'));
    }

    // 4. Single Data Ko Wapis Re-assign Karna
    public function reassignSupportData(Request $request, $id)
    {
        $request->validate([
            'new_expiry_date' => 'required|date',
            'assigned_to'     => 'required|exists:users,id'
        ]);

        $expiredRecord = ExpiredSupport::findOrFail($id);
        $existsInSupport = support::where('number', $expiredRecord->number)->exists();

        if ($existsInSupport) {
            return back()->with('error', 'This customer number already exists in active Support list!');
        }

        support::create([
            'name'             => $expiredRecord->name,
            'number'           => $expiredRecord->number,
            'agent_name'       => $expiredRecord->agent_name,
            'expiry_date'      => $request->new_expiry_date,
            'show_status'      => $expiredRecord->show_status,
            'assigned_by_name' => Auth::user()->name,
            'assigned_by_role' => Auth::user()->role,
            'assigned_date'    => now()->toDateString(),
            'assigned_to'      => $request->assigned_to, // Jisko select kiya usko assign hoga
        ]);

        $expiredRecord->delete();

        return back()->with('success', 'Customer successfully re-assigned to Support team!');
    }

    // 5. Multiple Data Ko Ek Sath Re-assign Karna
    public function reassignMultipleSupportData(Request $request)
    {
        $request->validate([
            'selected_ids'    => 'required|array',
            'new_expiry_date' => 'required|date',
            'assigned_to'     => 'required|exists:users,id'
        ]);

        $expiredRecords = ExpiredSupport::whereIn('id', $request->selected_ids)->get();
        $assignedCount = 0;

        foreach ($expiredRecords as $expiredRecord) {
            $existsInSupport = support::where('number', $expiredRecord->number)->exists();
            if (!$existsInSupport) {
                support::create([
                    'name'             => $expiredRecord->name,
                    'number'           => $expiredRecord->number,
                    'agent_name'       => $expiredRecord->agent_name,
                    'expiry_date'      => $request->new_expiry_date,
                    'show_status'      => $expiredRecord->show_status,
                    'assigned_by_name' => Auth::user()->name,
                    'assigned_by_role' => Auth::user()->role,
                    'assigned_date'    => now()->toDateString(),
                    'assigned_to'      => $request->assigned_to,
                ]);
                $expiredRecord->delete();
                $assignedCount++;
            }
        }

        return back()->with('success', $assignedCount . ' Customers successfully re-assigned!');
    }
}