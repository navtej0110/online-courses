<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Description of CourseTestsRelation
 *
 * @author developer pc
 */
class CourseTestsRelation extends Model{
    
    protected $table = "course_tests_relation";
    
    public function test(){
        return $this->hasOne('App\Models\Test', 'id','test_id');
    }
    
    public function module(){
        return $this->hasOne('App\Models\Test', 'id','test_id');
    }
    
    public function chapters(){
        return $this->hasMany('App\Models\Chapter', 'test_id','test_id');
    }
    
    public function course(){
        return $this->hasOne('App\Models\Course', 'id','course_id');
    }
    public function topic()
    {
        return [];
    }
}
