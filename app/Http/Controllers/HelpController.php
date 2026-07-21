<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\help;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\HelpDeskNotification;
use Illuminate\Support\Facades\Notification;

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

        $help = help::create([
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

    /// ---- NEW CODE: Notify Support & Admins ----  ///
    $supportStaff = User::whereIn('role', ['support', 'admin', 'sub_admin'])->get();
    Notification::send($supportStaff, new HelpDeskNotification(
        "New Help Request from {$req->customer_name}",
        route('help.chat', $help->id)
    ));
    /// --------------   End Here Notify code ---------------------  ///

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

        //// Start Status Notification logic Code ////
    // User model se us banday ko nikalain jiski yeh request thi
    $requestOwner = \App\Models\User::find($help->user_id); 
    
    if ($requestOwner) {
        $requestOwner->notify(new \App\Notifications\HelpDeskNotification(
            "Status Update: Your Help Request (Status is #{$help->id}) Now '{$help->status}'.",
            route('viewHelpTable', $help->id)
        ));
    }
    
    //  End Status Notification Logic Code //
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

    //////// Send Message New Original Code ////////

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

        // ---- NEW CODE: Notifications Logic Start here ---- //
    if ($user->role === 'user') {
        // Agar normal user message bheje toh support/admin ko batao
        $supportStaff = User::whereIn('role', ['support', 'admin', 'sub_admin'])->get();
        Notification::send($supportStaff, new HelpDeskNotification(
            "New message on Request #{$help->id} from {$user->name}",
            route('help.chat', $help->id)
        ));
    } else {
        // Agar support/admin message bheje toh normal user ko batao
        $requestOwner = User::find($help->user_id);
        if ($requestOwner && $requestOwner->id !== $user->id) {
            $requestOwner->notify(new HelpDeskNotification(
                "New reply on your Request #{$help->id}",
                route('help.chat', $help->id)
            ));
        }
    }
    // --------  New Code: Notification Logic  End Here  -------  //

        return redirect()->route('help.chat', $id)->with(['success' => 'Message sent.']);
    }

    ////////// New Original Code End Here //////////

    public function viewResolvedHelpRequestsForSupport()
    {
        // Sirf 'resolve' status wali requests filter kar k bhej rahy hain
        $helpRequestDetail = help::where('status', 'resolve')->latest()->get();
        return view('front.support_resolved_requests', compact('helpRequestDetail'));
    }
}
