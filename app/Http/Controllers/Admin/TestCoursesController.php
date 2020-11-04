<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Providers\RouteServiceProvider;
use App\Models\Course;
use App\Models\Test;

class TestCoursesController extends AdminController {

    /**
     * 
     * @param array $data
     * @return type
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'time_limit' => ['required', 'integer'],
                    'idle_time' => ['required', 'integer'],
                    //'content' => ['required', 'string'],
        ]);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function add(Request $request) {
        return view('admin.test.add', ['id' => '']);
    }

    /**
     * 
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function edit(Request $request, $id) {
        try {
            $data = parent::editRecord($request, $id, Test::class);
            return view('admin.test.add', $data);
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
            $payload = parent::deleteRecord($request, Test::class);
            echo json_encode($payload);
        } catch (\Exception $ex) {
            echo json_encode(['status' => 0, 'error' => $ex->getMessage()]);
        }
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
                $data = new Test();
            } else {
                $data = Test::where('id', '=', $id)->first();
                if (empty($data)) {
                    throw new \Exception('Invalid Test Selected!');
                }
            }

            $data->name = $request['name'];
            $data->slug = strtolower(urlencode($request['name']));
            $data->description = addslashes($request['description']);
            $data->time_limit = $request['time_limit'];
            $data->idle_time = $request['idle_time'];
            $data->content = isset($request['content']) ? addslashes($request['content']) : "";
            $data->status = $request['status'];
            $data->is_locked = $request['is_locked'];
            $data->is_archive = 0;
            $data->scatter = 0;
            $data->created_by_admin_id = $this->auth::id();
            $data->save();

            return back()->with('success', "Test is " . ($id ? 'updated' : "created") . " Successfully!");
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    public function index(Request $request) {
        $records = Test::where('is_archive','=','0')
                ->with(['courses' => function($query){
                    $query->with('course');
                }])
                ->with('chapters')
                ->get();
        return view('admin.test.list', ['records' => $records]);
    }

}
