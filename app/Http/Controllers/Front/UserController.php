<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\FrontController;
use Illuminate\Http\Request;

class UserController extends FrontController {

    /**
     * 
     * @return type
     */
    public function index(Request $request) {
       return view('front.user.home');
    }
    
    public function profile(Request $request) {
       return view('front.user.profile');
    }
    
    public function dashboard(Request $request) {
       return view('front.user.dashboard');
    }

}
