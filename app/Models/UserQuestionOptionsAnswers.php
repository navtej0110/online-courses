<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQuestionOptionsAnswers extends Model
{
    protected $table = "users_question_options_answers";
    
    public function question() {
        return $this->hasOne('App\Models\Question', 'id', 'question_id');
    }

}
