<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTestAttempts extends Model
{
    protected $table = "user_test_attempts";
    
    /**
     * 
     * @return type
     */
    public function questions() {
        return $this->hasMany('App\Models\UserQuestionOptionsAnswers', 'attempt_id', 'id');
    }

    public function getMainQuizStatus($chapter_id,$user_id,$course_id,$module_id)
    {
    	 $topics = $this::where('user_id','=',$user_id)
                ->where('chapter_id','=',$chapter_id)
                ->where('is_passed','=','1')
                ->where('module_id','=',$module_id)
                ->where('course_id','=',$course_id)
                ->get()->toArray();
                
         if(!empty($topics))
         {
         	return 1;
         }
        
       return 0;
    }
}
