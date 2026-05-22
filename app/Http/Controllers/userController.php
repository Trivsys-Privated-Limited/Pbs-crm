<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\sendAgentMail;
use App\Models\customer as Customer;
use App\Models\oldCustomer as OldCustomer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\LoginOTPMail;
use App\Mail\LoginNotificationMail;

class userController extends Controller
{

    public function viewHome()
    {
        return view('front.home');
    }

    public function viewUserTable(Request $req)
    {
        $date         = $req->date ? Carbon::parse($req->date) : now();
        $currentMonth = $date->month;
        $currentYear  = $date->year;
        $oldCustomer  = Customer::with('user')
            ->select('a_name', DB::raw('SUM(price) as total'), DB::raw('MAX(regitr_date) as last_date'))
            ->where('status', 'sale')
            ->where('price', '>', 0)
            ->whereMonth('regitr_date', $currentMonth)
            ->whereYear('regitr_date', $currentYear)
            ->groupBy('a_name')
            ->orderBy('last_date', 'desc')
            ->get();

        $newCustomer = OldCustomer::with('user')
            ->select('agent', DB::raw('SUM(price) as total'), DB::raw('MAX(regitr_date) as last_date'))
            ->where('status', 'sale')
            ->where('price', '>', 0)
            ->whereMonth('regitr_date', $currentMonth)
            ->whereYear('regitr_date', $currentYear)
            ->groupBy('agent')
            ->orderBy('last_date', 'desc')
            ->get();

        $agents    = User::all();
        $salesData = [];

        foreach ($agents as $agent) {
            $salesCount            = $agent->getSalesCountForMonth($currentMonth, $currentYear);
            $salesData[$agent->id] = $salesCount;
        }
        $users = User::all();

        return view('admin.userTable', compact('users', 'salesData', 'currentMonth', 'oldCustomer', 'newCustomer'));
    }

    public function addUser()
    {
        return view('admin.add_user');
    }

    public function storeUserdetail(Request $req)
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

        User::insert([
            'name'       => $req->user_name,
            'email'      => $req->user_email,
            'phone'      => $phone,
            'address'    => $address,
            'password'   => Hash::make($req->user_password),
            'ip_address' => '1',
            'role'       => $req->role,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('viewUserTable')->with(['success' => 'User Created Successfuly']);
    }

    public function viewEditForm(string $id)
    {
        $user = User::find($id);
        return view('admin.edit_user', compact('user'));
    }

    public function storeUpdateUser(Request $req, string $id)
    {

        $req->validate([
            'user_name'  => 'required|string',
            'user_email' => 'required|email',
            'role'       => 'required',
        ]);

        $address = $req->user_address ?: 'No Address';
        $phone   = $req->user_phone ?: 'No Address';

        $user = User::find($id);
        $user->update([
            'name'       => $req->user_name,
            'email'      => $req->user_email,
            'phone'      => $phone,
            'address'    => $address,
            'role'       => $req->role,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user->ip_address = $req->ip;
        $user->save();

        return redirect()->route('viewUserTable')->with(['success' => 'User Updated Successfuly']);
    }

    public function deleteUser(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('viewUserTable')->with(['success' => 'User Deleted Successfuly']);
    }

    public function login()
    {
        Auth::logout();
        return view('front.login');
    }

//// Start Otp Process Here ///////////
    public function loginstore(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');
    $remember    = $request->has('remember');

    if (Auth::attempt($credentials, $remember)) {
        $user = Auth::user();

        // 1. Check if user is active (Aapka purana code)
        if ($user->ip_address === '0') {
            Auth::logout();
            return back()->withErrors(['email' => 'Your account is inactive. Please contact support.']);
        }

        // 2. IP Security check (Aapka purana code)
        if ($user->ip_address !== '1') {
            Auth::logout();
            return back()->withErrors(['email' => 'You cannot log in from this device or location.']);
        }

        // 3. ADMIN / SUB_ADMIN ke liye OTP Logic (Naya Addition)
        if ($user->role === 'admin' || $user->role === 'sub_admin') {
            $otp = rand(100000, 999999);
            $user->login_token = $otp;
            $user->login_token_expires_at = now()->addMinutes(15);
            $user->save();

            // OTP Email bhejein
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\LoginOTPMail($otp));

            Auth::logout(); // OTP verify hone tak logout rakhein
            session(['otp_email' => $user->email]);

            return redirect()->route('verifyOtp')->with('success', 'OTP sent to your email.');
        } 

        // 4. NORMAL USERS / SUPPORT ke liye Notification aur Redirect
        
        // Har login par notification bhejega (Naya Addition)
        $this->sendLoginNotification($user);

        if ($user->role === 'support') {
            return redirect()->route('supportNumbers');
        } else {
            return redirect()->route('viewHome');
        }

    } else {
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}

    public function verifyOtp()
    {
        // Agar session mein email nahi hai toh wapis login par bhej dein
        if (!session()->has('otp_email')) {
            return redirect()->route('login');
        }
        return view('front.verify_otp');
    }

    public function verifyOtpStore(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $email = session('otp_email');
        
        // Database mein check karein ke OTP sahi hai aur expire toh nahi hua
        $user = User::where('email', $email)
                    ->where('login_token', $request->otp)
                    ->where('login_token_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        // Kamyab verification ke baad login karein
        Auth::login($user);

        // Token clear karein taake dobara use na ho sake
        $user->login_token = null;
        $user->login_token_expires_at = null;
        $user->save();
        
        session()->forget('otp_email');

        // Baqi Admins ko notification bhejien
        $this->sendLoginNotification($user);

        // Role ke mutabiq redirect karein
        if ($user->role === 'admin' || $user->role === 'sub_admin') {
            return redirect()->route('dashboard');
        }

        return redirect()->route('viewHome');
    }

    private function sendLoginNotification($loggedInUser)
    {
        // Saare Admins aur Sub-Admins ko nikaalein
        $admins = User::whereIn('role', ['admin', 'sub_admin'])->get();
        
        $time = now()->format('Y-m-d H:i:s');
        $ip = request()->ip();

        foreach ($admins as $admin) {
            // Jo user login hua hai usay khud ko email na jaye (Optional)
            if ($admin->id !== $loggedInUser->id) {
                \Illuminate\Support\Facades\Mail::to($admin->email)->send(new \App\Mail\LoginNotificationMail(
                    $loggedInUser->name,
                    $loggedInUser->email,
                    $time,
                    $ip
                ));
            }
        }
    }

///// End Here Otp Process ////////

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }

    public function sendMail()
    {
        Mail::to('balochmuhammad817@gmail.com')->send(new sendAgentMail('Hello Muhammad', 'kdmflaksmdflkmslkdmflksm'));
    }

    public function activateUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->ip_address = '1';
            $user->save();
            return redirect()->back()->with('success', 'User activated successfully');
        }

        return redirect()->back()->with('error', 'User not found');
    }

    public function deactivateUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->ip_address = '0';
            $user->save();
            return redirect()->back()->with('success', 'User deactivated successfully');
        }

        return redirect()->back()->with('error', 'User not found');
    }

    public function viewUserChangePassword(string $id)
    {
        $user = User::select('id')->find($id);
        return view('admin.change_agent_password', compact('user'));
    }

    public function changeAgentPasswordStore(Request $req, string $id)
    {
        $req->validate([
            'password' => 'required|min:8|max:12|confirmed',
        ]);

        $user           = User::find($id);
        $user->password = Hash::make($req->password);
        $user->save();

        return back()->with(['success' => 'Password Change Successfuly']);
    }
}
