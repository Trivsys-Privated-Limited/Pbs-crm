<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\help;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class HelpController extends Controller
{
    public function viewHelpForm()
    {
        return view('front.help');
    }

    public function storeHelpRequest(Request $req)
    {
        $req->validate([
            'customer_name' => 'required|string',
            'customer_number' => 'required|max:11',
            'm_address' => 'required',
            'help_reason' => 'required',
        ]);

        $email = $req->customer_email ?: 'No Email';

        help::create([
            'c_name' => $req->customer_name,
            'c_number' => $req->customer_number,
            'c_email' => $email,
            'make_address' => $req->m_address,
            'help_reason' => $req->help_reason,
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('help')->with(['success' => 'Help Request Submit Successfuly']);
    }

    public function viewHelpTable()
    {
        $helpRequestDetail = help::where('user_id', Auth::id())->get();
        return view('front.help_view', compact('helpRequestDetail'));
    }

    public function updateRemarksForm(string $id)
    {
        $help = help::find($id);
        return view('front.updateRemarks', compact('help'));
    }

    public function agentRemarksUpdate(Request $req, string $id)
    {
        $req->validate([
            'remarks' => 'required'
        ]);
        $help = help::find($id);
        $help->remarks = $req->remarks;
        $help->save();
        return redirect()->route('viewHelpTable')->with(['success' => 'Remarks Updated Successfuly']);
    }
}
