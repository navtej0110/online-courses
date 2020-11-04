<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Providers\RouteServiceProvider;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Pool;
use App\Models\Test;
use App\Models\CourseTestsRelation;
use Illuminate\Support\Facades\DB;

class QuestionPoolsController extends AdminController {

    protected $baseModel = Pool::class;
    protected $baseViewDir = 'pool';

    /**
     * 
     * @param array $data
     * @return type
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
        ]);
    }

    protected function testsValidator(array $data) {
        return Validator::make($data, [
                    'to' => ['required']
        ]);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function add(Request $request) {
        return view('admin.' . $this->baseViewDir . '.add', ['id' => '']);
    }

    /**
     * 
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function edit(Request $request, $id) {
        try {
            $data = parent::editRecord($request, $id, $this->baseModel);
            return view('admin.' . $this->baseViewDir . '.add', $data);
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
            $payload = parent::deleteRecord($request, $this->baseModel);
            echo json_encode($payload);
        } catch (\Exception $ex) {
            echo json_encode(['status' => 0, 'error' => $ex->getMessage()]);
        }
    }

    public function tests(Request $request, $course_id) {
        try {
            $course = Course::where('id', '=', $course_id)->first();

            if (empty($course)) {
                throw new \Exception('Invalid Course Selected!');
            }

            $tests = Test::where('is_archive', '=', 0)->get();
            $courseTests = CourseTestsRelation::where('course_id', '=', $course_id)->where('status', '=', 1)->with('test')->get();

            return view('admin.course.tests', ['course' => $course, 'tests' => $tests, 'courseTests' => $courseTests]);
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    /**
     * 
     * @param Request $request
     * @param type $course_id
     * @return type
     * @throws \Exception
     */
    public function addupdatetests(Request $request, $test_id = null) {

        try {
            DB::beginTransaction();
            $course = Course::where('id', '=', $course_id)->where('status', '=', '1')->first();
            $to = $request['to'];

            if (empty($course)) {
                throw new \Exception('Invalid Course Selected!');
            }

            if (!$request->has('to')) {
                throw new \Exception('Plese select any Test or Course!');
            }

            if (empty($to)) {
                throw new \Exception('Plese select any Test or Course!');
            }

            $data = [];
            foreach ($to as $t) {
                $data[] = [
                    'course_id' => $course_id,
                    'test_id' => $t,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }

            CourseTestsRelation::where('course_id', '=', $course_id)->update(['status' => 0]);

            CourseTestsRelation::insert($data);
            DB::commit();
            return back()->with('success', 'Tests to Courses assigned Successfully!');
        } catch (\Exception $ex) {
            DB::rollBack();
            return back()->with('error', $ex->getMessage());
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
                $object = new $this->baseModel();
            } else {
                $object = $this->baseModel::where('id', '=', $id)->first();
                if (empty($object)) {
                    throw new Exception('Invalid Chapter Selected!');
                }
            }

            $object->name = $request['name'];
            //$object->slug = strtolower(urlencode($request['name']));
            $object->description = addslashes($request['description']);
            //$object->status = $request['status'];
            $object->is_archive = $request['is_archive'];
            $object->chapter_id = '1';
            $object->test_id = '0'; //$request['test_id'];
            //$object->created_by_admin_id = $this->auth::id();
            $object->save();

            return back()->with('success', "Chapter is " . ($id ? 'updated' : "created") . " Successfully!");
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    public function index(Request $request) {
        $data = $this->baseModel::all();
        return view('admin.' . $this->baseViewDir . '.list', ['results' => $data]);
    }

}
