<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Topic;

class Chapter extends Model
{
    protected $table = "chapters";
    
    public function module(){
        return $this->hasOne('App\Models\Test', 'id', 'test_id')->where('is_Archive','=',0);
    }
    
    public function topics(){
        return $this->hasMany('App\Models\Topic', 'chapter_id', 'id')->where('is_Archive','=',0);
    }
    
    public function getTopics($chapter_id){
        $topics = Topic::where('chapter_id','=',$chapter_id)
                ->where('is_archive','=','0')
                ->get();
        
        return $topics;
    }
    
    /**
     * 
     * @param type $chapter_id
     * @return type
     */
    public function getTopicIds($chapter_id){
        $topics = $this::getTopics($chapter_id);
        
        $ids = [];
        if(empty($topics)){
           return []; 
        }
        
        foreach($topics as $t){
            $ids[] = $t->id;
        }
        
        return $ids;
    }
}
