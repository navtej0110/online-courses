<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModulesStatus extends Model
{
    protected $table = "user_modules_status";
    
    /**
     * 
     * @param type $user_id
     * @param type $course_id
     * @param type $module_id
     * @return int
     */
    public function startModule($user_id, $course_id, $module_id){
        $obj = $this::where('course_id','=',$course_id)
                ->where('user_id','=',$user_id)
                ->where('module_id','=',$module_id)
                ->first();
        
        if(empty($obj)){
            $um = new $this();
            $um->course_id = $course_id;
            $um->module_id = $module_id;
            $um->started = date('Y-m-d H:i:s');
            $um->user_id = $user_id;            
            $um->save();
            return 1;
        }
        
        return 0;
    }
}
