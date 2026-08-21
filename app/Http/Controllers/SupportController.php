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
use App\Models\customer;
use App\Models\oldCustomer;

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

        $expiredSupports = ExpiredSupport::orderBy('id', 'desc')->paginate(50);
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
    // Limit ky sath expiry number support ko re-assign krny ka function
    
    /*public function reassignLimitSupportData(Request $request) 
    {
        $request->validate([
            'limit_count' => 'required|integer|min:1',
            'new_expiry_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
        ]);
        // Limit Quantity ke Mutabiq top records nikalna 
        $expiredRecords = ExpiredSupport::orderBy('id','asc')->take((int)$request->limit_count)->get();

        if ($expiredRecords->isEmpty()) {
            return back()->with('error','No Expired Numbers Data Available to Re-assign to Support Team');
        }
        $assignedCount = 0;

        foreach($expiredRecords as $expiredRecord) {
            $existsInSupport = support::where('number', $expiredRecord->number)->exists();
            if (!$existsInSupport) {
                support::create([
                    'name' => $expiredRecord->name,
                    'number' => $expiredRecord->number,
                    'agent_name' => $expiredRecord->agent_name,
                    'expiry_date' => $expiredRecord->expiry_date,
                    'show_status' => $expiredRecord->show_status,
                    'assigned_by_name' => Auth::user()->name,
                    'assigned_by_role' => Auth::user()->role,
                    'assigned_date' => now()->toDateString(),
                    'assigned_to' => $request->assigned_to,
                ]);

                $expiredRecord->delete();
                $assignedCount++;
            }
            else {
                // Agar number active support mein pehle se ho to duplicate clean kar dein
                $expiredRecord->delete();
            }
        }
        return back()->with('success', 'Selected Number Re-assigned to Support Team with Limit Quantity');

    }*/
            public function reassignLimitSupportData(Request $request) 
    {
        $request->validate([
            'limit_count' => 'required|integer|min:1',
            'new_expiry_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
        ]);
        // Limit Quantity ke Mutabiq top records nikalna 
        $expiredRecords = ExpiredSupport::orderBy('id','asc')->take((int)$request->limit_count)->get();

        if ($expiredRecords->isEmpty()) {
            return back()->with('error','No Expired Numbers Data Available to Re-assign to Support Team');
        }
        $assignedCount = 0;

        foreach($expiredRecords as $expiredRecord) {
            $existsInSupport = support::where('number', $expiredRecord->number)->exists();
            if (!$existsInSupport) {
                support::create([
                    'name' => $expiredRecord->name,
                    'number' => $expiredRecord->number,
                    'agent_name' => $expiredRecord->agent_name,
                    'expiry_date' => $request->new_expiry_date, // <--- FIX: Yahan $request->new_expiry_date use karein
                    'show_status' => $expiredRecord->show_status,
                    'assigned_by_name' => Auth::user()->name,
                    'assigned_by_role' => Auth::user()->role,
                    'assigned_date' => now()->toDateString(),
                    'assigned_to' => $request->assigned_to,
                ]);

                $expiredRecord->delete();
                $assignedCount++;
            }
            else {
                // Agar number active support mein pehle se ho to duplicate clean kar dein
                $expiredRecord->delete();
            }
        }
        return back()->with('success', $assignedCount . ' Customer Numbers Re-assigned to Support Team Successfully');
    }

    // View the form and count total agent sales
    public function viewSendSalesToSupportForm($agent_id)
    {
        $agent = User::findOrFail($agent_id);
        
        // Count sales from both tables
        $oldSalesCount = customer::where('a_name', $agent_id)->where('status', 'sale')->count();
        $newSalesCount = oldCustomer::where('agent', $agent_id)->where('status', 'sale')->count();
        $totalSales = $oldSalesCount + $newSalesCount;

        // Fetch support users for the assignment dropdown
        $supportUsers = User::whereIn('role', ['Support', 'support'])->get();

        return view('admin.send_sales_to_support', compact('agent', 'totalSales', 'supportUsers'));
    }

    // Safely copy data to the Support table
  /*  public function sendSalesToSupport(Request $request, $agent_id)
    {
        $request->validate([
            'expiry_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $agent = User::findOrFail($agent_id);

        // Fetch all sales records for this agent
        $oldSales = customer::where('a_name', $agent_id)->where('status', 'sale')->get();
        $newSales = oldCustomer::where('agent', $agent_id)->where('status', 'sale')->get();
        
        $allSales = $oldSales->merge($newSales);
        $copiedCount = 0;
        $duplicateCount = 0;

        foreach ($allSales as $sale) {
            $number = $sale->customer_number;
            
            // Check for duplicates in both active support and expired support tables
            $existsInSupport = support::where('number', $number)->exists();
            $existsInExpired = ExpiredSupport::where('number', $number)->exists();

            if (!$existsInSupport && (!$existsInExpired)) {
                // Copy data to support, keeping the original sale safe
                support::create([
                    'name'             => $sale->customer_name,
                    'number'           => $number,
                    'agent_name'       => $agent->name,
                    'expiry_date'      => $request->expiry_date,
                    'show_status'      => 'Sale',
                    'assigned_by_name' => Auth::user()->name,
                    'assigned_by_role' => Auth::user()->role,
                    'assigned_date'    => now()->toDateString(),
                    'assigned_to'      => $request->assigned_to,
                ]);
                $copiedCount++;
            } else {
                $duplicateCount++;
            }
        }

        return redirect()->route('viewAgentSaleTable')->with('success', "{$copiedCount} Sales successfully copied to Support. {$duplicateCount} duplicates were skipped. Original sales records remain untouched.");
    } */

        // Safely copy data to the Support table with a specific limit
   /* public function sendSalesToSupport(Request $request, $agent_id)
    {
        $request->validate([
            'expiry_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
            'limit'       => 'required|integer|min:1',
        ]);

        $agent = User::findOrFail($agent_id);

        // Fetch all sales records for this agent from both tables
        $oldSales = customer::where('a_name', $agent_id)->where('status', 'sale')->get();
        $newSales = oldCustomer::where('agent', $agent_id)->where('status', 'sale')->get();
        
        // Merge them and apply the user-defined limit
        $allSales = $oldSales->merge($newSales)->take($request->limit);
        
        $copiedCount = 0;
        $duplicateCount = 0;

        foreach ($allSales as $sale) {
            $number = $sale->customer_number;
            
            // Check for duplicates in both active support and expired support tables
            $existsInSupport = support::where('number', $number)->exists();
            $existsInExpired = ExpiredSupport::where('number', $number)->exists();

            if (!$existsInSupport && (!$existsInExpired)) {
                // Copy data to support, keeping the original sale safe
                support::create([
                    'name'             => $sale->customer_name,
                    'number'           => $number,
                    'agent_name'       => $agent->name,
                    'expiry_date'      => $request->expiry_date,
                    'show_status'      => 'Sale',
                    'assigned_by_name' => Auth::user()->name,
                    'assigned_by_role' => Auth::user()->role,
                    'assigned_date'    => now()->toDateString(),
                    'assigned_to'      => $request->assigned_to,
                ]);
                $copiedCount++;
            } else {
                $duplicateCount++;
            }
        }

        return redirect()->route('viewAgentSaleTable')->with('success', "{$copiedCount} Sales successfully copied to Support based on your limit. {$duplicateCount} duplicates were skipped.");
    } */

        // same function but some change added //

        // Safely copy data to the Support table with a specific limit
    public function sendSalesToSupport(Request $request, $agent_id)
    {
        $request->validate([
            'expiry_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
            'limit'       => 'required|integer|min:1',
        ]);

        $agent = User::findOrFail($agent_id);

        // 1. Get all numbers already present in both support and expired_support tables
        $existingSupportNumbers = support::pluck('number')->toArray();
        $existingExpiredNumbers = ExpiredSupport::pluck('number')->toArray();
        $allExistingNumbers = array_merge($existingSupportNumbers, $existingExpiredNumbers);

        // 2. Fetch sales records excluding the numbers that are already in support/expired_support
       /* $oldSales = customer::where('a_name', $agent_id)
                            ->where('status', 'sale')
                            ->whereNotIn('customer_number', $allExistingNumbers)
                            ->get();

        $newSales = oldCustomer::where('agent', $agent_id)
                               ->where('status', 'sale')
                               ->whereNotIn('customer_number', $allExistingNumbers)
                               ->get(); */

        // new one add for testing
        // 2. Fetch sales records with memory-efficient limit logic
        $requestedLimit = (int) $request->limit;

        $oldSales = customer::where('a_name', $agent_id)
                            ->where('status', 'sale')
                            ->whereNotIn('customer_number', $allExistingNumbers)
                            ->take($requestedLimit)
                            ->get();

        $remainingLimit = $requestedLimit - $oldSales->count();

        if ($remainingLimit > 0) {
            $newSales = oldCustomer::where('agent', $agent_id)
                                   ->where('status', 'sale')
                                   ->whereNotIn('customer_number', $allExistingNumbers)
                                   ->take($remainingLimit)
                                   ->get();
        } else {
            $newSales = collect([]);
        }
        
        // 3. Merge them
        $allSales = $oldSales->merge($newSales);

        // end here //
        
        // 3. Merge them and apply the limit. Now it will only take fresh sales.
       // $allSales = $oldSales->merge($newSales)->take($request->limit);
        
        $copiedCount = 0;
        $duplicateCount = 0;

        foreach ($allSales as $sale) {
            $number = $sale->customer_number;
            
            // Optional double-check before inserting
            $existsInSupport = support::where('number', $number)->exists();
            $existsInExpired = ExpiredSupport::where('number', $number)->exists();

            if (!$existsInSupport && !$existsInExpired) {
                // Copy data to support, keeping the original sale safe
                support::create([
                    'name'             => $sale->customer_name,
                    'number'           => $number,
                    'agent_name'       => $agent->name,
                    'expiry_date'      => $request->expiry_date,
                    'show_status'      => 'Sale',
                    'assigned_by_name' => Auth::user()->name,
                    'assigned_by_role' => Auth::user()->role,
                    'assigned_date'    => now()->toDateString(),
                    'assigned_to'      => $request->assigned_to,
                ]);
                $copiedCount++;
            } else {
                $duplicateCount++;
            }
        }

        return redirect()->route('viewAgentSaleTable')->with('success', "{$copiedCount} Sales successfully copied to Support based on your limit. {$duplicateCount} duplicates were skipped.");
    }

    // --- NEW FUNCTIONS FOR ALL AGENTS SALES TO SUPPORT --- //

    // 1. View the form and count total global valid sales
    public function viewSendAllSalesToSupportForm()
    {
        // Get all numbers already present in both support and expired_support tables
        $existingSupportNumbers = support::pluck('number')->toArray();
        $existingExpiredNumbers = ExpiredSupport::pluck('number')->toArray();
        $allExistingNumbers = array_merge($existingSupportNumbers, $existingExpiredNumbers);

        // Fetch count of fresh sales excluding the numbers that are already in support/expired_support
        $oldSalesCount = customer::where('status', 'sale')
                            ->whereNotIn('customer_number', $allExistingNumbers)
                            ->count();

        $newSalesCount = oldCustomer::where('status', 'sale')
                               ->whereNotIn('customer_number', $allExistingNumbers)
                               ->count();
        
        $totalSales = $oldSalesCount + $newSalesCount;

        // Fetch support users for the assignment dropdown
        $supportUsers = User::whereIn('role', ['Support', 'support'])->get();

        return view('admin.send_all_sales_to_support', compact('totalSales', 'supportUsers'));
    }

    // 2. Safely copy ALL agents data to the Support table with a limit
    public function sendAllSalesToSupport(Request $request)
    {
        $request->validate([
            'expiry_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
            'limit'       => 'required|integer|min:1',
        ]);

        $existingSupportNumbers = support::pluck('number')->toArray();
        $existingExpiredNumbers = ExpiredSupport::pluck('number')->toArray();
        $allExistingNumbers = array_merge($existingSupportNumbers, $existingExpiredNumbers);

        // Fetch fresh sales (Global) excluding duplicates
      /*  $oldSales = customer::where('status', 'sale')
                            ->whereNotIn('customer_number', $allExistingNumbers)
                            ->get();

        $newSales = oldCustomer::where('status', 'sale')
                               ->whereNotIn('customer_number', $allExistingNumbers)
                               ->get(); */
        
        // Merge them and apply the user's limit
      //  $allSales = $oldSales->merge($newSales)->take($request->limit);

    // new add for test purpose //
      // Fetch fresh sales (Global) with memory-efficient limit logic
        $requestedLimit = (int) $request->limit;

        $oldSales = customer::where('status', 'sale')
                            ->whereNotIn('customer_number', $allExistingNumbers)
                            ->take($requestedLimit)
                            ->get();

        $remainingLimit = $requestedLimit - $oldSales->count();

        if ($remainingLimit > 0) {
            $newSales = oldCustomer::where('status', 'sale')
                                   ->whereNotIn('customer_number', $allExistingNumbers)
                                   ->take($remainingLimit)
                                   ->get();
        } else {
            $newSales = collect([]);
        }
        
        // Merge them
        $allSales = $oldSales->merge($newSales);

        // end here //
        
        $copiedCount = 0;
        $duplicateCount = 0;

        foreach ($allSales as $sale) {
            $number = $sale->customer_number;
            
            // Double check for duplicates
            $existsInSupport = support::where('number', $number)->exists();
            $existsInExpired = ExpiredSupport::where('number', $number)->exists();

            if (!$existsInSupport && !$existsInExpired) {
                // Find agent name dynamically
                $agentId = $sale->a_name ?? $sale->agent;
                $agentObj = User::find($agentId);
                $agentName = $agentObj ? $agentObj->name : 'Unknown Agent';

                // Copy data to support safely
                support::create([
                    'name'             => $sale->customer_name,
                    'number'           => $number,
                    'agent_name'       => $agentName,
                    'expiry_date'      => $request->expiry_date,
                    'show_status'      => 'Sale',
                    'assigned_by_name' => Auth::user()->name,
                    'assigned_by_role' => Auth::user()->role,
                    'assigned_date'    => now()->toDateString(),
                    'assigned_to'      => $request->assigned_to,
                ]);
                $copiedCount++;
            } else {
                $duplicateCount++;
            }
        }

        return redirect()->route('viewAgentSaleTable')->with('success', "{$copiedCount} Global Sales successfully copied to Support based on your limit. {$duplicateCount} duplicates were skipped.");
    }
}