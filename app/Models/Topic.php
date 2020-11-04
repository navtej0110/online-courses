<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $topic = 'topics';
    
    public function chapter(){
        return $this->hasOne('App\Models\Chapter', 'id', 'chapter_id')->where('is_Archive','=',0);
    }
    
    public function mini_quiz(){
        return $this->hasMany('App\Models\Question', 'topic_id', 'id')->where('quiz_type','=','mini_quiz');
    }
    
    public function user_mini_quiz(){
        return $this->hasMany('App\Models\UserTopicQuiz', 'topic_id', 'id');
    }
}
