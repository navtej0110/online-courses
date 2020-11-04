<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class ProfileController extends AdminController {
    
    public function home(Request $request) {
       return view('admin.profile.home');
    }
    
    /**
     * 
     * @param array $data
     * @return type
     */
    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => ['required', ],
            'email' => ['required', 'string', 'max:255']
        ]);
    }

    /**
     * 
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function edit(Request $request, $id) {
        try {
            $data = parent::editRecord($request, $id, User::class);
            return view('admin.user.add', $data);
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

}
