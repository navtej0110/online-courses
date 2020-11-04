<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Question;
use App\Models\UserTestAttempts;
use App\Models\UserQuestionOptionsAnswers;
use App\Models\QuestionOptionsAnswers;
use Illuminate\Support\Facades\DB;

class QuestionBankController extends AdminController {

    public function index(Request $request) {
        try {

            $user_id = $this->auth::id();
            //DB::beginTransaction();
            //DB::commit();
            return view('admin.questionsbank.index');
        } catch (\Exception $ex) {
            DB::rollBack();
        }
    }

    public function getQuestions(Request $request, $chapter_id) {
        try {
            $chapter = Chapter::where('id', '=', $chapter_id)->first();
            $questions = Question::where('chapter_id', '=', $chapter_id)
                    ->where('quiz_type','=','main_quiz')
                    ->where('is_archive','=',0)
                    ->with('options')
                    ->get();

            return view('admin.questionsbank.chapter', ['records' => $questions, 'chapter' => $chapter]);
        } catch (\Exception $ex) {
            
        }
    }

    public function addChapterQuestions(Request $request, $chapter_id) {
        $chapter = Chapter::where('id', '=', $chapter_id)->first();
        
        return view('admin.questionsbank.index', [
            'chapter_id' => $chapter_id,
            'quizTypes' => json_encode([['name' => 'Main Quiz', 'value' => 'main_quiz']]),
            'question_id' => 0,
            'topic_id' => 0,
            'id' => 0,
            'allow_questions' => 'multiple',
            'chapter' => $chapter,
            'deleteUrl' => route('admin.question-bank.delete', ['chapter_id' => $chapter_id]),
            'getUrl' => '',
            'postUrl' => route('admin.question-bank.addEditChapter', ['chapter_id' => $chapter_id, 'question_id' => ""]),
            'not_found' => 0,
            'is_locked'=>(isset($question['is_locked']) && $question['is_locked']==1)? 'yes':'no'
        ]);
    }

    public function addEditChapter(Request $request, $chapter_id, $question_id = null) {
        try {

            DB::beginTransaction();

            foreach ($request->all() as $r):

                if (empty($question_id)) {
                    $data = new Question();
                } else {
                    $data = Question::where('id', '=', $question_id)->first();
                    if (empty($data)) {
                        throw new \Exception('Invalid Test Selected!');
                    }
                    if ($data->is_locked == 1) {
                        throw new \Exception('The Question is Locked it can not be Edited!');
                    }
                }

                $data->chapter_id = $chapter_id;
                $data->topic_id = 0;
                $data->parent_question_id = 0;

                if (isset($r['name'])) {
                    $data->name = $r['name'];
                }

                if (isset($r['description'])) {
                    $data->description = $r['description'];
                } else {
                    $data->description = "";
                }

                if (isset($r['question_type'])) {
                    $data->question_type = $r['question_type'];
                }

                if (isset($r['is_locked'])) {
                    $data->is_locked = $r['is_locked'];
                } else {
                    $data->is_locked = 0;
                }

                if (isset($r['quiz_type'])) {
                    $data->quiz_type = $r['quiz_type'];
                }

                if (isset($r['question_information'])) {
                    $data->question_information = $r['question_information'];
                }

                if (isset($r['display_type'])) {
                    $data->display_type = $r['display_type'];
                }

                $data->created_by_admin_id = $this->auth::id();

                $data->save();

                $options_data = [];

                if (sizeof($r['options']) <= 0) {
                    
                } else {
                    foreach ($r['options'] as $o) {
                        $options_data[] = [
                            'option' => $o['name'],
                            'answer_boolean' => empty($o['answer_boolean']) ? 0 : $o['answer_boolean'],
                            'status' => 1,
                            'is_archive' => 0,
                            'answer_string' => "",
                            'question_id' => $data->id,
                            'prefix' => $o['prefix'],
                            'images' => $o['images'],
                            'name' => $o['name'],
                            'description' => empty($o['description']) ? "" : $o['description'],
                        ];
                    }
                }

                switch ($r['question_type']) {
                    case 'multiple_choice';
                        (new QuestionOptionsAnswers())->multipleChoice($data->id, $options_data);
                        break;

                    case 'single_choice';
                        (new QuestionOptionsAnswers())->singleChoice($data->id, $options_data);
                        break;

                    case 'true_false';
                        (new QuestionOptionsAnswers())->trueFalse($data->id, $options_data);
                        break;

                    case 'yes_no';
                        (new QuestionOptionsAnswers())->yesNo($data->id, $options_data);
                        break;
                }

            endforeach;

            DB::commit();

            echo json_encode(['success' => 1]);
        } catch (\Exception $ex) {
            DB::rollBack();
            echo json_encode(['success' => 0, 'error' => $ex->getMessage()]);
        }
    }

    public function addEditTopic(Request $request, $chapter_id, $topic_id) {
        try {

            DB::beginTransaction();
            
            foreach ($request->all() as $r):
                $id = $r['id'];
                if (empty($id)) {
                    $data = new Question();
                } else {
                    $data = Question::where('id', '=', $id)
                            ->where('chapter_id','=',$chapter_id)
                            ->where('topic_id','=',$topic_id)
                            ->first();
                    if (empty($data)) {
                        throw new \Exception('Invalid Test Selected!');
                    }
                    if ($data->is_locked == 1) {
                        throw new \Exception('The Question is Locked it can not be Edited!');
                    }
                }

                $data->chapter_id = $chapter_id;
                $data->topic_id = $topic_id;
                $data->parent_question_id = 0;

                if (isset($r['name'])) {
                    $data->name = $r['name'];
                }

                if (isset($r['description'])) {
                    $data->description = $r['description'];
                } else {
                    $data->description = "";
                }

                if (isset($r['question_type'])) {
                    $data->question_type = $r['question_type'];
                }

                if (isset($r['is_locked'])) {
                    $data->is_locked = $r['is_locked'];
                } else {
                    $data->is_locked = 0;
                }

                if (isset($r['quiz_type'])) {
                    $data->quiz_type = $r['quiz_type'];
                }

                if (isset($r['question_information'])) {
                    $data->question_information = $r['question_information'];
                }

                if (isset($r['display_type'])) {
                    $data->display_type = $r['display_type'];
                }

                $data->created_by_admin_id = $this->auth::id();

                $data->save();

                $options_data = [];

                if (sizeof($r['options']) <= 0) {
                    
                } else {
                    //print_r($r['options']); exit;
                    foreach ($r['options'] as $o) {
                        $options_data[] = [
                            'option' => $o['name'],
                            'answer_boolean' => empty($o['answer_boolean']) ? 0 : $o['answer_boolean'],
                            'status' => 1,
                            'is_archive' => 0,
                            'answer_string' => "",
                            'question_id' => $data->id,
                            'prefix' => $o['prefix'],
                            'images' => $o['images'],
                            'name' => $o['name'],
                            'description' => empty($o['description']) ? "" : $o['description'],
                        ];
                    }
                }

                switch ($r['question_type']) {
                    case 'multiple_choice';
                        (new QuestionOptionsAnswers())->multipleChoice($data->id, $options_data);
                        break;

                    case 'single_choice';
                        (new QuestionOptionsAnswers())->singleChoice($data->id, $options_data);
                        break;

                    case 'true_false';
                        (new QuestionOptionsAnswers())->trueFalse($data->id, $options_data);
                        break;

                    case 'yes_no';
                        (new QuestionOptionsAnswers())->yesNo($data->id, $options_data);
                        break;
                }

            endforeach;

            DB::commit();

            echo json_encode(['success' => 1]);
        } catch (\Exception $ex) {
            DB::rollBack();
            echo json_encode(['success' => 0, 'error' => $ex->getMessage()]);
        }
    }

    /**
     * 
     * @param Request $request
     * @param type $chapter_id
     * @param type $question_id
     * @return type
     */
    public function getChapterQuestion(Request $request, $chapter_id, $question_id) {
        
        $question = Question::where('id', '=', $question_id)->where('chapter_id', '=', $chapter_id)->first();


        $chapter = Chapter::where('id', '=', $chapter_id)->first();

        return view('admin.questionsbank.index', [
            'chapter_id' => $chapter_id,
            'question_id' => $question_id,
            'quizTypes' => json_encode([['name' => 'Mini Quiz', 'value' => 'mini_quiz']]),
            'topic_id' => 0,
            'id' => $question_id,
            'allow_questions' => 'single',
            'chapter' => $chapter,
            'getUrl' => route('admin.question-bank.ajaxChapterQuestion', ['chapter_id' => $chapter_id, 'question_id' => $question_id]),
            'postUrl' => route('admin.question-bank.addEditChapter', ['chapter_id' => $chapter_id, 'question_id' => $question_id]),
            'deleteUrl' => route('admin.question-bank.delete', ['chapter_id' => $chapter_id]),
            'not_found' => empty($question) ? 1 : 0,
            'is_locked'=>($question['is_locked']==1)? 'yes':'no'
            
        ]);
    }

    /**
     * 
     * @param Request $request
     * @param type $chapter_id
     * @param type $question_id
     */
    public function ajaxChapterQuestion(Request $request, $chapter_id, $question_id) {
        try {

            $question = Question::where('id', '=', $question_id)
                    ->where('chapter_id', '=', $chapter_id)
                    ->with('options')
                    ->get();

            echo json_encode(['success' => 1, 'payload' => $question]);
        } catch (\Exception $ex) {
            DB::rollBack();
            echo json_encode(['success' => 0, 'error' => $ex->getMessage()]);
        }
    }

    public function ajaxTopicQuestion(Request $request, $chapter_id, $topic_id) {
        try {

            $question = Question::where('chapter_id', '=', $chapter_id)
                    ->where('topic_id', '=', $topic_id)
                    ->where('is_archive', '=', '0')
                    ->with('options')
                    ->get();

            echo json_encode(['success' => 1, 'payload' => $question]);
        } catch (\Exception $ex) {
            DB::rollBack();
            echo json_encode(['success' => 0, 'error' => $ex->getMessage()]);
        }
    }
    
    public function deleteQuestion(Request $request, $chapter_id) {
        try {
            
            if(!$request->has('id')){
                throw new Exception('Invalid Question requested to Remove!');
            }
            
            $question = Question::where('chapter_id', '=', $chapter_id)
                    ->where('is_archive', '=', '0')
                    ->where('id', '=', $request->input('id'))
                    ->first();
            
            if(empty($question)){
                throw new Exception('Invalid Question requested to Remove!');
            }

            $question->is_archive = 1;
            $question->save();
            
            echo json_encode(['success' => 1, 'payload' => $question]);
        } catch (\Exception $ex) {
            DB::rollBack();
            echo json_encode(['success' => 0, 'error' => $ex->getMessage()]);
        }
    }
}
