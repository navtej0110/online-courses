<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Course;

/**
 * Description of UserCourses
 *
 * @author developer pc
 */
class UserCourses extends Model {

    protected $table = 'user_courses';
    protected $fillable = ['user_id','course_id','course_name','password'];

    public function course() {
        return $this->hasOne('App\Models\Course', 'id', 'course_id');
    }

    public function tests() {
        return $this->hasOne('App\Models\CourseTestsRelation', 'course_id', 'course_id')->where('status', '=', '1');
    }
    
    /**
     * 
     * @param type $user_id
     * @param type $course_id
     */
    public function checkAccess($user_id, $course_id){
        $access = $this::where('user_id','=',$user_id)
                ->where('course_id','=',$course_id)
                //->where('is_archive','=','0')
                ->first();
        
        if(empty($access)){
            return false;
        }
        
        return true;
    }
    
    /**
     * 
     * @param type $user_id
     * @param type $course_id
     * @return int
     */
    public function startCourse($user_id, $course_id) {
        $course = $this::where('course_id','=',$course_id)->where('user_id','=',$user_id)->first();
        
        if(empty($course->started)){
            $course->started = date('Y-m-d H:i:s');
            $course->save();
            
            return 1;
        }
        
        return 0;
    }

    public function addUserCourse($course_id,$user_id)
    {
       
       $data= $this->create(array(
            'user_id' =>$user_id,
            'course_id'  => $course_id,
            'course_name'=>'',
            'password'=>'',
            
        ));
        return $data;
    }

}
