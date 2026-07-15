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

    public function viewAllHelpRequestsForSupport()
    {
        // Yahan humne '!=' (not equal to) laga diya hy,
        // toh ab yeh resolve wali requests yahan show nahi karega.
        $helpRequestDetail = help::where('status', '!=', 'resolve')->latest()->get();
        return view('front.support_help_requests', compact('helpRequestDetail'));
    }

    public function updateHelpStatus(Request $req, string $id)
    {
        $req->validate([
            'status' => 'required|in:pending,working,resolve',
        ]);

        $user = Auth::user();
        if ($user->role !== 'support' && $user->role !== 'admin' && $user->role !== 'sub_admin') {
            abort(403, 'Unauthorized.');
        }

        $help = help::findOrFail($id);
        $help->status = trim($req->status);
        $help->save();

        return redirect()->back()->with(['success' => 'Status updated successfully!']);
    }

    public function chatView(Request $req, string $id)
    {
        $help = help::with(['messages.sender'])->findOrFail($id);

        $user = Auth::user();
        // Sirf normal 'user' role wale restrict hain ke woh sirf apni request dekh sakein
        // Support, admin, sub_admin sab dekh sakte hain
        if ($user->role === 'user' && $help->user_id != $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('front.help_chat', compact('help'));
    }

    public function sendMessage(Request $req, string $id)
    {
        $req->validate([
            'message' => 'required|string'
        ]);

        $help = help::findOrFail($id);
        $user = Auth::user();

        // Sirf normal 'user' role wale restrict hain
        if ($user->role === 'user' && $help->user_id != $user->id) {
            abort(403, 'Unauthorized access.');
        }

        \App\Models\HelpMessage::create([
            'help_id' => $help->id,
            'sender_id' => $user->id,
            'message' => $req->message,
        ]);

        return redirect()->route('help.chat', $id)->with(['success' => 'Message sent.']);
    }

    public function viewResolvedHelpRequestsForSupport()
    {
        // Sirf 'resolve' status wali requests filter kar k bhej rahy hain
        $helpRequestDetail = help::where('status', 'resolve')->latest()->get();
        return view('front.support_resolved_requests', compact('helpRequestDetail'));
    }
}
