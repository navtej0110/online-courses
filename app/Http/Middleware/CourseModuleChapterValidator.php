<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserCourses;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Test;
use App\Models\CourseTestsRelation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class CourseModuleChapterValidator {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        try{
            $user_id = Auth::id();
            $course_slug = $request->route('course_slug');
            $module_slug = $request->route('module_slug');
            $chapter_slug = $request->route('chapter_slug');
            
            // check course exists
            $course = Course::where('slug','=',$course_slug)->where('is_archive','=','0')->first();
            if(empty($course)){
                throw new \Exception('Invalid Course or Course not Found!', 404);
            }
            
            $access = (new UserCourses())->checkAccess($user_id, $course->id);
            
            if($access === false){
                throw new \Exception('You are not Enrolled to this Course!', 404);
            }
            
            // check module exists
            $module = Test::where('slug','=',$module_slug)->where('is_archive','=','0')->first();
            if(empty($module)){
                throw new \Exception('Invalid Course Module or Module not Found!', 404);
            }
            
            // check module course relation
            $rel = CourseTestsRelation::where('course_id','=',$course->id)
                    ->where('test_id','=',$module->id)
                    ->where('status','=','1')
                    ->first();
            
            if(empty($rel)){
                throw new \Exception('Invalid Course Module or Module not Found!', 404);
            }
            
            // check chapter exists
            $chapter = Chapter::where('test_id','=',$module->id)
                    ->where('is_archive','=','0')
                    ->first();
            
            if(empty($chapter)){
                throw new \Exception('Invalid Module Chapter or Chapter not Found!', 404);
            }
            
            $request->attributes->set('course', $course);
            $request->attributes->set('module', $module);
            $request->attributes->set('chapter', $chapter);
            
            return $next($request);
        }catch(\Exception $ex){
            return new Response(view('exception', ['code' => $ex->getCode(), 'error' => $ex->getMessage()]));
        }
    }

}
