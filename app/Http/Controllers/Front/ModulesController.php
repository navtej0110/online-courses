<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\UserCourses;
use App\Models\Course;
use App\Models\Test;
use App\Models\CourseTestsRelation;
use App\Models\UserModulesStatus;
use App\Models\Chapter;
use App\Models\UserTopicQuiz;
use App\Models\UserTestAttempts;


class ModulesController extends FrontController {

    /**
     * 
     * @param Request $request
     * @param type $course_slug
     * @return type
     * @throws Exception
     * 
     * $request->route()->parameters()
     */
    public function listAll(Request $request, $course_slug) {
        try {
            if($request->attributes->get('loggedin') == 0){
                return $this->nonLoggedIn($request, $course_slug);
            }
            if($request->attributes->get('loggedin') == 1){
                return $this->loggedIn($request, $course_slug);
            }
        } catch (\Exception $ex) {
            return view('exception', ['code' => $ex->getCode(), 'error' => $ex->getMessage()]);
        }
    }

    /**
     * 
     * @param type $request
     * @param type $course_slug
     * @return type
     * @throws Exception
     */
    protected function loggedIn($request, $course_slug) {
        $user_id = $this->auth::id();

        $course = $request->attributes->get('course');
        $is_accessable = $request->attributes->get('is_accessable');

        // start course time tracking
        if (!empty($is_accessable)) {
            (new UserCourses())->startCourse($user_id, $course->id);
        }

        $modules = CourseTestsRelation::where('course_id', '=', $course->id)
                ->where('status', '=', 1)
                ->with(['module' => function($query) {

                        $query->with(['chapters'=>function($qry){
                            $qry->with('topics');
                         }]);
                    }])
                ->get();
                $chapters=$modules->toArray();
                $_percentage=$this->chapterProgress($chapters,$user_id);
                
              
                
                /*$return=$this->isMainQuizAvailable(2,3);*/
              
                
            

        if (empty($course)) {
            throw new Exception('Invalid Course or Course Removed!', 404);
        }

        if (empty($modules)) {
            throw new Exception('No Module found for Course ' . $course->name . '!');
        }

        return view('front.test.loggedin', ['course' => $course, 'modules' => $modules, 'is_accessable' => $is_accessable,'percentage'=>$_percentage]);
    }

    /**
     * 
     * @param type $request
     * @param type $course_slug
     * @return type
     * @throws Exception
     */
    protected function nonLoggedIn($request, $course_slug) {
        $course = (new Course())->getFromSlug($course_slug);
        $modules = CourseTestsRelation::where('course_id', '=', $course->id)
                ->where('status', '=', 1)
                ->with(['module' => function($query) {
                        $query->with('chapters');
                    }])
                ->get();

        if (empty($course)) {
            throw new Exception('Invalid Course or Course Removed!', 404);
        }

        if (empty($modules)) {
            throw new Exception('No Module found for Course ' . $course->name . '!');
        }

        return view('front.test.nologgedin', ['course' => $course, 'modules' => $modules]);
    }
    protected function chapterProgress($chapters,$user_id)
    {

          $completed_topic=[];
                $i=0;
                foreach ($chapters as $chapter ) {
                   if(isset($chapter['module']['chapters']) && !empty($chapter['module']['chapters']))
                   {
                        

                        foreach ($chapter['module']['chapters'] as $ch) {

                           $complete=$this->completeTopic($ch['id'],$user_id,$chapter['course_id'],$chapter['test_id']);

                           $mainquiz=$this->isMainQuizAvailable($ch['id'],$user_id,$chapter['course_id'],$chapter['test_id']);
                            $completed_topic[$i]['chapter_id']=$ch['id'];
                            $completed_topic[$i]['topic']=$complete;
                           $completed_topic[$i]['mainquiz']=$mainquiz;


                            $i++;
                            
                        }
                        
                   }
                   
                }

               
                $_percentage=[];
                
                foreach($completed_topic as $percentage)
                {
                  
                   $passed=count($percentage['topic']['completed']);
                    $total=count($percentage['topic']['total']);

                    $mini_quiz_progress=0;
                    $main_quiz_progress=0;
                    if($total>0)
                    {
                        $mini_quiz_progress=($passed/$total)*75;
                    }
                    if($percentage['mainquiz']!=0)
                    {
                        $main_quiz_progress=25;
                    }
                    $_percentage[$percentage['chapter_id']]=$mini_quiz_progress+$main_quiz_progress;
                   
                }
                return $_percentage;
    }
    protected function isMainQuizAvailable($chapter_id, $user_id,$course_id,$module_id){
        $total_topics = (new Chapter())->getTopicIds($chapter_id);
        $chapter_status = (new UserTestAttempts())->getMainQuizStatus($chapter_id, $user_id,$course_id,$module_id);
        return $chapter_status;
       
    }

    protected function completeTopic($chapter_id, $user_id,$course_id,$module_id){
        
        $total_topics = (new Chapter())->getTopicIds($chapter_id);
        
        $passed_topics = (new UserTopicQuiz())->getCompleteTopicIds($chapter_id, $user_id,$course_id,$module_id);
        
        $passed=[];
     
        
        foreach($total_topics as $t){
            if(in_array($t, $passed_topics)){
                $passed[]=$t;
               
            }
            

        }
        return array('completed'=>$passed,'total'=>$total_topics);
    }

}
