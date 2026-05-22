<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\sendAgentMail;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class adminController extends Controller
{
    public function viewAdminTable()
    {
        $users = user::where('role', 'admin')->Orwhere('role', 'sub_admin')->get();
        return view('admin.adminTable', compact('users'));
    }

    public function viewAddForm()
    {
        return view('admin.add_admin');
    }

    public function storeAdminDetail(Request $req)
    {
        $req->validate([
            'user_name'     => 'required|string',
            'user_email'    => 'required|email|unique:users,email',
            'user_password' => 'required|min:8|max:12|confirmed',
            'role'          => 'required',
        ]);

        $address = $req->user_address ?: 'No Address';
        $phone   = $req->user_phone ?: 'No Address';

        $toSendMail = $req->user_email;
        $subject    = 'Hello ' . $req->user_name . ' Login Now';
        $message    = 'Email : ' . $req->user_email . ' Password : ' . $req->user_password;

        Mail::to($toSendMail)->send(new sendAgentMail($subject, $message));

        user::insert([
            'name'       => $req->user_name,
            'email'      => $req->user_email,
            'phone'      => $phone,
            'address'    => $address,
            'password'   => Hash::make($req->user_password),
            'role'       => $req->role,
            'ip_address' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('viewAdminTable')->with(['success' => 'Admin Created Successfuly']);
    }

    public function viewEditForm(string $id)
    {
        $user = user::find($id);
        return view('admin.edit_admin', compact('user'));
    }

    public function storeUpdateAdmin(Request $req, string $id)
    {

        $req->validate([
            'user_name'  => 'required|string',
            'user_email' => 'required|email',
        ]);

        $address = $req->user_address ?: 'No Address';
        $phone   = $req->user_phone ?: 'No Address';

        $user = user::find($id);
        $user->update([
            'name'       => $req->user_name,
            'email'      => $req->user_email,
            'phone'      => $phone,
            'address'    => $address,
            'role'       => $req->role,
            'ip_address' => $req->ip,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user->role       = $req->role;
        $user->ip_address = $req->ip;
        $user->save();

        return redirect()->route('viewAdminTable')->with(['success' => 'Admin Updated Successfuly']);

    }

    public function deleteAdmin(string $id)
    {
        $user = user::find($id);
        $user->delete();
        return redirect()->route('viewAdminTable')->with(['success' => 'Admin Deleted Successfuly']);
    }

    public function viewAdminUpdatePasswordForm()
    {
        $admin = Auth::user()->id;
        return view('admin.changePassword', compact('admin'));
    }

    public function changePasswordStore(Request $req, string $id)
    {
        $req->validate([
            'password' => 'required|min:8|max:12|confirmed',
        ]);

        $admin = user::find($id);

        $admin->password = Hash::make($req->password);
        $admin->save();

        return back()->with(['success' => 'Password Change Successfuly']);
    }

}