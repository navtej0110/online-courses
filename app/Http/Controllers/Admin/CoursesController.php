<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Providers\RouteServiceProvider;
use App\Models\Course;
use App\Models\Test;
use App\Models\CourseTestsRelation;
use Illuminate\Support\Facades\DB;

class CoursesController extends AdminController {

    /**
     * 
     * @param array $data
     * @return type
     */
    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
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
        $categories = \App\Models\Category::where('is_Archive','=','0')->get();
        return view('admin.course.add', ['id' => '','categories' => $categories,'selected_category'=>[]]);
    }
    
    /**
     * 
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function edit(Request $request, $id) {
        try{

            $categories = \App\Models\Category::where('is_Archive','=','0')->get();
            $data = parent::editRecord($request, $id, Course::class);
            $selected_category= \App\Models\CourseCategory::select('category_id')
                        ->where('is_Archive','=','0')
                        ->where('course_id','=',$id)
                        ->get()
                        ->toArray();
            $categories_id=[];
            for($i=0;$i<count($selected_category);$i++)
                {
                    $categories_id[]=$selected_category[$i]['category_id'];
            }
            
            return view('admin.course.add', $data,['categories' => $categories,'selected_category'=>$categories_id]);
            
        }catch(\Exception $ex){
            return back()->with('error', $ex->getMessage());
        }
    }
    
    /**
     * 
     * @param Request $request
     * @throws \Exception
     */
    public function delete(Request $request) {
        try{
            $payload = parent::deleteRecord($request, Course::class);
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
    public function addupdatetests(Request $request, $course_id){
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
    public function addUpdate(Request $request, $id = null) {
        $this->validator($request->all())->validate();
        
        try {
            
            if(empty($id)){
                $course = new Course();
            }else{
                $course = Course::where('id','=',$id)->first();
                if(empty($course)){
                    throw new Exception('Invalid Course Selected!');
                }
            }
            
            $course->name = $request['name'];
            $course->slug = strtolower(urlencode($request['name']));
            $course->description = addslashes($request['description']);
            $course->password = $request['password'];
            $course->status = $request['status'];
            $course->is_archive = $request['is_archive'];
            
            $course->include_certificate = $request['include_certificate'];
            $course->is_beginner = $request['is_beginner'];
            $course->is_intermediate = $request['is_intermediate'];
            $course->price = $request['price'];
            
            $course->idle_time = $request['idle_time'];
            $course->minimum_minutes = $request['minimum_minutes'];
            
            $course->created_by_admin_id = $this->auth::id();
            $course->save();
            
            if($request->has('image')){
                $course->image = $request->input('image');
            }else{
                $course->image = "";
            }
            
            $course->save();
            
            if(!empty($request->input('category')) && sizeof($request->input('category')) > 0){
                $course_categories = \App\Models\CourseCategory::where('course_id','=',$course->id)->update(['is_archive'=>1]);
                foreach($request->input('category') as $c){
                    \App\Models\CourseCategory::create([
                        'course_id' => $course->id,
                        'category_id' => $c
                    ]);
                }
            }else{
                $course_categories = \App\Models\CourseCategory::where('course_id','=',$course->id)->update(['is_archive'=>1]);
            }
            
            return back()->with('success', "Course is ".($id ? 'updated' : "created")." Successfully!");
            
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    public function index(Request $request) {
        $courses = Course::where('is_archive','=',0)
                ->with(['modules' => function($query){
                    $query->with('test');
        }])->get();
        return view('admin.course.list',['courses' => $courses]);
    }
}
