<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends AdminController {
    
    public function fourZeroFour(Request $request) {
       return view('admin.user.404');
    }
    
    public function home(Request $request) {
       return view('admin.user.home');
    }
    
    public function filemanager(Request $request) {
       return view('admin.user.filemanager');
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
     * @return type
     */
    public function add(Request $request) {
        return view('admin.user.add', ['id' => '']);
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

    /**
     * 
     * @param Request $request
     * @throws \Exception
     */
    public function delete(Request $request) {
        try {
            $payload = parent::deleteRecord($request, User::class);
            echo json_encode($payload);
        } catch (\Exception $ex) {
            echo json_encode(['status' => 0, 'error' => $ex->getMessage()]);
        }
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function listAjax(Request $request){
        $start = $request['start'];
        $length = $request['length'];
        $search = $request['search']['value'];
        $order = $request['order'][0];
        $draw = $request['draw'];
                
        $query = $default = new User();
        
        // all records without any conditions.
        $recordsTotal = $default->count();
        
        // conditions here.
        if(!empty($search)){
            $query = $query::where('name', 'like', '%'.$search.'%')->oRwhere('email', 'like', '%'.$search.'%');
        }
        
        // all records count with conditions.
        $recordsFiltered = $query->count();
        
        if($order['column'] == 0){
            $query = $query->orderBy('name', $order['dir']);
        }
        
        if($order['column'] == 1){
            $query = $query->orderBy('email', $order['dir']);
        }
        
        // all records with condition and limit.
        $data = $query->offset($start)->limit($length)->get();
               
        return view('admin.user.ajaxlist',['recordsFiltered' => $recordsFiltered, 'recordsTotal' => $recordsTotal, 'data' => $data]);
    }

    /**
     * 
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function addUpdate(Request $request, $id = null) {
        $this->validator($request->all())->validate();

        try {

            if (empty($id)) {
                $data = new User();
            } else {
                $data = User::where('id', '=', $id)->first();
                if (empty($data)) {
                    throw new \Exception('Invalid User Selected!');
                }
            }
            
            $data->name = $request['name'];
            $data->email = $request['email'];
            
            if(!empty($request['password'])){
                $data->password = Hash::make($request['password']);
            }
            //$data->created_by_admin_id = $this->auth::id();
            $data->save();

            return back()->with('success', "User is " . ($id ? 'updated' : "created") . " Successfully!");
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    public function index(Request $request) {
        return view('admin.user.list');
    }

}
