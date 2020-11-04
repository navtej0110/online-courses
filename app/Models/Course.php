<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CourseTestsRelation;

class Course extends Model
{
    protected $table = "courses";
    
    public function modules(){
        return $this->hasMany('App\Models\CourseTestsRelation', 'course_id', 'id')->where('status','=',1);
    }
    
    public function categories(){
        return $this->hasMany('App\Models\CourseCategory', 'course_id', 'id')->where('is_archive','=',1);
    }
    public function payment(){
        return $this->hasMany('App\userCoursePayment', 'course_id', 'id')->select('id','user_id','course_id');
    }
    
    /**
     * 
     * @param type $course_slug
     * @return int
     */
    public function getFromSlug($course_slug) {
        $course = $this::where('is_archive', '=', '0')
                ->where('slug', '=', $course_slug)
                ->with('categories')->first();
        
        if(empty($course)){
            return 0;
        }
        
        return $course;
    }
    
    /**
     * 
     * @param type $course_id
     * @return type
     */
    public function courseModuleFullInformations($course_id){
        $info = CourseTestsRelation::where('status','=','1')
                ->where('course_id','=',$course_id)
                ->with(['module' => function($query){
                    $query->with(['chapters' => function($query){
                        $query->with(['topics' => function($query){
                            $query->with(['mini_quiz' => function($query){
                                $query->with('options');
                            }]);
                        }]);
                    }]);
                }])
                ->with('course')
                ->get();
        
        return $info->toArray();
    }

}
