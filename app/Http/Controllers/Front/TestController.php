<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\FrontController;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Course;
use App\Models\Question;
use App\Models\UserTestAttempts;
use App\Models\UserQuestionOptionsAnswers;
use Illuminate\Support\Facades\DB;

class TestController extends FrontController
{
    /**
     * 
     * @param Request $request
     * @param type $course_slug
     * @param type $test_slug
     */
    public function answerSubmit(Request $request, $course_slug,$test_slug){
        try{
            
            $user_id = $this->auth::id();
            DB::beginTransaction();
            
            $session = [];
        
            if ($request->session()->has('logged_in_courses')) {
                $session = $request->session()->get('logged_in_courses');
            }
            
            $course = Course::where('slug','=',$course_slug)->first();
            
            if(empty($course)){
                throw new \Exception('Invalid Course Selected!');
            }
            
            if($course->is_archive == '1'){
                throw new \Exception('Invalid Course Selected!');
            }
            
            if(!in_array($course->id, $session)){
                return redirect()->route('front.course.list')->with('warning', 'Login to Courses!');
            }
            
            $test = Test::where('slug','=',$test_slug)
                    ->with(['questions' => function($query){
                        $query->with(['options_answers' => function($query){
                            $query->where('is_archive','=','0');
                        }]);
                    }])
                    ->first();
            
            if(empty($test)){
                throw new \Exception('Invalid Test/Chapter!');
            }
            
            if($test->is_archive == '1'){
                throw new \Exception('Invalid Test/Chapter!');
            }
            
            $user_questions = $request->input('question');
            $questions = Question::where('test_id','=',$test->id)
                    ->where('is_archive','=','0')
                    ->where('is_locked','=','1')->get();
            
            $questionsIds = (function() use ($questions){
                if(sizeof($questions) > 0){
                    foreach($questions as $q){
                        $questionsIds[] = $q->id;
                    }
                }
                return $questionsIds;
            })();
            
            $questionsAnswers = (function() use ($user_questions){
                $questionsAnswers = [];
                if(sizeof($user_questions) > 0){
                    foreach($user_questions as $k => $q){
                        $questionsAnswers[$k] = $q['option_id'];
                    }
                }
                return $questionsAnswers;
            })();
            
            //print_r($questionsAnswers); exit; 
            
            $question_details = Question::whereIn('id',$questionsIds)
                    ->with(['options_answers' => function($query){
                        $query->where('is_archive','=',0);
                    }])
                    ->get();
                    
            $attempt = new UserTestAttempts();
            $attempt->user_id = $user_id;
            $attempt->course_id = $course->id;
            $attempt->test_id = $test->id;
            $attempt->time = date('Y-m-d H:i:s');
            $attempt->attempt = '0';
            $attempt->score = '0';
            $attempt->created_at = date('Y-m-d H:i:s');
            $attempt->updated_at = date('Y-m-d H:i:s');
            $attempt->save();
            
            $answers = [];
            foreach($question_details as $qa){
                $oa = $qa->options_answers;
                $type = $qa->type;
                $user_answer_ids = isset($questionsAnswers[$qa->id]) ? $questionsAnswers[$qa->id] : [];
                $right_options = (function() use($oa){
                    $options = [];
                    foreach($oa as $o){
                        if($o->answer_boolean == '1'){
                            $options[] = $o->id;
                        }
                    }

                    return implode(',', $options);
                })();
                $answers[] = [
                    'user_id' => $user_id,
                    'course_id' => $course->id,
                    'test_id' => $test->id,
                    'attempt_id' => $attempt->id,
                    'is_archive' => 0,
                    'answer_boolean' => 0,
                    'answer_string' =>'',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'question_id' => $qa->id,
                    'type' => $qa->type,
                    'question_ids' => implode(',', $user_answer_ids),
                    'right_options' => $right_options,
                    'percentage' => '0',
                    'match' => (function () use ($right_options, $type, $user_answer_ids){
                        switch($type){
                            case 'multiple_choice':
                                $right_options = explode(',', $right_options);
                                if($right_options === array_intersect($right_options, $user_answer_ids) && $user_answer_ids === array_intersect($user_answer_ids, $right_options)) {
                                    return 1;
                                } else {
                                    return 0;
                                }
                                break;
                            
                            case 'true_false':
                                if($right_options == implode(',',$user_answer_ids))
                                    return 1;
                                else
                                    return 0;
                                break;
                            
                            case 'yes_no':
                                if($right_options == implode(',',$user_answer_ids))
                                    return 1;
                                else
                                    return 0;
                                break;
                            
                            case 'single_choice':
                                if($right_options == implode(',',$user_answer_ids))
                                    return 1;
                                else
                                    return 0;
                                break;
                        }
                    })()
                ];
            }
            
            UserQuestionOptionsAnswers::insert($answers);
            
            DB::commit();
            return redirect()->route('front.course.list')->with('success', 'Test '.$test->name.' for course '.$course->name.' submitted Successfully!');   
            
        }catch(\Exception $ex){
            echo $ex->getMessage();
            DB::rollBack();
        }
    }

    public function attempt(Request $request, $course_slug,$test_slug) {
        $session = [];
        
        if ($request->session()->has('logged_in_courses')) {
            $session = $request->session()->get('logged_in_courses');
        }
        
        try{
            $course = Course::where('slug','=',$course_slug)->first();
            
            if(empty($course)){
                throw new \Exception('Invalid Course Selected!');
            }
            
            if($course->is_archive == '1'){
                throw new \Exception('Invalid Course Selected!');
            }
            
            if(!in_array($course->id, $session)){
                return redirect()->route('front.course.list')->with('warning', 'Login to Courses!');
            }
            
            $test = Test::where('slug','=',$test_slug)
                    ->with(['questions' => function($query){
                        $query->with(['options_answers' => function($query){
                            $query->where('is_archive','=','0');
                        }]);
                    }])
                    ->first();
            
            if(empty($test)){
                throw new \Exception('Invalid Test/Chapter!');
            }
            
            if($test->is_archive == '1'){
                throw new \Exception('Invalid Test/Chapter!');
            }
            
            
                   
            return view('front.test.home', ['test_slug' => $test_slug, 'course_slug' => $course_slug, 'test' => $test]);   
        }catch(\Exception $ex){
            return view('front.test.home', ['error' => $ex->getMessage()]);   
        }
    }
}
