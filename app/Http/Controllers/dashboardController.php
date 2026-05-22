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
use App\Models\user;
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
        $leaveRequests = $this->getLeaveRequest();
        return view('admin.dashbord', compact([
            'userCount',
            'sale',
            'trial',
            'lead',
            'price',
            'help',
            'curentSale',
            'leaveRequests',
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
        $helpRequest = help::all();
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

    public function storeNumbers(Request $req)
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
    }

    public function storeCustomerNumbers(Request $req)
    {
        $req->validate([
            'agent'  => 'required',
            'date'   => 'required|date',
            'number' => 'required|integer|min:1',
        ]);

        $number        = $req->input('number');
        $clientNumbers = client_number::select('number', 'id')->inRandomOrder()->take($number)->get();
        $customerName  = 'No Customer Name';

        foreach ($clientNumbers as $clientNumber) {
            customerNumber::create([
                'customer_name'   => $customerName,
                'customer_number' => $clientNumber->number,
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

    public function viewAgentDistributeNumbersDetail(string $id)
    {
        $customerResponseReports = customerNumber::with('user')->where('agent', $id)->get();
        return view('admin.customer_response', compact('customerResponseReports'));
    }

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
        $clientNumbers = old_number::select('number', 'id')
            ->inRandomOrder()
            ->take($number)
            ->get();
        $customerName = 'No Customer Name';

        foreach ($clientNumbers as $clientNumber) {
            customerNumber::create([
                'customer_name'   => $customerName,
                'customer_number' => $clientNumber->number,
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

   

}
