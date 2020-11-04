<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTopicQuiz extends Model
{
    protected $table = "user_topic_quiz";
    
    public function getPassedTopics($chapter_id, $user_id){
        $topics = $this::where('chapter_id','=',$chapter_id)
                ->where('user_id','=',$user_id)
                ->where('is_correct','=','1')
                ->get();
        
        return $topics;
    }
    
    /**
     * 
     * @param type $chapter_id
     * @param type $user_id
     * @return type
     */
    public function getPassedTopicIds($chapter_id, $user_id){
        $topics = $this::getPassedTopics($chapter_id, $user_id);
        
        $ids = [];
        if(empty($topics)){
           return []; 
        }
        
        foreach($topics as $t){
            $ids[] = $t->topic_id;
        }
        
        return $ids;
    }

    public function getCompleteTopicIds($chapter_id,$user_id,$course_id,$module_id)
    {
    	$topics = $this::getCompletedTopics($chapter_id, $user_id,$course_id,$module_id);
        
        $ids = [];
        if(empty($topics)){
           return []; 
        }
        
        foreach($topics as $t){
            $ids[] = $t->topic_id;
        }
        
        return $ids;
    }
    public function getCompletedTopics($chapter_id, $user_id,$course_id,$module_id){
        $topics = $this::where('chapter_id','=',$chapter_id)->where('course_id','=',$course_id)->where('module_id','=',$module_id)
                ->where('user_id','=',$user_id)
                ->where('is_correct','=','1')
                ->get();
        
        return $topics;
    }
    
}
