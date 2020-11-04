<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chapter;
use App\Models\UserTopicQuiz;
use App\Models\UserTestAttempts;

class FrontController extends Controller
{
    protected $auth;
    
    public function __construct(Auth $auth){
        $this->auth = $auth;
    }
     public function chapterPercentage($chapters,$user_id,$course_id,$module_id)
    	{
    	
    			
          		$completed_topic=[];
                $i=0;
                foreach ($chapters as $ch ) 
                	{
						$complete=$this->completedTopic($ch['id'],$user_id,$course_id,$module_id);
						
						$mainquiz=$this->isMainQuizExist($ch['id'],$user_id,$course_id,$module_id);
                        $completed_topic[$i]['course_id']=$course_id;
                        $completed_topic[$i]['module_id']=$module_id;
                        $completed_topic[$i]['chapter_id']=$ch['id'];
						$completed_topic[$i]['topic']=$complete;
                        $completed_topic[$i]['mainquiz']=$mainquiz;
						$i++;
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
                    $_percentage[]=$mini_quiz_progress+$main_quiz_progress;
                  }
                return $_percentage;
    }
    public function isMainQuizExist($chapter_id, $user_id,$course_id,$module_id){
        $total_topics = (new Chapter())->getTopicIds($chapter_id);
        $chapter_status = (new UserTestAttempts())->getMainQuizStatus($chapter_id, $user_id,$course_id,$module_id);
        return $chapter_status;
       
    }

    public function completedTopic($chapter_id, $user_id,$course_id,$module_id){
        
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
