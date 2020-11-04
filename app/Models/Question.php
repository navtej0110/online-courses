<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserTopicQuiz;
use App\Models\UserTestAttempts;
use App\Models\UserQuestionOptionsAnswers;
use Illuminate\Support\Facades\DB;

class Question extends Model {

    protected $table = "questions";

    public function options_answers() {
        return $this->hasMany('App\Models\QuestionOptionsAnswers', 'question_id', 'id')->where('is_Archive', '=', 0);
    }

    public function options() {
        return $this->hasMany('App\Models\QuestionOptionsAnswers', 'question_id', 'id')->where('is_Archive', '=', 0);
    }

    public function answers() {
        return $this->hasMany('App\Models\QuestionOptionsAnswers', 'question_id', 'id')
                        ->where('answer_boolean', '!=', 0)
                        ->where('is_Archive', '=', 0);
    }

    /**
     * 
     * @param type $question_type
     * @param type $user_answers
     * @param type $question_answers
     */
    public function calculateTypeAnswer($question_type, $user_answers, $question_answers) {
        $question_answers = $question_answers->toArray();
        $question_type = $question_type;
        $user_answers = $user_answers;

        // true/false, yes/no
        if ($question_type == 'true_false' || $question_type == 'yes_no') {
            $actual_answer = $question_answers[0]['answer_boolean'];
            $given_answer = $user_answers[0]['answer'];

            return ($actual_answer == $given_answer) ? 1 : 0;

            // for multiselect and single select    
        } else {
            $correct_answers = (function() use ($question_answers) {
                        $a = [];
                        foreach ($question_answers as $k => $qa) {
                            if ($qa['answer_boolean'] == '1') {
                                $a[$qa['id']] = $qa['id'];
                            }
                        }

                        return $a;
                    })();
            sort($correct_answers);

            $correct_user_answers = (function() use ($user_answers) {
                        $a = [];
                        foreach ($user_answers as $k => $qa) {
                            if ($qa['answer'] == '1') {
                                $a[$qa['id']] = $qa['id'];
                            }
                        }

                        return $a;
                    })();
            sort($correct_user_answers);

            return ($correct_answers === $correct_user_answers ? 1 : 0);
        }
    }

    /**
     * 
     * @param type $request
     * @param type $course
     * @param type $module
     * @param type $chapter
     * @param type $topic_id
     * @throws \Exception
     */
    public function checkQuestionAnswerMiniQuiz($request, $user_id, $course, $module, $chapter, $topic_id) {
        try {
            if (sizeof($request) <= 0) {
                throw new \Exception('Quiz not Found!');
            }

            // check chapter questions.
            $Submitted_questions = (function() use ($request) {
                        $q = [];
                        foreach ($request as $r) {
                            $q[] = $r['id'];
                        }
                        return $q;
                    })();
            sort($Submitted_questions);

            $topic_questions = $this::where('topic_id', '=', $topic_id)
                    ->where('is_archive', '=', '0')
                    ->where('quiz_type', '=', 'mini_quiz')
                    ->with('options_answers')
                    ->get();

            $topic_questions_only = (function() use($topic_questions) {
                        $q = [];
                        foreach ($topic_questions as $tq) {
                            $q[] = $tq->id;
                        }
                        return $q;
                    })();
            sort($topic_questions_only);

            if (!$Submitted_questions === $topic_questions_only) {
                throw new \Exception('Invalid Questions Provided for Qopic Quiz!');
            }

            $calculative = [];
            $correct = [];
            $wrong = [];
            $saveData = [];

            DB::beginTransaction();

            foreach ($topic_questions as $tq) {
                $type = $tq->question_type;
                foreach ($request as $r) {
                    if ($r['id'] == $tq->id) {
                        $result = $this->calculateTypeAnswer($type, $r['options_answers'], $tq['options_answers']);
                        if ($result === 1) {
                            $correct[] = $tq->id;
                            $saveData[] = [
                                'course_id' => $course->id,
                                'module_id' => $module->id,
                                'chapter_id' => $chapter->id,
                                'topic_id' => $topic_id,
                                'question_id' => $tq->id,
                                'is_correct' => 1,
                                'user_id' => $user_id,
                                'options_answers' => json_encode($tq['options_answers']),
                                'answered' => json_encode($r['options_answers']),
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                        } else {
                            $wrong[] = $tq->id;
                        }
                        $calculative[$tq->id] = $this->calculateTypeAnswer($type, $r['options_answers'], $tq['options_answers']);
                    }
                }
            }

            $percentage = (count($correct) / count($topic_questions) * 100);

            if ($percentage >= 100) {
                UserTopicQuiz::insert($saveData);
            }

            DB::commit();

            return ['success' => 1, 'report' => $calculative, 'correct' => $correct, 'wrong' => $wrong, 'percentage' => $percentage];
        } catch (\Exception $ex) {
            DB::rollBack();
            return ['success' => 0, 'error' => $ex->getMessage()];
        }
    }

    /**
     * 
     * @param type $request
     * @param type $course
     * @param type $module
     * @param type $chapter
     * @param type $topic_id
     * @throws \Exception
     */
    public function checkQuestionAnswerMainQuiz($request, $user_id, $course, $module, $chapter) {
        $passing_grades = $chapter->minimum_passing_grades;
        try {
            if (sizeof($request) <= 0) {
                throw new \Exception('Quiz not Found!');
            }

            // check chapter questions.
            $Submitted_questions = (function() use ($request) {
                        $q = [];
                        foreach ($request as $r) {
                            $q[] = $r['id'];
                        }
                        return $q;
                    })();
            sort($Submitted_questions);

            $chapter_questions = $this::where('chapter_id', '=', $chapter->id)
                    ->where('is_archive', '=', '0')
                    ->where('quiz_type', '=', 'main_quiz')
                    ->with('options_answers')
                    ->get();


            $chapter_questions_only = (function() use($chapter_questions) {
                        $q = [];
                        foreach ($chapter_questions as $tq) {
                            $q[] = $tq->id;
                        }
                        return $q;
                    })();
            sort($chapter_questions_only);

            /**
             * check if any invalid question provided during quiz submission.
             */
            foreach ($Submitted_questions as $sq) {
                if (!in_array($sq, $chapter_questions_only)) {
                    throw new \Exception('Invalid Questions Provided in Quiz!');
                }
            }

            $calculative = [];
            $correct = [];
            $wrong = [];
            $saveData = [];

            $chapter_questions = $chapter_questions;
            $questions = (function() use($chapter_questions, $Submitted_questions) {
                        $questions = [];
                        foreach ($chapter_questions as $cq) {
                            if (in_array($cq->id, $Submitted_questions)) {
                                $questions[] = $cq;
                            }
                        }
                        return $questions;
                    })();

            DB::beginTransaction();

            foreach ($questions as $tq) {
                $type = $tq->question_type;
                foreach ($request as $r) {
                    if ($r['id'] == $tq->id) {
                        $result = $this->calculateTypeAnswer($type, $r['options_answers'], $tq['options_answers']);
                        
                        if ($result === 1) {
                            $correct[] = $tq->id;
                        }
                        if ($result === 0) {
                            $wrong[] = $tq->id;
                        }
                        
                        if ($result === 1 || $result === 0) {
                            $saveData[] = [
                                'course_id' => $course->id,
                                'module_id' => $module->id,
                                'chapter_id' => $chapter->id,
                                'question_id' => $tq->id,
                                'match' => $result,
                                'percentage' => $result == 1 ? 100 : 0,
                                'user_id' => $user_id,
                                'submitted_options' => json_encode($r['options_answers']),
                                'right_options' => json_encode($tq['options_answers']),
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'answer_string' => "",
                                //'attempt_id' => 1,
                                'type' => $type
                            ];
                        }
                        $calculative[$tq->id] = $this->calculateTypeAnswer($type, $r['options_answers'], $tq['options_answers']);
                    }
                }
            }

            $percentage = (count($correct) / count($questions) * 100);
            $percentage = round($percentage);

            if ($percentage >= 0) {
                UserTestAttempts::where('user_id','=',$user_id)
                        ->where('user_id','=',$user_id)
                        ->where('course_id','=',$course->id)
                        ->where('module_id','=',$module->id)
                        ->where('chapter_id','=',$chapter->id)->update(['is_current' => 0]);
                
                $at = new UserTestAttempts();
                $at->user_id = $user_id;
                $at->course_id = $course->id;
                $at->time = date('Y-m-d H:i:s');
                $at->created_at = date('Y-m-d H:i:s');
                $at->updated_at = date('Y-m-d H:i:s');
                $at->module_id = $module->id;
                $at->chapter_id = $chapter->id;
                $at->score = $percentage;
                $at->attempt = 0;
                $at->is_passed = $percentage >= $passing_grades ? 1 : 0;
                $at->is_current = 1;
                $at->save();
                
                $convert = [];
                foreach($saveData as $k => $s){
                    $s['attempt_id'] = $at->id;
                    $convert[$k] = $s;
                }
                UserQuestionOptionsAnswers::insert($convert);
            }

            DB::commit();

            return ['success' => 1, 'report' => $calculative, 'correct' => $correct, 'wrong' => $wrong, 'percentage' => $percentage, 'attempt_id' => $at->id];
        } catch (\Exception $ex) {
            DB::rollBack();
            return ['success' => 0, 'error' => $ex->getMessage(), 'line' => $ex->getLine()];
        }
    }

}
