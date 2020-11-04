<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FrontController;
use App\Models\UserChaptersStatus;
use App\Models\UserModulesStatus;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Chapter;
use App\Models\Question;
use App\Models\UserTopicQuiz;
use Illuminate\Support\Facades\DB;

/**
 * Description of TopicController
 *
 * @author developer pc
 */
class TopicController extends FrontController {

    protected function isMainQuizAvailable($chapter_id, $user_id){
        $total_topics = (new Chapter())->getTopicIds($chapter_id);
        $passed_topics = (new UserTopicQuiz())->getPassedTopicIds($chapter_id, $user_id);
        
        if(sizeof($total_topics) <= 0){
            return 0;
        }
        
        foreach($total_topics as $t){
            if(!in_array($t, $passed_topics)){
                return 0;
            }
        }
        
        return 1;
    }
    protected function nextMiniTopicLink($chapter_id, $user_id){
        $total_topics = (new Chapter())->getTopicIds($chapter_id);
        $passed_topics = (new UserTopicQuiz())->getPassedTopicIds($chapter_id, $user_id);
        if(sizeof($total_topics) <= 0){
            return 0;
        }
        
        foreach($total_topics as $t){
            if(!in_array($t, $passed_topics)){
                return $t;
            }
        }
        return 0;
    }
    /**
     * 
     * @param Request $request
     * @param type $course_slug
     * @param type $module_slug
     * @param type $chapter_slug
     * @param type $topic_id
     * @return type
     * @throws \Exception
     */
    public function index(Request $request, $course_slug, $module_slug, $chapter_slug, $topic_id) {
        try {
            $user_id = $this->auth::id();

            $course = $request->attributes->get('course');
            $module = $request->attributes->get('module');
            $chapter = $request->attributes->get('chapter');
            $topic = Topic::where('id', '=', $topic_id)
                            ->where('chapter_id', '=', $chapter->id)
                            ->where('is_archive', '=', '0')->first();

            if (empty($topic)) {
                throw new \Exception("Invalid Lesson or Topic not Found!");
            }

            (new UserModulesStatus())->startModule($user_id, $course->id, $module->id);
            (new UserChaptersStatus())->startChapter($user_id, $course->id, $module->id, $chapter->id);
            
            $isMainQuizAvailable = $this->isMainQuizAvailable($chapter->id, $user_id);

            $chapter = \App\Models\Chapter::where('id', '=', $chapter->id)
                    ->where('is_archive', '=', 0)
                    ->with(['topics' => function($query) {
                            $query->with(['mini_quiz']);
                        }])
                    ->first();

            foreach ($chapter->topics as $k => $v) {
                $topic->link = $chapter->topics[$k]->link = route('front.topic.index', [
                    'course_slug' => $course->slug,
                    'module_slug' => $module->slug,
                    'chapter_slug' => $chapter->slug,
                    'topic_id' => $v->id
                ]);

                $chapter->topics[$k]->quizLink = route('front.topic.quiz', [
                    'course_slug' => $course->slug,
                    'module_slug' => $module->slug,
                    'chapter_slug' => $chapter->slug,
                    'topic_id' => $v->id
                ]);

                $chapter->topics[$k]->visible = $topic->id == $v->id ? 'visible' : '';
            }

            $topic->quizLink = route('front.topic.quiz', [
                'course_slug' => $course->slug,
                'module_slug' => $module->slug,
                'chapter_slug' => $chapter->slug,
                'topic_id' => $topic->id
            ]);
            
            if(!empty($topic->video_1)){
                $topic->video_1 = '//www.youtube.com/embed/'.$topic->video_1.'?controls=0';
            }
            
            $main_quiz_link = route('front.chapter.quiz', [
                'course_slug' => $course->slug,
                'module_slug' => $module->slug,
                'chapter_slug' => $chapter->slug
            ]);

            //echo '<pre>';
            //print_r($chapter->toArray()); exit;

            return view('front.topic.index', ['chapter' => $chapter, 'module' => $module, 
                'course' => $course, 
                'topic' => $topic,
                'main_quiz_link' => $main_quiz_link,
                'isMainQuizAvailable' => $isMainQuizAvailable ,
                'course_link' => route('course.modules',[
                    'course_slug' => $course->slug,
                ]),
                'chapter_link' => route('front.course.module-chapter',[
                    'course_slug' => $course->slug,
                    'module_slug' => $module->slug,
                    'chapter_slug' => $chapter->slug
                ])
            ]);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * 
     * @param Request $request
     * @param type $course_slug
     * @param type $module_slug
     * @param type $chapter_slug
     * @param type $topic_id
     * @return type
     * @throws \Exception
     */
    public function quiz(Request $request, $course_slug, $module_slug, $chapter_slug, $topic_id) {
        try {
            $user_id = $this->auth::id();

            $course = $request->attributes->get('course');
            $module = $request->attributes->get('module');
            $chapter = $request->attributes->get('chapter');

            $UserTopicQuiz = UserTopicQuiz::where('topic_id', '=', $topic_id)->get();
            if (!empty($UserTopicQuiz) && sizeof($UserTopicQuiz) > 0) {
                $condition = [
                    'id',
                    'option',
                    //DB::raw('answer_boolean as answer'),
                    'answer_boolean as answer',
                    'prefix',
                    'images',
                    //'orderby',
                    'name',
                    'description',
                    'question_id'
                ];
            } else {
                $condition = [
                    'id',
                    'option',
                    DB::raw('0 as answer'),
                    'prefix',
                    'images',
                    //'orderby',
                    'name',
                    'description',
                    'question_id'
                ];
            }

            $topic = Topic::where('id', '=', $topic_id)
                            ->where('chapter_id', '=', $chapter->id)
                            ->with(['user_mini_quiz' => function($query) use($user_id) {
                                    $query->select('topic_id', 'is_correct', 'question_id')->where('user_id', '=', $user_id);
                                }])
                            ->with(['mini_quiz' => function($query) use($condition) {
                                    $query->select(['id', 'name', 'description', 'question_type', 'topic_id', 'quiz_type', 'display_type'])
                                    ->where('is_archive','=','0')
                                    ->with(['options_answers' => function($query) use($condition) {
                                            $query->select($condition);
                                        }]);
                                }])
                            ->where('is_archive', '=', '0')->first();                 
                           
            if (empty($topic)) {
                throw new \Exception("Invalid Lesson or Topic not Found!");
            }
            
            if(!empty($topic->mini_quiz) && sizeof($topic->mini_quiz) > 0){
                if (sizeof($topic->user_mini_quiz) == sizeof($topic->mini_quiz)) {
                    $topic->already_answered = 1;
                } else {
                    $topic->already_answered = 0;
                }
            }

            /* echo '<pre>';
              print_r($topic->toArray());
              exit; */

            (new UserModulesStatus())->startModule($user_id, $course->id, $module->id);
            (new UserChaptersStatus())->startChapter($user_id, $course->id, $module->id, $chapter->id);
            
            $isMainQuizAvailable = $this->isMainQuizAvailable($chapter->id, $user_id);
            $nextMiniTopicLink = $this->nextMiniTopicLink($chapter->id, $user_id);
            
            $topic->next_topic_link ='';
            
            if($nextMiniTopicLink > 0){ 
                foreach ($chapter->topics as $k => $v) {
                $topic->next_topic_link = $chapter->topics[$k]->link = route('front.topic.index', [
                    'course_slug' => $course->slug,
                    'module_slug' => $module->slug,
                    'chapter_slug' => $chapter->slug,
                    'topic_id' => $nextMiniTopicLink
                ]);
            }
        }
        

            $chapter = \App\Models\Chapter::where('id', '=', $chapter->id)
                    ->where('is_archive', '=', 0)
                    ->with(['topics' => function($query) {
                            $query->with(['user_mini_quiz']);
                            $query->with(['mini_quiz']);
                        }])
                    ->first();

            foreach ($chapter->topics as $k => $v) {
                $topic->link = $chapter->topics[$k]->link = route('front.topic.index', [
                    'course_slug' => $course->slug,
                    'module_slug' => $module->slug,
                    'chapter_slug' => $chapter->slug,
                    'topic_id' => $v->id
                ]);

                $chapter->topics[$k]->quizLink = route('front.topic.quiz', [
                    'course_slug' => $course->slug,
                    'module_slug' => $module->slug,
                    'chapter_slug' => $chapter->slug,
                    'topic_id' => $v->id
                ]);

                $chapter->topics[$k]->visible = $topic->id == $v->id ? 'visible' : '';
            }
            
            $topic->quizLink = route('front.topic.quiz', [
                'course_slug' => $course->slug,
                'module_slug' => $module->slug,
                'chapter_slug' => $chapter->slug,
                'topic_id' => $topic->id
            ]);
            
            $main_quiz_link = route('front.chapter.quiz', [
                'course_slug' => $course->slug,
                'module_slug' => $module->slug,
                'chapter_slug' => $chapter->slug
            ]);


            return view('front.topic.quiz', [
                'chapter' => $chapter,
                'module' => $module,
                'course' => $course,
                'topic' => $topic,
                'main_quiz_link' => $main_quiz_link,
                'quiz' => $topic->mini_quiz,
                'topic_link'=>$topic->next_topic_link,
                'isMainQuizAvailable' => $isMainQuizAvailable,
                'chapter_link' => route('front.course.module-chapter',[
                    'course_slug' => $course->slug,
                    'module_slug' => $module->slug,
                    'chapter_slug' => $chapter->slug
                ]),
                'course_link' => route('course.modules',[
                    'course_slug' => $course->slug,
                ]),
                'submitMiniQuiz' => route('front.topic.mini-quiz', [
                    'course_slug' => $course->slug,
                    'module_slug' => $module->slug,
                    'chapter_slug' => $chapter->slug,
                    'topic_id' => $topic->id,
                    
                ])
            ]);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * 
     * @param Request $request
     * @param type $course_slug
     * @param type $module_slug
     * @param type $chapter_slug
     */
    public function submitMiniQuiz(Request $request, $course_slug, $module_slug, $chapter_slug, $topic_id) {
        try {
            
            //print_r($request->all()); exit;
            
            $user_id = $this->auth::id();
            $question = new Question();
            $course = $request->attributes->get('course');
            $module = $request->attributes->get('module');
            $chapter = $request->attributes->get('chapter');
            $topic = Topic::where('id', '=', $topic_id)
                            ->where('chapter_id', '=', $chapter->id)
                            ->where('is_archive', '=', '0')->first();

            if (empty($topic)) {
                throw new \Exception('Invalid Quiz!');
            }
            
            $user_rel = UserTopicQuiz::where('topic_id','=',$topic_id)->where('user_id','=',$user_id)->get();
            
            if(sizeof($user_rel) > 0){
                throw new \Exception('You have already completed this Quiz!');
            }

            $report = $question->checkQuestionAnswerMiniQuiz($request->all(), $user_id, $course, $module, $chapter, $topic->id);
            
            if ($report['success'] == 1 && $report['percentage'] < 100) {
                throw new \Exception('Invalid Answer Please Read Lesson Carefully!');
            }

            return $report;
        } catch (\Exception $ex) {
            return ['success' => 0, 'error' => $ex->getMessage()];
        }
    }

}
