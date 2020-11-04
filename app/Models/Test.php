<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = "tests";
    
    public function courses(){
        return $this->hasMany('App\Models\CourseTestsRelation', 'test_id', 'id')->where('status','=',1);
    }
    
    public function chapters(){
        return $this->hasMany('App\Models\Chapter', 'test_id', 'id')->where('is_archive','=',0);
    }
}
