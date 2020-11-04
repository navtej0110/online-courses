<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Providers\RouteServiceProvider;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Test;
use App\Models\CourseTestsRelation;
use Illuminate\Support\Facades\DB;

class ChaptersController extends AdminController
{
    protected $baseModel = Chapter::class;
    
    protected $baseViewDir = 'chapter';
    
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
    public function add(Request $request, $test_id) {
        try{
            $test = Test::where('id','=',$test_id)->first();
            if(empty($test)){
                throw new \Exception("Test not Found!");
            }
            
            return view('admin.'.$this->baseViewDir.'.add', ['test' => $test, 'test_id' => $test_id, 'id' => '']);
        }catch(\Exception $ex){
            return redirect()->route('admin.fourZeroFour');
        }
    }
    
    /**
     * 
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function edit(Request $request, $test_id, $id) {
        try{
            $test = Test::where('id','=',$test_id)->first();
            
            if(empty($test)){
                throw new \Exception("Test not Found!");
            }
            
            $data = parent::editRecord($request, $id, $this->baseModel);
            $data['test'] = $test;
            return view('admin.'.$this->baseViewDir.'.add', ['test'=>$test, 'test_id' => $test_id, 'result' => $data['data'], 'id' => $data['id']]);
            
        }catch(\Exception $ex){
            return back()->with('error', $ex->getMessage());
        }
    }
    
    /**
     * 
     * @param Request $request
     * @throws \Exception
     */
    public function delete(Request $request, $type_id, $id) {
        try{
            $payload = parent::deleteRecord($request, $this->baseModel);
            echo json_encode($payload);
            
        }catch(\Exception $ex){
            echo json_encode(['status' => 0, 'error' => $ex->getMessage()]);
        }
    }
    
    public function tests(Request $request, $course_id) {
        try{
            $course = Course::where('id','=',$course_id)->first();
            
            if(empty($course)){
                throw new \Exception('Invalid Course Selected!');
            }
            
            $tests = Test::where('is_archive','=',0)->get();
            $courseTests = CourseTestsRelation::where('course_id','=',$course_id)->where('status','=',1)->with('test')->get();
            
            return view('admin.course.tests', ['course' => $course, 'tests' => $tests, 'courseTests' => $courseTests]);
        }catch(\Exception $ex){
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
    public function addupdatetests(Request $request, $test_id = null){
        
        print_r($request->all()); exit;
        
        try{
            DB::beginTransaction();
            $course = Course::where('id','=',$course_id)->where('status','=','1')->first();
            $to = $request['to'];
            
            if(empty($course)){
                throw new \Exception('Invalid Course Selected!');
            }
            
            if(!$request->has('to')){
                throw new \Exception('Plese select any Test or Course!');
            }
            
            if(empty($to)){
                throw new \Exception('Plese select any Test or Course!');
            }
            
            $data = [];
            foreach($to as $t){
                $data[] = [
                    'course_id' => $course_id,
                    'test_id' => $t,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
            
            CourseTestsRelation::where('course_id','=',$course_id)->update(['status'=>0]);
            
            CourseTestsRelation::insert($data);
            DB::commit();
            return back()->with('success', 'Tests to Courses assigned Successfully!');
            
        }catch(\Exception $ex){
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
    public function addUpdate(Request $request, $test_id, $id = null) {
        $this->validator($request->all())->validate();
        
        try {            
            if(empty($id)){
                $object = new $this->baseModel();
            }else{
                $object = $this->baseModel::where('id','=',$id)->first();
                if(empty($object)){
                    throw new Exception('Invalid Chapter Selected!');
                }
            }
            
            $object->name = $request['name'];
            $object->slug = urldecode($request['slug']);
            //$object->slug = strtolower(urlencode($request['name']));
            $object->description = addslashes($request['description']);
            //$object->status = $request['status'];
            $object->is_archive = $request['is_archive'];
            $object->test_id = $request['test_id'];
            
            $object->duration = $request['duration'];
            $object->retakes = $request['retakes'];
            $object->minimum_passing_grades = $request['minimum_passing_grades'];
            $object->number_of_question_in_quiz = $request['number_of_question_in_quiz'];
            
            $object->is_locked = $request['is_locked'];
            
            //$object->created_by_admin_id = $this->auth::id();
            $object->save();
            
            return back()->with('success', "Chapter is ".($id ? 'updated' : "created")." Successfully!");
            
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }
    
    /**
     * 
     * @param Request $request
     * @param type $test_id
     * @return type
     */
    public function index(Request $request, $test_id = "") {
        try{
            if(!empty($test_id)){
                $test = Test::where('id','=',$test_id)->first();
                if(empty($test)){
                    throw new \Exception("Module not Found!");
                }
                $data = Chapter::with('module')->where(['test_id' => $test_id])->get();
            }else{
                $test = "";
                $data = Chapter::with('module')->get();
            }
            
            return view('admin.'.$this->baseViewDir.'.list',['test_id' => $test_id, 'results' => $data, 'test' => $test]);
            
        }catch(\Exception $ex){
            return back()->with('error', $ex->getMessage());
        }
    }
}
