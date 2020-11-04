<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Admin;
use App\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * 
     * @return type
     */
    public function showAdminLoginForm() {
        return view('auth.login', ['url' => 'admin', 'register' => '/register/admin', 'forget_password' => '']);
    }
    
    public function showUserLoginForm() {
        return view('auth.login', ['url' => '', 'register' => '/register', 'forget_password' => 'password/reset']);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function adminLogin(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        
        /**
         * check for extra validation here like status or archived.
         */

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect()->intended('/admin/home');
        }else{
            return back()->with('error', 'Invalid Username or Password!');
        }
        
        return back()->withInput($request->only('email', 'remember'));
    }
    
    public function userLogin(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        
        /**
         * check for extra validation here like status or archived.
         */
        $user = User::where('email','=',$request->email)->first();
        
        if(empty($user))
            return back()->with('error', 'Invalid Username or Password!');
        
        if($user->is_archive != '0')
            return back()->with('error', 'Your account is disable please contact to support!');

        if (Auth::guard()->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect()->intended('/home');
        }else{
            return back()->with('error', 'Invalid Username or Password!');
        }
        
        return back()->withInput($request->only('email', 'remember'));
    }

}
