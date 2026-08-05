<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\client_number;
use App\Models\customer;
use App\Models\customerNumber;
use App\Models\help;
use App\Models\leave;
use App\Models\not_service;
use App\Models\oldCustomer;
use App\Models\old_number;
use App\Models\renewal;
use App\Models\User as user; // Is se small 'user' bhi kaam karega
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class dashboardController extends Controller
{
    private function getLeaveRequest()
    {
        $leaveRequests = leave::with('user')->where('status', 'pending')->get();
        return $leaveRequests;
    }

    // private function fetchAttendanceData()
    // {
    //     $zk = new ZktecoLib('192.168.100.250', 4370);

    //     if (! $zk->connect()) {
    //         return 'Device not connected';
    //     }

    //     $zk->disableDevice();

    //     $users = collect($zk->getUser());
    //     $logs  = collect($zk->getAttendance());

    //     $zk->enableDevice();
    //     $zk->disconnect();

    //     $validUsers = User::pluck('name')->toArray();
    //     $yesterday  = Carbon::yesterday();

    //     $grouped = [];

    //     foreach ($logs as $log) {

    //         if (! isset($log['id'], $log['timestamp'])) {
    //             continue;
    //         }

    //         $zkUser = $users->first(fn($u) => $u['userid'] == $log['id']);
    //         if (! $zkUser) {
    //             continue;
    //         }

    //         $userName = $zkUser['name'];
    //         if (! in_array($userName, $validUsers)) {
    //             continue;
    //         }

    //         $time = Carbon::parse($log['timestamp']);

    //         // Night Shift (9PM - 6AM)
    //         if ($time->hour >= 21) {
    //             $shiftDate = $time->toDateString();
    //         } elseif ($time->hour < 6) {
    //             $shiftDate = $time->copy()->subDay()->toDateString();
    //         } else {
    //             continue;
    //         }

    //         $grouped[$userName][$shiftDate][] = $time;
    //     }

    //     foreach ($grouped as $userName => $dates) {
    //         foreach ($dates as $date => $times) {

    //             if (Carbon::parse($date)->greaterThan($yesterday)) {
    //                 continue;
    //             }

    //             $times = collect($times)->sort()->values()->all();

    //             if (count($times) < 2) {
    //                 Attendance::updateOrCreate(
    //                     ['employee_name' => $userName, 'date' => $date],
    //                     [
    //                         'check_in'    => null,
    //                         'check_out'   => null,
    //                         'status'      => 'Absent',
    //                         'total_hours' => 0,
    //                     ]
    //                 );
    //                 continue;
    //             }

    //             $checkIn  = $times[0];
    //             $checkOut = $times[count($times) - 1];

    //             if ($checkOut->lessThan($checkIn)) {
    //                 $checkOut->addDay();
    //             }

    //             $hours = round($checkOut->diffInMinutes($checkIn) / 60, 2);

    //             $shiftStart = Carbon::parse($date)->setTime(21, 0, 0);
    //             $lateLimit  = $shiftStart->copy()->addMinutes(15);

    //             $status = $checkIn->gt($lateLimit) ? 'Half Day' : 'Present';

    //             Attendance::updateOrCreate(
    //                 ['employee_name' => $userName, 'date' => $date],
    //                 [
    //                     'check_in'    => $checkIn->format('H:i:s'),
    //                     'check_out'   => $checkOut->format('H:i:s'),
    //                     'total_hours' => $hours,
    //                     'status'      => $status,
    //                 ]
    //             );
    //         }
    //     }
    // }

    public function viewDashboard(Request $req)
    {
        $date      = $req->date ?? now();
        $month     = date('m', strtotime($date));
        $year      = date('Y', strtotime($date));
        $userCount = user::where('role', 'user')->count();
        $oldsale   = customer::whereMonth('regitr_date', $month)
            ->whereYear('regitr_date', $year)
            ->where('status', 'sale')
            ->count();
        $Newsale = oldCustomer::whereMonth('regitr_date', $month)
            ->whereYear('regitr_date', $year)
            ->where('status', 'sale')
            ->count();
        $sale  = $oldsale + $Newsale;
        $trial = customer::whereMonth('regitr_date', $month)
            ->whereYear('regitr_date', $year)
            ->where('status', 'trial')
            ->count();
        $lead = customer::whereMonth('regitr_date', $month)
            ->whereYear('regitr_date', $year)
            ->where('status', 'lead')
            ->count();
        $help            = help::where('status', 'pending')->count();
        $oldCutomerprice = Customer::where('status', 'sale')->whereMonth('regitr_date', $month)
            ->whereYear('regitr_date', $year)
            ->sum('price');
        $NewCustomerprice = oldCustomer::where('status', 'sale')->whereMonth('regitr_date', $month)
            ->whereYear('regitr_date', $year)
            ->sum('price');
        $price                    = $oldCutomerprice + $NewCustomerprice;
        $oldSalecustomerExpriDate = Customer::with('user')
            ->whereDate('regitr_date', today())
            ->get();
        $NewSalecurentSale = OldCustomer::with('user')
            ->whereDate('regitr_date', today())
            ->get();

        $curentSale    = $oldSalecustomerExpriDate->merge($NewSalecurentSale);

        // Total Pending Sale Count
       // $pendingSaleOld = customer::whereMonth('regitr_date', $month)->whereYear('regitr_date', $year)->where('status', 'pending')->count();
       // $pendingSaleNew   = oldCustomer::whereMonth('regitr_date', $month)->whereYear('regitr_date', $year)->where('status', 'pending')->count();
       // $totalPendingSale = $pendingSaleOld + $pendingSaleNew;

       // Total Pending Sale Count (All Time)
        $pendingSaleOld  = Customer::where('status', 'pending')->count();
        $pendingSaleNew  = OldCustomer::where('status', 'pending')->count();
        $totalPendingSale = $pendingSaleOld + $pendingSaleNew;

        // Total Numbers Count //
       /* $totalClientNumbers = client_number::count();
        $totalCustomerNumbers = customerNumber::count();
        $totalOldNumbers = old_number::count(); */

        // Total Regional Wise Count From Client_numbers and old_numbers and customer_numbers tables //
        $totalUsNumbers = client_number::where('region','us')->count() + old_number::where('region', 'us')->count() + customerNumber::where('region', 'us')->count();
        $totalUkNumbers = client_number::where('region','uk')->count() + old_number::where('region', 'uk')->count() + customerNumber::where('region', 'uk')->count();
        $totalAusNumbers = client_number::where('region','aus')->count() + old_number::where('region', 'aus')->count() + customerNumber::where('region', 'aus')->count();

        // --- NEW SEARCH LOGIC (Separate & Independent) --- //
        $searchResultsLeads = collect();
        $searchResultsSales = collect();
        $searchResultsPendingSales = collect();
        $searchResultsTrials = collect();
        $searchResultsNumbers = collect();

        if ($req->has('search') && !empty($req->search)) {
            $search = $req->search;

            // 1. Leads Search
            $searchResultsLeads = \App\Models\customer::with('user')
                ->where('status', 'lead')
                ->where(function($query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                          ->orWhere('customer_number', 'like', "%{$search}%")
                          ->orWhere('customer_email', 'like', "%{$search}%")
                          ->orWhereHas('user', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          });
                })->get();

            // 2. Sales Search (Dono tables me)
            $salesOld = \App\Models\customer::with('user')
                ->where('status', 'sale')
                ->where(function($query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                          ->orWhere('customer_number', 'like', "%{$search}%")
                          ->orWhere('customer_email', 'like', "%{$search}%")
                          ->orWhereHas('user', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          });
                })->get();

            $salesNew = \App\Models\oldCustomer::with('user')
                ->where('status', 'sale')
                ->where(function($query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                          ->orWhere('customer_number', 'like', "%{$search}%")
                          ->orWhere('customer_email', 'like', "%{$search}%")
                          ->orWhereHas('user', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          });
                })->get();

            $searchResultsSales = $salesOld->merge($salesNew);

            // 2.5 Pending Sales Search
            $pendingSalesOld = \App\Models\customer::with('user')
                ->where('status', 'pending')
                ->where(function($query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                          ->orWhere('customer_number', 'like', "%{$search}%")
                          ->orWhere('customer_email', 'like', "%{$search}%")
                          ->orWhereHas('user', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          });
                })->get();

            $pendingSalesNew = \App\Models\oldCustomer::with('user')
                ->where('status', 'pending')
                ->where(function($query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                          ->orWhere('customer_number', 'like', "%{$search}%")
                          ->orWhere('customer_email', 'like', "%{$search}%")
                          ->orWhereHas('user', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          });
                })->get();

            $searchResultsPendingSales = $pendingSalesOld->merge($pendingSalesNew);

            // 3. Trials Search
            $searchResultsTrials = \App\Models\customer::with('user')
                ->where('status', 'trial')
                ->where(function($query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                          ->orWhere('customer_number', 'like', "%{$search}%")
                          ->orWhere('customer_email', 'like', "%{$search}%")
                          ->orWhereHas('user', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          });
                })->get();

            // 4. Distribute Numbers (CustomerNumber) Search
            $searchResultsNumbers = \App\Models\customerNumber::with('user')
                ->where(function($query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                          ->orWhere('customer_number', 'like', "%{$search}%")
                          ->orWhereHas('user', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          });
                })->get();
        }

        ///// end here new logic code ////

        $leaveRequests = $this->getLeaveRequest();
        return view('admin.dashbord', compact([
            'userCount',
            'sale',
            'trial',
            'lead',
            'price',
            'help',
            'totalPendingSale',  // <-- New Add Kiya hy Pending sale count krwany ky liye.
            'totalUsNumbers', /// <-- New Add kiya hy  Total Numbers Count krwany ky liye. Client_numbers table ma sy count horhy hen.
            'totalUkNumbers',  /// <-- New Add kiya hy  Total Numbers Count krwany ky liye. Customer_numbers table ma sy count horhy hen.
            'totalAusNumbers', /// <-- New Add kiya hy  Total Numbers Count krwany ky liye. old_numbers table ma sy count horhy hen.
            'curentSale',
            'leaveRequests',
            'searchResultsLeads',    // <-- NAYA ADD HUA
            'searchResultsSales',    // <-- NAYA ADD HUA
            'searchResultsPendingSales', // <-- NAYA ADD HUA
            'searchResultsTrials',   // <-- NAYA ADD HUA
            'searchResultsNumbers'   // <-- NAYA ADD HUA
        ]));
    }

////// Add New code start view Agent Sale Table //////

     public function viewAgentSaleTable()
    {
        DB::statement('SET SESSION group_concat_max_len = 1000000');
        $customers = Customer::select(
            'customers.a_name',
            DB::raw('COUNT(*) as total'),
            DB::raw('MAX(customers.regitr_date) as last_date'),
            'users.name as user_name',
            'users.id as user_id',
            DB::raw('GROUP_CONCAT(CONCAT_WS(" ", customers.customer_name, customers.customer_number, customers.customer_email) SEPARATOR " ") as search_data')
        )
            ->leftJoin('users', 'users.id', '=', 'customers.a_name')
            ->where('customers.status', 'sale')
            ->whereNotNull('users.id')
            ->groupBy('customers.a_name', 'users.name', 'users.id')
            ->orderBy('last_date', 'desc')
            ->get();

        return view('admin.agent_sale', compact('customers'));
    }

//// New code End here  ////////

    public function viewSaleTable(Request $req, string $id)
    {
        $month = date('m', strtotime($req->date));
        $year  = date('Y', strtotime($req->date));
        if ($req->date == null) {
            $oldcustomers = Customer::with('user')
                ->where('status', 'sale')
                ->orderBy('regitr_date', 'asc')
                ->where('a_name', $id)
                ->get();
            $Newcustomers = oldCustomer::with('user')
                ->where('status', 'sale')
                ->orderBy('regitr_date', 'asc')
                ->where('agent', $id)
                ->get();
        } else {
            $oldcustomers = Customer::with('user')
                ->where('status', 'sale')
                ->whereMonth('regitr_date', $month)
                ->whereYear('regitr_date', $year)
                ->where('a_name', $id)
                ->get();
            $Newcustomers = oldCustomer::with('user')
                ->where('status', 'sale')
                ->whereMonth('regitr_date', $month)
                ->whereYear('regitr_date', $year)
                ->where('agent', $id)
                ->get();
        }
        $customers = $oldcustomers->merge($Newcustomers);
        return view('admin.sale_table', compact('customers'));
    }

    /// Start New Single sale Distribute ///

    public function distributeSingleSaleForm(string $id)
    {
        $oldcustomer = Customer::with('user')->find($id);
        $newcustomer = OldCustomer::with('user')->find($id);
        $customer = $oldcustomer ? $oldcustomer : $newcustomer;

        $agents = User::where('role', 'user')->where('id', '!=', $customer->a_name)->get();
        return view('admin.dis_single_sale', compact(['agents', 'customer']));
    }

    public function updateSingleSaleAgent(Request $req, string $id)
    {
        $req->validate([
            'agent' => 'required',
        ]);
        
        $oldcustomer = Customer::find($id);
        $newcustomer = OldCustomer::find($id);
        $customer = $oldcustomer ? $oldcustomer : $newcustomer;

        $newAgent = User::find($req->agent);

        if ($customer->getTable() == 'customers') {
            // Previous agent ka fresh naam users table se lo (user_name pe rely mat karo)
            $prevAgent = User::find($customer->a_name);
            $customer->previous_agent_name = $prevAgent ? $prevAgent->name : ($customer->user_name ?? null);
            $customer->a_name = $newAgent->id;
            $customer->user_name = $newAgent->name;
        } else {
            $prevAgent = User::find($customer->agent);
            $customer->previous_agent_name = $prevAgent ? $prevAgent->name : null;
            $customer->agent = $newAgent->id;
            $customer->agent_name = $newAgent->name;
        }
        $customer->save();

        // return redirect()->back()->with(['success' => 'Sale Distributed Successfully']);
        return redirect()->route('viewAgentSaleTable')->with('success', 'Sale Distributed Successfully');
    }

    /// End New Single sale Distribute ///

    /// Start New Multiple Sale Distribute ///

public function distributeMultipleSaleForm(Request $request)
    {
        // Agar page GET request se load ho raha hai (jaise validation fail hone par reload)
        // toh hum session se customer_ids nikalenge, warna request se.
        $customer_ids = $request->customer_ids;

        if (!$customer_ids && session()->has('old_customer_ids')) {
            $customer_ids = session()->get('old_customer_ids');
        }

        // Agar dono jagah se IDs nahi milin (yani user direct URL enter kar ke aya hai)
        if (!$customer_ids) {
            return redirect()->route('viewAgentSaleTable')->with('error', 'Please select at least one sale to distribute.');
        }

        // IDs ko session mein save kar lein taake validation fail hone par bhi mehfooz rahein
        session()->flash('old_customer_ids', $customer_ids);

        $selected_customers = collect();
        
        foreach($customer_ids as $id) {
            $customer = Customer::with('user')->find($id);
            if(!$customer) {
                $customer = OldCustomer::with('user')->find($id);
            }
            if($customer) {
                $selected_customers->push($customer);
            }
        }

        $agents = User::where('role', 'user')->get();

        return view('admin.dis_multiple_sale', compact(['agents', 'selected_customers']));
    }

    public function saveMultipleSaleDistribution(Request $request)
    {
        // 1. Validation 
        $request->validate([
            'customer_ids' => 'required|array|min:1',
            'agent' => 'required|exists:users,id',
        ], [
            'agent.required' => 'Please select an agent first.',
            'customer_ids.required' => 'Please select at least one sale to distribute.'
        ]);

        $newAgent = User::find($request->agent);

        // 2. Customers aur OldCustomers ki IDs ko alag alag array mein store karein
        $customerIds = [];
        $oldCustomerIds = [];

        foreach ($request->customer_ids as $id) {
            // Check karein ke yeh id `customers` table ki hai ya `old_customers` table ki
            if (Customer::find($id)) {
                $customerIds[] = $id;
            } elseif (OldCustomer::find($id)) {
                $oldCustomerIds[] = $id;
            }
        }

        // 3. New Customers (Customer Model) ko update karein
        if (!empty($customerIds)) {
            // Pehle previous agent name save karo - fresh naam users table se lo
            Customer::whereIn('id', $customerIds)->each(function ($cust) use ($newAgent) {
                $prevAgent = User::find($cust->a_name);
                $prevName = $prevAgent ? $prevAgent->name : ($cust->user_name ?? null);
                $cust->update([
                    'previous_agent_name' => $prevName,
                    'a_name' => $newAgent->id,
                    'user_name' => $newAgent->name,
                ]);
            });
        }

        // 4. Old Customers (OldCustomer Model) ko update karein
        if (!empty($oldCustomerIds)) {
            OldCustomer::whereIn('id', $oldCustomerIds)->each(function ($cust) use ($newAgent) {
                $prevAgent = User::find($cust->agent);
                $prevName = $prevAgent ? $prevAgent->name : null;
                $cust->update([
                    'previous_agent_name' => $prevName,
                    'agent' => $newAgent->id,
                    'agent_name' => $newAgent->name,
                ]);
            });
        }

        // Flash session ko clear karein
        session()->forget('old_customer_ids');

        return redirect()->route('viewAgentSaleTable')->with('success', 'Multiple Sales successfully distributed to the selected agent.');
    }

    /// End New Multiple Sale Distribute ///

    public function cutomerUPdateSaleDetailFormVIew(string $id)
    {
        $oldcustomers = customer::find($id);
        $Newcustomers = oldcustomer::find($id);
        $customer     = null;
        if ($oldcustomers) {
            $customer = $oldcustomers;
        } else {
            $customer = $Newcustomers;
        }
        return view('admin.edit_agent_sale', compact('customer'));
    }

    public function cutomerUPdateDetailSaleStore(Request $req, string $id)
    {
        $req->validate([
            'customer_name'   => 'required|string',
            'customer_number' => 'required|numeric',
            'price'           => 'required|numeric',
            'remarks'         => 'required',
            'status'          => 'required',
            'expiry_date'     => 'required|numeric',
        ]);

        $customer = customer::find($id);

        $email = $req->customer_email ?: 'No Email';

        $expiryMonths = (int) $req->expiry_date;
        $expiryDate   = Carbon::now()->addMonths($expiryMonths)->format('Y-m-d');

        $customer->update([
            'customer_name'   => $req->customer_name,
            'customer_email'  => $email,
            'customer_number' => $req->customer_number,
            'price'           => $req->price,
            'remarks'         => $req->remarks,
            'status'          => $req->status,
            'make_address'    => $req->make_address,
            'regitr_date'     => $req->date,
            'expiry_date'     => $expiryDate,
        ]);

        return redirect()->route('viewPendingSale')->with(['success' => 'Customer Detail Updated Successfully']);
    }

    public function deleteSaleCustomerDetails(string $id)
    {
        $oldcustomer = customer::find($id);
        $newcustomer = oldcustomer::find($id);
        $customer    = null;
        if ($oldcustomer) {
            $customer = $oldcustomer;
        } else {
            $customer = $newcustomer;
        }
        $customer->delete();
        return redirect()->route('viewPendingSale')->with(['success' => 'Customer Detail Deleted Successfuly']);
    }

 ////// start new add code view Agent Leadl Table //////

        public function viewAgentLeadlTable()
    {
        DB::statement('SET SESSION group_concat_max_len = 1000000');
        $customers = Customer::with('user')
            ->select(
                'a_name',
                DB::raw('COUNT(*) as total'),
                DB::raw('MAX(regitr_date) as last_date'),
                DB::raw('GROUP_CONCAT(CONCAT_WS(" ", customer_name, customer_number, customer_email) SEPARATOR " ") as search_data')
            )
            ->where('status', 'lead')
            ->groupBy('a_name')
            ->orderBy('last_date', 'desc')
            ->get();
        return view('admin.agent_lead', compact('customers'));
    }

    /////// end new add code //////

    public function viewleadtable(string $id)
    {
        $customers = Customer::with('user')
            ->where('status', 'lead')
            ->orderByRaw('MONTH(regitr_date) asc')
            ->where('a_name', $id)
            ->get();
        return view('admin.lead_table', compact('customers'));
    }

    public function distributeLeadsForm(string $id)
    {
        $agentName = Customer::select('a_name')->with('user')->where('status', 'lead')->groupBy('a_name')->where('a_name', '!=', $id)->get();
        $agentID   = user::find($id);
        $customer  = Customer::where('status', 'lead')->where('a_name', $id)->get();
        return view('admin.dis_lead', compact(['agentName', 'agentID']));
    }

    //// old logic for all lead Distribute without prev_agent name show ////
    /*
    public function updateLeadAgent(Request $req, string $id)
    {
        $OldLeadAgent = customer::where('status', 'lead')->where('a_name', $id)->take($req->number)->get();
        $disLeadAgent = customer::where('status', 'lead')->where('a_name', $req->agent)->take($req->number)->get();
        foreach ($OldLeadAgent as $oldAgent) {
            foreach ($disLeadAgent as $newAgent) {
                $newAgentID   = $newAgent->a_name;
                $newAgentName = $newAgent->user_name;

                $oldAgent->a_name    = $newAgentID;
                $oldAgent->user_name = $newAgentName;

                $oldAgent->save();
                $newAgent->save();
            }
        }
        return redirect()->route('viewAgentLeadlTable')->with(['success' => 'Distribute Lead Successfuly']);
    }
        */

    /// New logic for All Lead Distrubute With prev_agent name //////

    public function updateLeadAgent(Request $req, string $id)
{
    // Old Agent se $req->number ke mutabiq leads fetch karein
    $OldLeadAgent = Customer::where('status', 'lead')
                            ->where('a_name', $id)
                            ->take($req->number)
                            ->get();

    // Reassignment se pehle purane agent ka fresh name fetch kar lein
    $prevAgent = User::find($id);
    $prevAgentName = $prevAgent ? $prevAgent->name : null;

    // Target Agent ki details fetch karein
    $newAgent = User::find($req->agent);

    if ($newAgent && $OldLeadAgent->isNotEmpty()) {
        foreach ($OldLeadAgent as $lead) {
            // Reassign karne se pehle purane agent ka naam save karein
            $lead->previous_agent_name = $prevAgentName ?? ($lead->user_name ?? null);
            $lead->a_name = $newAgent->id;
            $lead->user_name = $newAgent->name;
            $lead->save();
        }
    }

    return redirect()->route('viewAgentLeadlTable')->with(['success' => 'Distribute Lead Successfully']);
}

    /// End Here //////

    /// Start Single Lead Distribute Code ///

    public function distributeSingleLeadForm(string $id)
    {
        $customer = Customer::with('user')->find($id);
        $agents = User::where('role', 'user')->where('id', '!=', $customer->a_name)->get();
        return view('admin.dis_single_lead', compact(['agents', 'customer']));
    }

    public function updateSingleLeadAgent(Request $req, string $id)
    {
        $req->validate([
            'agent' => 'required',
        ]);
        
        $customer = Customer::find($id);
        $newAgent = User::find($req->agent);

        // Save previous agent name before reassigning
        $previousAgentName = $customer->user ? $customer->user->name : $customer->user_name;

        $customer->a_name = $newAgent->id;
        $customer->user_name = $newAgent->name;
        $customer->previous_agent_name = $previousAgentName;
        $customer->save();

        if ($newAgent) {
            $message = "New lead assigned to you:\n";
            $message .= "• Name: {$customer->customer_name}";
            if (!empty($customer->customer_number)) {
                $message .= " | Number: {$customer->customer_number}";
            }
            $newAgent->notify(new \App\Notifications\LeadDistributedNotification($message));
        }

        return redirect()->route('viewAgentLeadlTable')->with(['success' => 'Lead Distributed Successfully']);
    }

    /// End Single Lead Distribute Code ///

    /// start Multiple lead distribute code ///

    public function distributeMultipleLeadForm(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array|min:1',
        ], [
            'customer_ids.required' => 'Please select at least one lead to distribute.',
        ]);

        $selected_customers = Customer::with('user')->whereIn('id', $request->customer_ids)->get();
        // Sirf user role wale agents ko fetch karna
        $agents = User::where('role', 'user')->get();

        return view('admin.dis_multiple_lead', compact(['agents', 'selected_customers']));
    }

    public function saveMultipleLeadDistribution(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array|min:1',
            'agent' => 'required|exists:users,id',
        ], [
            'agent.required' => 'Please select an agent first.',
        ]);

        // Get customer names + their previous agent before updating
        $customersToUpdate = Customer::with('user')->whereIn('id', $request->customer_ids)->get();

        foreach ($customersToUpdate as $c) {
            $prevName = $c->user ? $c->user->name : $c->user_name;
            $c->previous_agent_name = $prevName;
            $c->a_name = $request->agent;
            $c->save();
        }

        $agent = User::find($request->agent);
        if ($agent) {
            $customers = Customer::whereIn('id', $request->customer_ids)->get();
            $count = $customers->count();
            $namesList = $customers->map(function($c) {
                $entry = "• {$c->customer_name}";
                if (!empty($c->customer_number)) {
                    $entry .= " | {$c->customer_number}";
                }
                return $entry;
            })->implode('\n');
            $message = "$count new lead(s) assigned to you:\n" . $namesList;
            $agent->notify(new \App\Notifications\LeadDistributedNotification($message));
        }

        // Yeh 'viewAgentLeadlTable' aapki dashboard route se liya gaya hai jo table dikhata hai
        return redirect()->route('viewAgentLeadlTable')->with('success', 'Leads successfully distributed to the selected agent.');
    }

    /// End Multiple lead distribute code ///

    public function cutomerUPdateDetailFormVIew(string $id)
    {
        $customer = customer::find($id);
        return view('admin.edit_agent_lead', compact('customer'));
    }

    public function cutomerUPdateDetailStore(Request $req, string $id)
    {
        $req->validate([
            'customer_name'   => 'required|string',
            'customer_number' => 'required|numeric',
            'price'           => 'required|numeric',
            'remarks'         => 'required',
            'status'          => 'required',
        ]);

        $customer = customer::find($id);
        $email    = $req->customer_email ?: 'No Email';
        $customer->update([
            'customer_name'   => $req->customer_name,
            'customer_email'  => $email,
            'customer_number' => $req->customer_number,
            'price'           => $req->price,
            'remarks'         => $req->remarks,
            'status'          => $req->status,
            'regitr_date'     => $req->date,
        ]);
        $customer->make_address = $req->make_address;
        $customer->regitr_date  = $req->date;
        $customer->save();

        return redirect()->route('viewAgentLeadlTable')->with(['success' => 'Customer Detail Updated Successfuly']);
    }

    public function deleteLeadCustomerDetails(string $id)
    {
        $customer = customer::find($id);
        $customer->delete();
        return redirect()->route('viewAgentLeadlTable')->with(['success' => 'Customer Detail Deleted Successfuly']);
    }

////// New add code start Agent Trial Table //////////

 public function viewAgentTrialTable()
    {
        DB::statement('SET SESSION group_concat_max_len = 1000000');
        $customers = Customer::with('user')
            ->select(
                'a_name',
                DB::raw('COUNT(*) as total'),
                DB::raw('MAX(regitr_date) as last_date'),
                DB::raw('GROUP_CONCAT(CONCAT_WS(" ", customer_name, customer_number, customer_email) SEPARATOR " ") as search_data')
            )
            ->where('status', 'trial')
            ->groupBy('a_name')
            ->orderBy('last_date', 'desc')
            ->get();

        return view('admin.agent_trial', compact('customers'));
    }

////// New Code End Here  ////////

    public function viewtrialtable(string $id)
    {
        $customers = Customer::with('user')
            ->where('status', 'trial')
            ->orderByRaw('MONTH(regitr_date) asc')
            ->where('a_name', $id)
            ->get();
        return view('admin.trial_table', compact('customers'));
    }

    public function distributeTrialsForm(string $id)
    {
        $agentName = Customer::select('a_name')->with('user')->where('status', 'trial')->groupBy('a_name')->where('a_name', '!=', $id)->get();
        $agentID   = user::find($id);
        $customer  = Customer::where('status', 'trial')->where('a_name', $id)->get();
        return view('admin.dis_trial', compact(['agentName', 'agentID']));
    }

    /// Old Trial Distribute Code without prev_agent_name show  ///
    /*
    public function updateTrialAgent(Request $req, string $id)
    {
        $OldLeadAgent = customer::where('status', 'trial')->where('a_name', $id)->take($req->number)->get();
        $disLeadAgent = customer::where('status', 'trial')->where('a_name', $req->agent)->take($req->number)->get();
        foreach ($OldLeadAgent as $oldAgent) {
            foreach ($disLeadAgent as $newAgent) {
                $newAgentID   = $newAgent->a_name;
                $newAgentName = $newAgent->user_name;

                $oldAgent->a_name    = $newAgentID;
                $oldAgent->user_name = $newAgentName;

                $oldAgent->save();
                $newAgent->save();
            }
        }
        return redirect()->route('viewAgentTrialTable')->with(['success' => 'Distribute Trial Successfuly']);
    }
        */
    
    /// End Here trial_distribute old logic ///

    /// Add New Logic Trial Distribute With prev_agent_name show ////

public function updateTrialAgent(Request $req, string $id)
{
    // Purane Agent aur Target Agent ko Users table se fetch karein
    $prevAgent = User::find($id);
    $prevAgentName = $prevAgent ? $prevAgent->name : null;

    $newAgent = User::find($req->agent);

    // Purane Agent se requested count ($req->number) ke mutabiq trials fetch karein
    $OldLeadAgent = Customer::where('status', 'trial')
                            ->where('a_name', $id)
                            ->take($req->number)
                            ->get();

    if ($newAgent && $OldLeadAgent->isNotEmpty()) {
        foreach ($OldLeadAgent as $oldAgent) {
            // Transfer se pehle purane agent ka naam save karein
            $oldAgent->previous_agent_name = $prevAgentName ?? ($oldAgent->user_name ?? null);
            
            // Naye agent ki details update karein 
            $oldAgent->a_name    = $newAgent->id;
            $oldAgent->user_name = $newAgent->name;

            $oldAgent->save();
        }
    }

    return redirect()->route('viewAgentTrialTable')->with(['success' => 'Distribute Trial Successfully']);
}


    /// End Here New Logic Trial Distribute code  ///

    /// Start Single Trial Distribute Code ///

    public function distributeSingleTrialForm(string $id)
    {
        $customer = Customer::with('user')->find($id);
        $agents = User::where('role', 'user')->where('id', '!=', $customer->a_name)->get();
        return view('admin.dis_single_trial', compact(['agents', 'customer']));
    }

    public function updateSingleTrialAgent(Request $req, string $id)
    {
        $req->validate([
            'agent' => 'required',
        ]);
        
        $customer = Customer::find($id);
        $newAgent = User::find($req->agent);

        // Previous agent ka fresh naam users table se lo
        $prevAgent = User::find($customer->a_name);
        $customer->previous_agent_name = $prevAgent ? $prevAgent->name : ($customer->user_name ?? null);

        $customer->a_name = $newAgent->id;
        $customer->user_name = $newAgent->name;
        $customer->save();

        //return redirect()->back()->with(['success' => 'Trial Distributed Successfully']);
        return redirect()->route('viewAgentTrialTable')->with(['success' => 'Distribute Trial Successfuly']);
    }

    /// End Single Trial Distribute Code ///

    /// Start Multiple Trial Distribute Code ///

    public function distributeMultipleTrialForm(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array|min:1',
        ], [
            'customer_ids.required' => 'Please select at least one trial to distribute.',
        ]);

        // Get selected customers
        $selected_customers = Customer::with('user')->whereIn('id', $request->customer_ids)->get();
        
        // Get agents
        $agents = User::where('role', 'user')->get();

        return view('admin.dis_multiple_trial', compact(['agents', 'selected_customers']));
    }

    public function saveMultipleTrialDistribution(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array|min:1',
            'agent' => 'required|exists:users,id',
        ], [
            'agent.required' => 'Please select an agent first.',
        ]);

        $newAgent = User::find($request->agent);

        // Update all selected trials with new agent ID and Name
        Customer::whereIn('id', $request->customer_ids)->each(function ($cust) use ($newAgent) {
            $prevAgent = User::find($cust->a_name);
            $prevName = $prevAgent ? $prevAgent->name : ($cust->user_name ?? null);
            $cust->update([
                'previous_agent_name' => $prevName,
                'a_name' => $newAgent->id,
                'user_name' => $newAgent->name,
            ]);
        });

        return redirect()->route('viewAgentTrialTable')->with('success', 'Multiple Trials successfully distributed to the selected agent.');
    }

    /// End Multiple Trial Distribute Code ///

    public function cutomerUPdateTrialDetailFormVIew(string $id)
    {
        $customer = customer::find($id);

        return view('admin.edit_agent_trial', compact('customer'));
    }

    public function cutomerUPdateDetailTrialStore(Request $req, string $id)
    {
        $req->validate([
            'customer_name'   => 'required|string',
            'customer_number' => 'required|numeric',
            'price'           => 'required|numeric',
            'remarks'         => 'required',
            'status'          => 'required',
        ]);

        $customer = customer::find($id);
        $email    = $req->customer_email ?: 'No Email';
        $customer->update([
            'customer_name'   => $req->customer_name,
            'customer_email'  => $email,
            'customer_number' => $req->customer_number,
            'price'           => $req->price,
            'remarks'         => $req->remarks,
            'status'          => $req->status,
            'regitr_date'     => $req->date,
        ]);

        $customer->make_address = $req->make_address;
        $customer->regitr_date  = $req->date;
        $customer->save();

        return redirect()->route('viewAgentTrialTable')->with(['success' => 'Customer Detail Update Successfuly']);
    }

    public function deleteTrialCustomerDetails(string $id)
    {
        $customer = customer::find($id);
        $customer->delete();
        return redirect()->route('viewAgentTrialTable')->with(['success' => 'Customer Detail Deleted Successfuly']);
    }

    public function updateCustomerStatus(string $id)
    {
        $customer                = customer::find($id);
        $customer->status        = 'sale';
        $customer->active_status = null;
        $customer->make_address  = null;
        $customer->start_date    = null;
        $customer->end_date      = null;
        $customer->date_count    = null;
        $customer->save();
        return redirect()->route('viewAgentTrialTable')->with(['success' => 'Customer Detail Updated Successfuly']);
    }

    public function deleteCustomerDetails(string $id)
    {
        $customer = customer::find($id);
        $customer->delete();
        return redirect()->route('viewAgentTrialTable')->with(['success' => 'Customer Cencel Successfuly']);
    }
    public function viewHelpRequestTableDashboard()
    {
        // $helpRequest = help::all();
        $helpRequest = help::latest()->take(50)->get();
        return view('admin.helpTable', compact('helpRequest'));
    }

    public function downHelpRequestStatus(string $id)
    {
        $help = help::find($id);
        return view('admin.updateHelpStatus', compact('help'));
    }
    public function updateHelpRequeststatus(Request $req, string $id)
    {
        $req->validate([
            'remarks' => 'required',
            'status'  => 'required',
        ]);
        $help          = help::find($id);
        $help->status  = $req->status;
        $help->remarks = $req->remarks;
        $help->save();
        return redirect()->route('viewHelpRequestTableDashboard')->with(['success' => 'Help Request Updated Successfuly']);
    }

    public function viewTrialDaysForm(string $id)
    {
        $customer = customer::find($id);
        return view('admin.trial_Days', compact('customer'));
    }

    public function updateStatusCustomerTrial()
    {
        $customers = Customer::where('active_status', 'active')->get();
        foreach ($customers as $customer) {
            if ($customer->date_count > 0) {
                $customer->date_count = (int) $customer->date_count - 1;

                if ($customer->date_count == 0) {
                    $customer->active_status = 'inactive';
                }

                $customer->save();
            }
        }

        return response()->json(['status' => 'Update complete']);
    }

    public function viewupdateSaleCustomerStatus(string $id)
    {
        $customer = customer::find($id);
        return view('admin.update_sale_days', compact('customer'));
    }
    public function updateSaleCustomerStatus(Request $req, string $id)
    {
        $req->validate([
            'make_address' => 'required',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = new \DateTime($req->start_date);
        $endDate   = new \DateTime($req->end_date);

        $interval                = $startDate->diff($endDate);
        $daysDifference          = $interval->days;
        $customer                = Customer::find($id);
        $customer->active_status = 'active';
        $customer->make_address  = $req->make_address;
        $customer->start_date    = $req->start_date;
        $customer->end_date      = $req->end_date;
        $customer->date_count    = $daysDifference;
        $customer->save();
        return redirect()->route('viewAgentSaleTable')->with(['success' => 'Customer Sale Days Is Start Now']);
    }

    public function viewSaleDaysForm(string $id)
    {
        $customer = customer::find($id);
        return view('admin.sale_days', compact('customer'));
    }

    public function addSaleCustomerStatus(Request $req, string $id)
    {
        $req->validate([
            'make_address' => 'required',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = new \DateTime($req->start_date);
        $endDate   = new \DateTime($req->end_date);

        $interval                = $startDate->diff($endDate);
        $daysDifference          = $interval->days;
        $customer                = Customer::find($id);
        $customer->active_status = 'active';
        $customer->make_address  = $req->make_address;
        $customer->start_date    = $req->start_date;
        $customer->end_date      = $req->end_date;
        $customer->date_count    = $daysDifference;
        $customer->save();
        return redirect()->route('viewAgentSaleTable')->with(['success' => 'Customer Sale Days Is Start Now']);
    }

    public function viewCustomerNumber()
    {
        $allCustomerNumber = CustomerNumber::with('user')->select('agent', DB::raw('count(*) as total'))
            ->groupBy('agent')
            ->get();
        return view('admin.customer_number', compact('allCustomerNumber'));
    }

    public function viewCustomerNumberForm()
    {
        $agentName             = User::select('name', 'id')->get();
        $allClientNumbersCount = client_number::count();
        return view('admin.add_customer_number', compact(['agentName', 'allClientNumbersCount']));
    }

    public function viewNumbersTable()
    {
        $numbers = client_number::get();
        return view('admin.number', compact('numbers'));
    }

    public function viewAddNumbersForm()
    {
        return view('admin.add_number');
    }

    //////// New Code logic For +1 +44 +61 Region Number allow ////////
    /* public function storeNumbers(Request $req)
    {
        $customerNumberArray = preg_split('/[,\r\n]+|\s{2,}/', $req->customerNumber);

        $customerNumberArray = array_map('trim', $customerNumberArray);
        $customerNumberArray = array_filter($customerNumberArray);
        $customerNumberArray = array_unique($customerNumberArray);

        $customerNumberArray = array_map(function ($num) {
            $num = preg_replace('/[^0-9+]/', '', $num);

            if (substr($num, 0, 2) === '+1') {
                return substr($num, 2);
            }

            return null;
        }, $customerNumberArray);

        $customerNumberArray = array_filter($customerNumberArray);

        $existingNumbers = array_merge(
            old_number::whereIn('number', $customerNumberArray)->pluck('number')->toArray(),
            client_number::whereIn('number', $customerNumberArray)->pluck('number')->toArray(),
            customerNumber::whereIn('customer_number', $customerNumberArray)->pluck('customer_number')->toArray()
        );

        $newNumbers = array_diff($customerNumberArray, $existingNumbers);

        if (empty($newNumbers)) {
            return redirect()->route('viewNumbersTable')
                ->with(['error' => 'All numbers already exist in the records.']);
        }

        foreach ($newNumbers as $number) {
            client_number::create([
                'number' => $number,
                'date'   => now(),
            ]);
        }

        return redirect()->route('viewNumbersTable')
            ->with(['success' => 'New numbers added successfully']);
    } */

     ////////// New Code For +1 +44 +61 Number Add Logic Start Here ////////

   /*  public function storeNumbers(Request $req)
    {
        $customerNumberArray = preg_split('/[,\r\n]+|\s{2,}/', $req->customerNumber);

        $customerNumberArray = array_map('trim', $customerNumberArray);
        $customerNumberArray = array_filter($customerNumberArray);
        $customerNumberArray = array_unique($customerNumberArray);

        $customerNumberArray = array_map(function ($num) {
            $num = preg_replace('/[^0-9+]/', '', $num);

            // Sirf +1, +44, aur +61 ko allow karain aur prefix remove kiye bina save karain
            if (str_starts_with($num, '+1') || str_starts_with($num, '+44') || str_starts_with($num, '+61')) {
                return $num;
            }

            // Agar user bina '+' k add kary tou autodetect kar k '+' add kar dain (Optional safety)
            if (str_starts_with($num, '1') && strlen($num) == 11) { return '+' . $num; }
            if (str_starts_with($num, '44') && strlen($num) >= 12) { return '+' . $num; }
            if (str_starts_with($num, '61') && strlen($num) >= 11) { return '+' . $num; }

            // Iske ilawa koi number store nahi hoga
            return null;
        }, $customerNumberArray);

        $customerNumberArray = array_filter($customerNumberArray);

        $existingNumbers = array_merge(
            old_number::whereIn('number', $customerNumberArray)->pluck('number')->toArray(),
            client_number::whereIn('number', $customerNumberArray)->pluck('number')->toArray(),
            customerNumber::whereIn('customer_number', $customerNumberArray)->pluck('customer_number')->toArray()
        );

        $newNumbers = array_diff($customerNumberArray, $existingNumbers);

        if (empty($newNumbers)) {
            return redirect()->route('viewNumbersTable')
                ->with(['error' => 'All numbers already exist in the records or invalid format.']);
        }

        foreach ($newNumbers as $number) {
            client_number::create([
                'number' => $number,
                'date'   => now(),
            ]);
        }

        return redirect()->route('viewNumbersTable')
            ->with(['success' => 'New specific numbers added successfully']);
    } */

    public function storeNumbers(Request $req)
    {
        $customerNumberArray = preg_split('/[,\r\n]+|\s{2,}/', $req->customerNumber);
        $customerNumberArray = array_map('trim', $customerNumberArray);
        $customerNumberArray = array_filter($customerNumberArray);
        $customerNumberArray = array_unique($customerNumberArray);

        // Nayi array jismein stripped_number as Key aur region as Value hogi
        $validNumbersMap = [];

        foreach ($customerNumberArray as $num) {
            // Sirf digits aur '+' ko allow karna
            $cleanNum = preg_replace('/[^0-9+]/', '', $num);

            if (str_starts_with($cleanNum, '+1')) {
                $validNumbersMap[substr($cleanNum, 2)] = 'us';
            } elseif (str_starts_with($cleanNum, '+44')) {
                $validNumbersMap[substr($cleanNum, 3)] = 'uk';
            } elseif (str_starts_with($cleanNum, '+61')) {
                $validNumbersMap[substr($cleanNum, 3)] = 'aus';
            }
        }

        // Sirf clean kiye hue numbers ki list database check k liye
        $strippedNumbersList = array_keys($validNumbersMap);

        // Database Check
        $existingNumbers = array_merge(
            old_number::whereIn('number', $strippedNumbersList)->pluck('number')->toArray(),
            client_number::whereIn('number', $strippedNumbersList)->pluck('number')->toArray(),
            customerNumber::whereIn('customer_number', $strippedNumbersList)->pluck('customer_number')->toArray()
        );

        // Jo numbers database me nahi hain
        $newNumbers = array_diff($strippedNumbersList, $existingNumbers);

        if (empty($newNumbers)) {
            return redirect()->route('viewNumbersTable')
                ->with(['error' => 'All numbers already exist in the records or invalid format.']);
        }

        // Database me save karna (Number aur Region dono)
        foreach ($newNumbers as $number) {
            client_number::create([
                'number' => $number,
                'region' => $validNumbersMap[$number], // Region humari map array se aayega
                'date'   => now(),
            ]);
        }

        return redirect()->route('viewNumbersTable')
            ->with(['success' => 'New specific numbers added successfully']);
    }

     ///////// End Region Number +1 +44 +61 Add Logic /////////////////

    public function storeCustomerNumbers(Request $req)
    {
        $req->validate([
            'agent'  => 'required',
            'date'   => 'required|date',
            'number' => 'required|integer|min:1',
        ]);

        $number        = $req->input('number');
        $clientNumbers = client_number::select('number', 'id', 'region')->inRandomOrder()->take($number)->get();
        $customerName  = 'No Customer Name';

        foreach ($clientNumbers as $clientNumber) {
            customerNumber::create([
                'customer_name'   => $customerName,
                'customer_number' => $clientNumber->number,
                'region'          => $clientNumber->region, // CHANGE: Yeh add karein
                'agent'           => $req->agent,
                'date'            => $req->date,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
        $clientNumbers->each(function ($clientNumber) {
            $clientNumber->delete();
        });

        return redirect()->route('viewNumbersTable')->with(['success' => 'Distribute To Customer Numbers Successfully']);
    }
/// old code customer response ///// 
  /*  public function viewAgentDistributeNumbersDetail(string $id)
    {
        $customerResponseReports = customerNumber::with('user')->where('agent', $id)->get();
        return view('admin.customer_response', compact('customerResponseReports'));
    }
    */

    //// Customer Response ma Expiry Number Remove hokr show hongy ///

public function viewAgentDistributeNumbersDetail(string $id)
{
    $customerResponseReports = customerNumber::with('user')
        ->where('agent', $id)
        ->where('date', '>=', Carbon::now()->toDateString())
        ->get();

    return view('admin.customer_response', compact('customerResponseReports'));
}

    /// End test expiry number /////

    public function distributeNumberForm(string $id)
    {
        $allAgent = user::select('id', 'name')->where('role', 'user')->where('id', '!=', $id)->get();
        $agentID  = user::find($id);
        return view('admin.dis_to_agent_number', compact('allAgent', 'agentID'));
    }

    public function distributeNumberToAgent(Request $req, string $id)
    {
        $req->validate([
            'new_agent' => 'required',
            'date'      => 'required',
            'number'    => 'required',
        ]);

        $old_agent = customerNumber::where('agent', $id)
            ->inRandomOrder()
            ->take($req->number)
            ->get();
        foreach ($old_agent as $old) {
            $old->agent         = $req->new_agent;
            $old->customer_name = 'No Customer Name';
            $old->date          = $req->date;
            $old->status        = 'pending';
            $old->remarks       = null;
            $old->save();
        }

        return redirect()->route('viewCustomerNumber')->with(['success' => 'Distribute Numbers Successfully']);
    }

    public function viewAgentDistributeSale(string $id)
    {
        $agentName = Customer::select('a_name')->with('user')->where('status', 'sale')->groupBy('a_name')->where('a_name', '!=', $id)->get();
        $agentID   = user::find($id);
        return view('admin.dis_sale', compact(['agentName', 'agentID']));
    }

    /// Old Logic for Sale Distribute Without Prev_agent_name Show ///
  /*  
    public function updateSaleAgent(Request $req, string $id)
    {
        $CustomerSaleAgent    = customer::where('status', 'sale')->where('a_name', $id)->get();
        $disSaleAgent         = customer::where('status', 'sale')->where('a_name', $req->agent)->get();
        $oldCustomerSaleAgent = oldcustomer::where('status', 'sale')->where('agent', $id)->get();

        foreach ($CustomerSaleAgent as $oldAgent) {
            foreach ($disSaleAgent as $newAgent) {
                $oldAgent->a_name    = $newAgent->a_name;
                $oldAgent->user_name = $newAgent->user_name;

                $oldAgent->save();
            }
        }

        foreach ($oldCustomerSaleAgent as $oldAgent) {
            foreach ($disSaleAgent as $newAgent) {
                $oldAgent->agent = $newAgent->a_name;

                $oldAgent->save();
            }
        }

        return redirect()->route('viewAgentSaleTable')->with(['success' => 'Distribute Sale Successfully']);
    }
     
    */

    /// End Here Old Logic Here ///

    /// New Logic For Sale Distribute with prev_agent_name Show ///

    public function updateSaleAgent(Request $req, string $id)
{
    // 1. Purane Agent ka name User table se fetch karein
    $prevAgent = User::find($id);
    $prevAgentName = $prevAgent ? $prevAgent->name : null;

    // 2. Naye Target Agent ko User table se fetch karein
    $newAgent = User::find($req->agent);

    if ($newAgent) {
        // 3. Customer table se $req->number ke mutabiq sales fetch karein
        $CustomerSaleAgent = Customer::where('status', 'sale')
                                     ->where('a_name', $id)
                                     ->take($req->number)
                                     ->get();

        foreach ($CustomerSaleAgent as $oldAgent) {
            $oldAgent->previous_agent_name = $prevAgentName ?? ($oldAgent->user_name ?? null);
            $oldAgent->a_name              = $newAgent->id;
            $oldAgent->user_name           = $newAgent->name;
            $oldAgent->save();
        }

        // 4. OldCustomer table se bhi $req->number ke mutabiq sales fetch karein
        $oldCustomerSaleAgent = OldCustomer::where('status', 'sale')
                                           ->where('agent', $id)
                                           ->take($req->number)
                                           ->get();

        foreach ($oldCustomerSaleAgent as $oldAgent) {
            $oldAgent->previous_agent_name = $prevAgentName ?? ($oldAgent->agent_name ?? null);
            $oldAgent->agent               = $newAgent->id;
            $oldAgent->agent_name          = $newAgent->name;
            $oldAgent->save();
        }
    }

    return redirect()->route('viewAgentSaleTable')->with(['success' => 'Distribute Sale Successfully']);
}
    
    
    /// End Here New Logic ///

    public function filterSaleByDate(Request $req)
    {
        $from = Carbon::parse($req->from)->format('Y-m-d');
        $to   = Carbon::parse($req->to)->format('Y-m-d');

        $oldCustomers = Customer::whereBetween('regitr_date', [$from, $to])->get();
        $newCustomers = OldCustomer::whereBetween('regitr_date', [$from, $to])->get();

        $customers = $oldCustomers->merge($newCustomers);

        return view('admin.all_sale', compact('customers'));
    }

    public function viewPendingSale(Request $req)
    {
        $query1 = Customer::where('status', 'pending');
        $query2 = OldCustomer::where('status', 'pending');

        if ($req->date) {
            $month = date('m', strtotime($req->date));
            $year  = date('Y', strtotime($req->date));

            $query1->whereMonth('regitr_date', $month)->whereYear('regitr_date', $year);
            $query2->whereMonth('regitr_date', $month)->whereYear('regitr_date', $year);
        }

        $customers = $query1->get()->merge($query2->get());

        return view('admin.pending_sale', compact('customers'));
    }

    public function acceptPendingSale(string $id)
    {
        $oldCustomer = customer::find($id);
        $newcustomer = oldCustomer::find($id);
        if ($newcustomer) {
            $newcustomer->status = 'sale';
            $newcustomer->save();
            return back()->with(['success' => 'Pending Sale Accepted Successfuly']);
        } else {
            $oldCustomer->status = 'sale';
            $oldCustomer->save();
            return back()->with(['success' => 'Pending Sale Accepted  Successfuly']);
        }
    }

    public function viewMacExpiryData()
    {
        $oldCustomer = Customer::with('user')
            ->where('status', 'sale')
            ->whereDate('expiry_date', '<=', now())
            ->get();

        $newCustomer = oldCustomer::with('user')
            ->where('status', 'sale')
            ->whereDate('expiry_date', '<=', now())
            ->get();

        $customers = $oldCustomer->merge($newCustomer);

        $customers = $customers->map(function ($customer) {

            $expiry = Carbon::parse($customer->expiry_date);
            $now    = Carbon::now();

            $customer->expired_days   = $now->diffInDays($expiry, false) * -1;
            $customer->expired_months = $now->diffInMonths($expiry, false) * -1;

            return $customer;
        });
         
        return view('admin.mac_expiry', compact('customers'));
    }

    public function viewAddNewAgentSaleForm()
    {
        $allAgent = user::select('id', 'name')->where('role', 'user')->get();
        return view('admin.add_sale', compact('allAgent'));
    }

    public function saveNewAgentSale(Request $req)
    {
        $req->validate([
            'customer_name'   => 'required|string',
            'customer_number' => 'required|numeric|unique:customers,customer_number',
            'status'          => 'required',
            'price'           => 'required|numeric',
            'remarks'         => 'required',
            'date'            => 'required|date',
            'agent'           => 'required',
        ]);
        $agent      = user::select('id', 'name')->find($req->agent);
        $email      = $req->customer_email ?: 'No Email';
        $macAddress = $req->make_address ?: null;
        $mac_expiry = $req->expiry_date ?: null;
        customer::create([
            'customer_name'   => $req->customer_name,
            'customer_number' => $req->customer_number,
            'customer_email'  => $email,
            'status'          => $req->status,
            'price'           => $req->price,
            'remarks'         => $req->remarks,
            'regitr_date'     => $req->date,
            'a_name'          => $agent->id,
            'user_name'       => $agent->name,
            'make_address'    => $macAddress,
            'expiry_date'     => $mac_expiry,
        ]);

        return redirect()->route('viewAgentSaleTable')->with(['success' => 'New Customer Add Successfuly']);
    }

    public function viewOldNumber()
    {
        $old_number = old_number::orderBy('id', 'desc')->paginate(100);
        return view('admin.old_number', compact('old_number'));
    }

    public function disOldCustomerNumberToAgent()
    {
        $agentName             = User::select('name', 'id')->get();
        $allClientNumbersCount = old_number::count();
        return view('admin.dis_old_number', compact(['agentName', 'allClientNumbersCount']));
    }

    public function storeOldCustomerNumber(Request $req)
    {
        $req->validate([
            'agent'  => 'required',
            'date'   => 'required|date',
            'number' => 'required|integer|min:1',
        ]);

        $number        = $req->input('number');
        $clientNumbers = old_number::select('number', 'id','region')
            ->inRandomOrder()
            ->take($number)
            ->get();
        $customerName = 'No Customer Name';

        foreach ($clientNumbers as $clientNumber) {
            customerNumber::create([
                'customer_name'   => $customerName,
                'customer_number' => $clientNumber->number,
                'region'          => $clientNumber->region, // CHANGE: Yeh add karein
                'agent'           => $req->agent,
                'date'            => $req->date,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
        $clientNumbers->each(function ($clientNumber) {
            $clientNumber->delete();
        });

        return redirect()->route('viewOldNumber')->with(['success' => 'Distribute To Customer Old Numbers Successfully']);
    }

    public function viewAllLeaveRecode()
    {
        $leaveRequests = leave::with('user')->get();
        return view('admin.hr.employee.manage_leave', compact('leaveRequests'));
    }

    public function viewRenewalpage($id)
    {
        $customers = renewal::where('customer_id', $id)->get();

        return view('admin.renewal_page', compact('customers'));
    }

    public function notServiceNumber()
    {
        $customerResponseReports = not_service::with('user')
            ->whereIn('status', ['not ava', 'not in service','not int'])
            ->get();
       
        return view('admin.not_service_number', compact('customerResponseReports'));

    }


    ////// Star region wise work show ////
    // Helper function ki ab zaroorat nahi hai, aap getRegionPrefix($region) ko delete kar sakte hain.

    // Har region k numbers show karny k liye
    public function viewNumbersByRegion($region)
    {
        // Ab hum direct 'region' column check kar rahy hain
        $numbers = client_number::where('region', $region)->get();
        return view('admin.number', compact('numbers', 'region'));
    }

    // Har region ki distribution ka form open karny k liye
    public function distributeNumbersFormByRegion($region)
    {
        $agentName = User::select('name', 'id')->where('role', 'user')->get();
        
        // LIKE query ki jagah where('region', $region) use ho raha hy
        $allClientNumbersCount = client_number::where('region', $region)->count();

        return view('admin.add_customer_number', compact(['agentName', 'allClientNumbersCount', 'region']));
    }

    //////////// Har region ki distribution ko save karny k liye //////////// 
    public function storeDistributedNumbersByRegion(Request $req, $region)
    {
        $req->validate([
            'agent'  => 'required',
            'date'   => 'required|date',
            'number' => 'required|integer|min:1',
        ]);

        $numberCount = $req->input('number');

        // LIKE query ko hta diya gaya hy
        $clientNumbers = client_number::select('number', 'id', 'region')
            ->where('region', $region)
            ->inRandomOrder()
            ->take($numberCount)
            ->get();

        $customerName  = 'No Customer Name';

        foreach ($clientNumbers as $clientNumber) {
            customerNumber::create([
                'customer_name'   => $customerName,
                'customer_number' => $clientNumber->number,
                'region'          => $clientNumber->region ?? $region, // CHANGE: Region yahan pass karein
                'agent'           => $req->agent,
                'date'            => $req->date,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        $clientNumbers->each(function ($clientNumber) {
            $clientNumber->delete();
        });

        return redirect()->route('viewNumbersByRegion', ['region' => $region])
            ->with(['success' => 'Distribute To ' . strtoupper($region) . ' Numbers Successfully']);
    }

    ///// End Distributed Numbers By Region Code /////
    ///// End Region wise work number //////

    // 1. Region wise Old Numbers View ///
public function viewOldNumbersByRegion($region)
{
    $old_number = old_number::where('region', $region)->orderBy('id', 'desc')->paginate(100);
    return view('admin.old_number', compact('old_number', 'region'));
}

// 2. Region wise Old Numbers Distribution Form ///
public function distributeOldNumbersFormByRegion($region)
{
    $agentName = User::select('name', 'id')->where('role', 'user')->get();
    $allClientNumbersCount = old_number::where('region', $region)->count();

    return view('admin.dis_old_number', compact(['agentName', 'allClientNumbersCount', 'region']));
}

// 3. Region wise Old Numbers Distribution Save ///
public function storeDistributedOldNumbersByRegion(Request $req, $region)
{
    $req->validate([
        'agent'  => 'required',
        'date'   => 'required|date',
        'number' => 'required|integer|min:1',
    ]);

    $numberCount = $req->input('number');

    $clientNumbers = old_number::select('number', 'id', 'region')
        ->where('region', $region)
        ->inRandomOrder()
        ->take($numberCount)
        ->get();

    $customerName = 'No Customer Name';

    foreach ($clientNumbers as $clientNumber) {
        customerNumber::create([
            'customer_name'   => $customerName,
            'customer_number' => $clientNumber->number,
            'region'          => $clientNumber->region ?? $region, // CHANGE: Region yahan pass karein
            'agent'           => $req->agent,
            'date'            => $req->date,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    $clientNumbers->each(function ($clientNumber) {
        $clientNumber->delete();
    });

    return redirect()->route('viewOldNumbersByRegion', ['region' => $region])
        ->with(['success' => 'Distribute To ' . strtoupper($region) . ' Old Numbers Successfully']);
}

/// End Region Wise Old Numbers  Code Logic //////

}
