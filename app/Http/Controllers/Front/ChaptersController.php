<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FrontController;
use App\Models\UserChaptersStatus;
use App\Models\UserModulesStatus;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Question;
use App\Models\UserTestAttempts;
use App\Models\UserTopicQuiz;
use Illuminate\Support\Facades\DB;

class ChaptersController extends FrontController {

    
    /**
     * 
     * @param type $chapter_id
     * @param type $user_id
     * @return int
     */
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
    
    /**
     * 
     * @param type $course_slug
     * @param type $module_slug
     * @param type $chapter_slug
     */
    public function index(Request $request, $course_slug, $module_slug, $chapter_slug) {
        try {
            $user_id = $this->auth::id();

            $course = $request->attributes->get('course');
            $module = $request->attributes->get('module');
            $chapter = $request->attributes->get('chapter');
            
            
            $isMainQuizAvailable = $this->isMainQuizAvailable($chapter->id, $user_id);
            
            (new UserModulesStatus())->startModule($user_id, $course->id, $module->id);

            (new UserChaptersStatus())->startChapter($user_id, $course->id, $module->id, $chapter->id);

            $chapter = \App\Models\Chapter::where('id', '=', $chapter->id)
                    ->where('is_archive', '=', 0)
                    ->with(['topics' => function($query) {
                            $query->with(['mini_quiz']);
                        }])
                    ->first();

            $i = 0;  
            $first_topic = '';
            
            if(sizeof($chapter->topics) > 0){
                foreach ($chapter->topics as $k => $v) {
                    $chapter->topics[$k]->link = route('front.topic.index', [
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

                    if($i == 0){
                        $first_topic = $v->id;
                    }
                    $i++;
                }
            }
            
            $mini_quiz_link = !empty($first_topic) ? route('front.topic.quiz', [
                'course_slug' => $course->slug,
                'module_slug' => $module->slug,
                'chapter_slug' => $chapter->slug,
                'topic_id' => $first_topic
            ]): "";
            
            return view('front.chapter.index', [
                'chapter' => $chapter,
                'module' => $module,
                'course' => $course,
                'isMainQuizAvailable' => $isMainQuizAvailable,
                'main_quiz_link' => route('front.chapter.quiz', [
                    'course_slug' => $course->slug,
                    'module_slug' => $module->slug,
                    'chapter_slug' => $chapter->slug,
                ]),
                'mini_quiz' => $mini_quiz_link,
                'chapter_link' => route('front.course.module-chapter',[
                    'course_slug' => $course->slug,
                    'module_slug' => $module->slug,
                    'chapter_slug' => $chapter->slug
                ]),
                'course_link' => route('course.modules',[
                    'course_slug' => $course->slug,
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
     * @return type
     */
    public function quiz(Request $request, $course_slug, $module_slug, $chapter_slug) {
        try {
            $user_id = $this->auth::id();

            $course = $request->attributes->get('course');
            $module = $request->attributes->get('module');
            $chapter = $request->attributes->get('chapter');

            $number_of_questions = $chapter->number_of_question_in_quiz;
            $number_of_previous_questions = 0;
            $reject_previous_questions = $previous_questions = $occupy_previous_questions = [];
            $offset = 0;
            $previous_half_questions = [];

            $user_attempts = UserTestAttempts::where('user_id', '=', $user_id)
                    ->where('course_id', '=', $course->id)
                    ->where('module_id', '=', $module->id)
                    ->where('chapter_id', '=', $chapter->id)
                    ->where('is_current', '=', 1)
                    ->with(['questions' => function($query){
                        $query->with('question');
                    }])
                    ->first();

            if (!empty($user_attempts) && $user_attempts->is_passed == '1') {
                return $this->displayPassedQuiz($user_attempts, $course, $module, $chapter);
                exit;
            }
            
            $isMainQuizAvailable = $this->isMainQuizAvailable($chapter->id, $user_id);

            if($isMainQuizAvailable == '0'){
                throw new \Exception('Please Complete all Topics to access Chapter Main Quiz!');
            }
            
            if (!empty($user_attempts)) {
                foreach ($user_attempts->questions as $q) {
                    $previous_questions[$q->question_id] = $q->question_id;
                }
            }

            if (sizeof($previous_questions) > 0) {
                $occupy_previous_questions = array_rand($previous_questions, round(count($previous_questions) / 2));
                $occupy_previous_questions = !is_array($occupy_previous_questions) ? [$occupy_previous_questions] : $occupy_previous_questions;
                
                $previous_question_data = Question::where('chapter_id', '=', $chapter->id)
                        ->where('quiz_type', '=', 'main_quiz')
                        ->where('is_archive', '=', '0')
                        ->whereIn('id', $occupy_previous_questions)
                        ->with(['options_answers' => function($query) {
                                $query->select([
                                    'id',
                                    'option',
                                    DB::raw('0 as answer'),
                                    'prefix',
                                    'images',
                                    //'orderby',
                                    'name',
                                    'description',
                                    'question_id'
                                ]);
                            }])
                        ->get();

                $number_of_questions = $number_of_questions - (count($previous_question_data));
            }

            if (sizeof($occupy_previous_questions) > 0) {
                $questions = Question::where('chapter_id', '=', $chapter->id)
                        ->where('quiz_type', '=', 'main_quiz')
                        ->where('is_archive', '=', '0')
                        ->whereNotIn('id', $previous_questions)
                        ->with(['options_answers' => function($query) {
                                $query->select([
                                    'id',
                                    'option',
                                    DB::raw('0 as answer'),
                                    'prefix',
                                    'images',
                                    //'orderby',
                                    'name',
                                    'description',
                                    'question_id'
                                ]);
                            }])
                        ->limit($number_of_questions)
                        ->orderBy('id', 'ASC')
                        ->get();
                            
                    if (sizeof($previous_question_data) > 0) {
                    foreach ($previous_question_data as $pqd) {
                        $questions[] = $pqd;
                    }
                }
            } else {
                $questions = Question::where('chapter_id', '=', $chapter->id)
                        ->where('quiz_type', '=', 'main_quiz')
                        ->where('is_archive', '=', '0')
                        ->with(['options_answers' => function($query) {
                                $query->select([
                                    'id',
                                    'option',
                                    DB::raw('0 as answer'),
                                    'prefix',
                                    'images',
                                    //'orderby',
                                    'name',
                                    'description',
                                    'question_id'
                                ]);
                            }])
                        ->limit($chapter->number_of_question_in_quiz)
                        ->get();
            }

            return view('front.chapter.quiz', [
                'chapter' => $chapter,
                'module' => $module,
                'course' => $course,
                'questions' => $questions,
                'submit_quiz' => route('front.chapter.submit-quiz', [
                    'course_slug' => $course->slug,
                    'module_slug' => $module->slug,
                    'chapter_slug' => $chapter->slug
                ]),
                'chapter_link' => route('front.course.module-chapter',[
                    'course_slug' => $course->slug,
                    'module_slug' => $module->slug,
                    'chapter_slug' => $chapter->slug
                ]),
                'course_link' => route('course.modules',[
                    'course_slug' => $course->slug,
                ]),
            ]);
        } catch (\Exception $ex) {
            echo '<h1><center>'.$ex->getMessage().'</center></h1>';
        }
    }

    /**
     * 
     * @param type $user_attempts
     * @param type $course
     * @param type $module
     * @param type $chapter
     * @return type
     */
    public function displayPassedQuiz($user_attempts, $course, $module, $chapter) {
        $converted = [];
        $correct = [];
        if (!empty($user_attempts) > 0) {
            foreach ($user_attempts['questions'] as $ua) {
                $ua['right_options'] = json_decode($ua['right_options']);
                $ua['submitted_options'] = json_decode($ua['submitted_options']);
                $converted['questions'][] = $ua;
                
                if($ua->match == 1){
                    $correct[] = $ua['question_id'];
                }
            }
        }

        return view('front.chapter.passed', [
            'chapter' => $chapter,
            'module' => $module,
            'course' => $course,
            'attempt' => $user_attempts,
            'attempts' => $converted,
            'correct' => $correct
        ]);
    }

    /**
     * 
     * @param Request $request
     * @param type $course_slug
     * @param type $module_slug
     * @param type $chapter_slug
     * @return type
     */
    public function submitQuiz(Request $request, $course_slug, $module_slug, $chapter_slug) {
        try {
            $user_id = $this->auth::id();

            $course = $request->attributes->get('course');
            $module = $request->attributes->get('module');
            $chapter = $request->attributes->get('chapter');

            $user_attempts = UserTestAttempts::where('user_id', '=', $user_id)
                    ->where('course_id', '=', $course->id)
                    ->where('module_id', '=', $module->id)
                    ->where('chapter_id', '=', $chapter->id)
                    ->where('is_current', '=', 1)
                    ->with(['questions' => function($query){
                        $query->with('question');
                    }])
                    ->first();
                    
            if(!empty($user_attempts) && $user_attempts->is_passed == '1'){
                throw new \Exception('You have already Cleared this Quiz!');
            }        
            
            $question = new Question();
            $report = $question->checkQuestionAnswerMainQuiz($request->all(), $user_id, $course, $module, $chapter);

            return $report;
        } catch (\Exception $ex) {
            return ['success'=>0, 'error'=>$ex->getMessage()];
        }
    }

}
