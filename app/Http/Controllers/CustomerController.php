<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\customer;
use App\Models\customerNumber;
use App\Models\oldCustomer;
use App\Models\renewal;
use App\Models\support;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function storeCustomerDetail(Request $req)
    {
        $req->validate([
            'customer_name'   => 'required|string',
            'customer_number' => 'required|numeric|unique:customers,customer_number',
            'customer_email'  => 'nullable|email|unique:customers,customer_email',
            'price'           => 'required|numeric',
            'remarks'         => 'required',
            'status'          => 'required',
            'date'            => 'required|date',
            'renewal'         => 'nullable|numeric|min:1',
        ]);

        $status = $req->status === 'sale' ? 'pending' : $req->status;

        $expiryDate = null;

        if ($req->renewal) {
            $expiryDate = Carbon::parse($req->date)
                ->addMonths((int) $req->renewal)
                ->format('Y-m-d');
        }

        $customer = Customer::create([
            'customer_name'   => $req->customer_name,
            'customer_email'  => $req->customer_email ?? 'No Email',
            'customer_number' => $req->customer_number,
            'price'           => $req->price,
            'remarks'         => $req->remarks,
            'status'          => $status,
            'a_name'          => Auth::id(),
            'regitr_date'     => $req->date,
            'expiry_date'     => $expiryDate,
            'user_name'       => Auth::user()->name,
        ]);

        if ($expiryDate) {
            Renewal::create([
                'customer_id'  => $customer->id,
                'renewal_date' => $expiryDate,
                'price'        => $req->price,
                'remarks'      => $req->remarks,
            ]);
        }

        return back()->with('success', 'Customer Created Successfully');
    }

    public function customerStatus(Request $req, string $id)
    {

        $customer = customer::find($id);
        if ($req->status == 'sale') {
            $customer->update([
                'status' => 'pending',
            ]);
        } else {
            $customer->update([
                'status' => $req->status,
            ]);
        }
        return back()->with(['update' => 'Update Customer Status Successfuly']);
    }

    public function customerSalesTable()
    {
        $oldcustomers = Customer::with('user')->where('a_name', Auth::id())
            ->where('status', 'sale')
            ->orderBy('regitr_date', 'desc')
            ->get();

        $newCustomer = oldCustomer::with('user')->where('agent', Auth::id())
            ->where('status', 'sale')
            ->orderBy('regitr_date', 'desc')
            ->get();

        $customers = $oldcustomers->merge($newCustomer);
        return view('front.customer_sale', compact(['customers']));
    }

    public function customerLeadTable()
    {

        $customers = Customer::where('a_name', Auth::id())
            ->where('status', 'lead')
            ->orderByRaw('MONTH(regitr_date) desc')
            ->get();
        $user = user::where('id', Auth::id())->first();
        return view('front.customer_lead', compact(['user', 'customers']));
    }

    public function customerTrialTable()
    {

        $customers = Customer::with('user')
            ->where('a_name', Auth::id())
            ->where('status', 'trial')
            ->orderByRaw('MONTH(regitr_date) desc')
            ->get();

        return view('front.customer_trial', compact('customers'));
    }

    public function viewCunstomerNumberTable()
    {
        return view('front.customer_number');
    }

    public function getAllCallingNumbers(Request $request)
    {
        $perPage = $request->get('per_page', 50);
        $search  = $request->get('search', '');

        $query = CustomerNumber::with('user')
            ->where('agent', Auth::id())
            ->whereDate('date', '>=', now())
            ->orderByRaw("CASE
                        WHEN status = 'pending' THEN 0
                        WHEN status = 'vm' THEN 1
                        WHEN status = 'not int' THEN 2
                        WHEN status = 'hung up' THEN 3
                        WHEN status = 'not ava' THEN 4
                        WHEN status = 'not in service' THEN 5
                        WHEN status = 'call back' THEN 6
                        ELSE 7
                    END")
            ->orderBy('status', 'desc')
            ->latest();

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('customer_number', 'LIKE', "%{$search}%");
            });
        }

        $customerNumbers = $query->paginate($perPage);

        return response()->json([
            'data'         => $customerNumbers->items(),
            'total'        => $customerNumbers->total(),
            'current_page' => $customerNumbers->currentPage(),
            'last_page'    => $customerNumbers->lastPage(),
            'per_page'     => $customerNumbers->perPage(),
            'from'         => $customerNumbers->firstItem(),
            'to'           => $customerNumbers->lastItem(),
        ], 200);
    }

    public function storeCustomerNumbersDetails(Request $req, string $id)
    {
        if ($req->status == 'lead' || $req->status == 'trial') {
            $req->validate([
                'status'  => 'required',
                'remarks' => 'required',
                'price'   => 'required',
            ]);

            $customer      = CustomerNumber::find($id);
            $customerName  = $req->customer_name ?: 'No Name';
            $customerEmail = 'No Email';
            $price         = $req->price;
            $status        = $req->status;
            $remarks       = $req->remarks;
            $authID        = Auth::id();
            $authName      = Auth::user()->name;
            $date          = now();

            $customerLeadDataStoreAndTrialDataStore = customer::create([
                'customer_name'   => $customerName,
                'customer_email'  => $customerEmail,
                'customer_number' => $customer->customer_number,
                'price'           => $price,
                'remarks'         => $remarks,
                'status'          => $req->status,
                'a_name'          => $authID,
                'user_name'       => $authName,
                'regitr_date'     => $date,
            ]);

            $customerLeadDataStoreAndTrialDataStore->user_name   = $authName;
            $customerLeadDataStoreAndTrialDataStore->regitr_date = $date;
            $customerLeadDataStoreAndTrialDataStore->save();
            $customer->delete();

            if ($req->status == 'lead') {
                return response()->json(['message' => 'Add Customer Information To Youre Lead Page Successfuly']);
            } else {
                return response()->json(['message' => 'Add Customer Information To Youre Trial Page Successfuly']);
            }
        } else {
            $req->validate([
                'status'  => 'required',
                'remarks' => 'required',
            ]);

            $customer                = CustomerNumber::find($id);
            $customerName            = $req->customer_name ?: 'No Name';
            $customer->customer_name = $customerName;
            $customer->status        = $req->status;
            $customer->remarks       = $req->remarks;
            $customer->save();

            return response()->json(['message' => 'Customer updated successfully.']);
        }
    }

    public function viewCustomerNumberEditForm(string $id)
    {
        $customer = CustomerNumber::find($id);
        return view('front.edit_customer_number', compact('customer'));
    }

    public function storeCustomerNumberEditDetails(Request $req, string $id)
    {

        if ($req->status == 'lead' || $req->status == 'trial') {
            $req->validate([
                'status'  => 'required',
                'remarks' => 'required',
                'price'   => 'required',
            ]);

            $customer      = CustomerNumber::find($id);
            $customerName  = $req->customer_name ?: 'No Name';
            $customerEmail = 'No Email';
            $price         = $req->price;
            $status        = $req->status;
            $remarks       = $req->remarks;
            $authID        = Auth::id();
            $authName      = Auth::user()->name;
            $date          = now();

            $customerLeadDataStoreAndTrialDataStore = customer::create([
                'customer_name'   => $customerName,
                'customer_email'  => $customerEmail,
                'customer_number' => $customer->customer_number,
                'price'           => $price,
                'remarks'         => $remarks,
                'status'          => $req->status,
                'a_name'          => $authID,
                'user_name'       => $authName,
                'regitr_date'     => $date,
            ]);

            $customerLeadDataStoreAndTrialDataStore->user_name   = $authName;
            $customerLeadDataStoreAndTrialDataStore->regitr_date = $date;
            $customerLeadDataStoreAndTrialDataStore->save();
            $customer->delete();

            if ($req->status == 'lead') {
                return redirect()->route('viewCunstomerNumberTable')->with(['success' => 'Add Customer Information To Youre Lead Page Successfuly']);
            } else {
                return redirect()->route('viewCunstomerNumberTable')->with(['success' => 'Add Customer Information To Youre Trial Page Successfuly']);
            }
        } else {
            $req->validate([
                'customer_name' => 'required',
                'status'        => 'required',
                'remarks'       => 'required',
            ]);

            $customer = CustomerNumber::find($id);

            $customer->customer_name = $req->customer_name;
            $customer->status        = $req->status;
            $customer->remarks       = $req->remarks;
            $customer->save();

            return redirect()->route('viewCunstomerNumberTable')->with(['success' => 'Update Customer Information Successfuly']);
        }
    }

    public function viewEditCustomerSaleDetailForm(Request $req, string $id)
    {
        $customer = customer::where('a_name', Auth::id())->find($id);

        return view('front.edit_customer_sale', compact('customer'));
    }

    public function storeEditCustomerSaleDetails(request $req, string $id)
    {
        $req->validate([
            'price'   => 'required',
            'remarks' => 'required',
        ]);
        $customer = customer::find($id);

        $customer->price   = $req->price;
        $customer->remarks = $req->remarks;
        $customer->save();

        return redirect()->route('customerSalesTable')->with(['success' => 'Update Sale Detail Successfuly']);
    }

    public function viewOldCustomerNewPKG(string $id)
    {
        $oldCustomerData = customer::find($id);
        return view('front.add_old_customer_sale', compact('oldCustomerData'));
    }

    public function storeOldCustomerNewPKGData(Request $req, string $id)
    {
        $req->validate([
            'price'   => 'required',
            'date'    => 'required',
            'remarks' => 'required',
        ]);

        $oldCustomerData = customer::find($id);

        $NewCustomer = oldCustomer::create([
            'customer_name'   => $oldCustomerData->customer_name,
            'customer_number' => $oldCustomerData->customer_number,
            'customer_email'  => 'No Email',
            'price'           => $req->price,
            'status'          => 'pending',
            'remarks'         => $req->remarks,
        ]);
        $NewCustomer->regitr_date = $req->date;
        $NewCustomer->agent       = Auth::id();
        $NewCustomer->save();
        return redirect()->route('customerSalesTable')->with(['success' => 'Add New Customer Successfully']);
    }

    public function viewleadEditForm(string $id)
    {
        $customer = customer::find($id);
        return view('front.lead_edit', compact('customer'));
    }

    public function storeUpdateLeadData(Request $req, string $id)
    {
        $req->validate([
            'price'   => 'required',
            'date'    => 'required',
            'remarks' => 'required',
        ]);

        $customer              = customer::find($id);
        $customer->price       = $req->price;
        $customer->regitr_date = $req->date;
        $customer->remarks     = $req->remarks;
        $expiryMonths          = (int) $req->renewal;
        $expiryDate            = Carbon::now()->addMonths($expiryMonths)->format('Y-m-d');
        $customer->expiry_date = $expiryDate;

        renewal::create([
            'customer_id'  => $customer->id,
            'renewal_date' => $expiryDate,
            'price'        => $req->price,
            'remarks'      => $req->remarks,
        ]);

        $customer->save();

        return redirect()->route('customerLeadTable')->with(['success' => 'update customer detail']);
    }

    public function viewTrialEditForm(string $id)
    {
        $customer = customer::find($id);
        return view('front.trial_edit', compact('customer'));
    }

    public function storeUpdateTrialData(Request $req, string $id)
    {
        $req->validate([
            'price'   => 'required',
            'date'    => 'required',
            'remarks' => 'required',
        ]);

        $expiryMonths = (int) $req->renewal;
        $expiryDate   = Carbon::now()->addMonths($expiryMonths)->format('Y-m-d');

        $customer              = customer::find($id);
        $customer->price       = $req->price;
        $customer->regitr_date = $req->date;
        $customer->remarks     = $req->remarks;
        $customer->expiry_date = $expiryDate;
        $customer->save();

        $renewal = renewal::create([
            'customer_id'  => $customer->id,
            'renewal_date' => $expiryDate,
            'price'        => $req->price,
            'remarks'      => $req->remarks,
        ]);

        return redirect()->route('customerTrialTable')->with(['success' => 'update customer detail']);
    }

    public function customerDeniedTable()
    {

        $customers = Customer::with('user')
            ->where('status', 'denied')
            ->orderByRaw('MONTH(regitr_date) desc')
            ->where('a_name', Auth::id())
            ->get();

        return view('front.customer_denied', compact('customers'));
    }

    public function viewSaleExpiry()
    {
        $oldCustomer = Customer::with('user')
            ->where('status', 'sale')
            ->whereDate('expiry_date', '<=', now())
            ->where('a_name', Auth::id())
            ->get();

        $newCustomer = oldCustomer::with('user')
            ->where('status', 'sale')
            ->whereDate('expiry_date', '<=', now())
            ->where('agent', Auth::id())
            ->get();

        $customers = $oldCustomer->merge($newCustomer);

        $customers = $customers->map(function ($customer) {

            $expiry = Carbon::parse($customer->expiry_date);
            $now    = Carbon::now();

            $customer->expired_days   = $now->diffInDays($expiry, false) * -1;
            $customer->expired_months = $now->diffInMonths($expiry, false) * -1;

            return $customer;
        });
        return view('front.sale_expiry', compact('customers'));
    }

    public function viewUpdateSaleExpiryForm(string $id)
    {
        $customer = customer::find($id);
        return view('front.updateSaleExpiry', compact('customer'));
    }

    public function updateSaleExpiry(Request $req, string $id)
    {
        $req->validate([
            'expiry_date' => 'required|numeric',
            'remarks'     => 'required',
            'price'       => 'required|numeric',
            'date'        => 'required|date',
        ]);

        $expiryMonths = (int) $req->expiry_date;

        $baseDate   = Carbon::parse($req->date);
        $expiryDate = $baseDate->addMonths($expiryMonths)->format('Y-m-d');

        $customer              = customer::find($id);
        $customer->expiry_date = $expiryDate;
        $customer->status      = 'pending';
        $customer->remarks     = $req->remarks;
        $customer->price       = $req->price;
        $customer->regitr_date = $req->date;
        $customer->save();

        $renewal = renewal::create([
            'customer_id'  => $customer->id,
            'renewal_date' => $expiryDate,
            'price'        => $req->price,
            'remarks'      => $req->remarks,
        ]);

        return redirect()->route('customerSalesTable')
            ->with(['success' => 'Customer Expiry Date Updated Successfully']);
    }

    public function supportNumbers()
    {
        $supportNumbers = support::where('show_status', 'S')->paginate(100);
        return view('front.support_number', compact('supportNumbers'));
    }

    public function daniyalNumbers()
    {
        $supportNumbers = support::where('show_status', 'D')->paginate(100);
        return view('front.daniyal_number', compact('supportNumbers'));
    }

    public function saadNumbers()
    {
        $supportNumbers = support::where('show_status', 'B')->paginate(100);
        return view('front.saad_number', compact('supportNumbers'));
    }

    public function storeSupportNumber(Request $req, string $id)
    {
        $req->validate([
            'remarks' => 'required',
            'status'  => 'required',
        ]);
        $support          = support::find($id);
        $support->remarks = $req->remarks;
        $support->status  = $req->status;
        $support->save();
        return back()->with('success', 'Support Number Updated Successfully');
    }

}
