<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TopicsController
 *
 * @author developer pc
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Question;
use App\Models\Topic;
use App\Models\UserTestAttempts;
use App\Models\UserQuestionOptionsAnswers;
use App\Models\QuestionOptionsAnswers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TopicsController extends AdminController {

    protected function validator(array $data) {
        return Validator::make($data, [
                    'title' => ['required', 'string', 'max:255'],
                    'video_1' => ['required', 'string', 'max:255'],
                    'content' => ['required', 'string'],
        ]);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function index(Request $request, $chapter_id = null) {
        try {
            
            if(!empty($chapter_id)){
                $chapter = Chapter::where('id', '=', $chapter_id)->first();
                if(empty($chapter)){
                    throw new \Exception("Topic not Found!");
                }
            }else{
                $chapter = "";
            }
            
            if(empty($chapter_id)){
                $topics = Topic::where('is_archive', '=', '0')->with('chapter')->get();
            }else{
                $topics = Topic::where('chapter_id', '=', $chapter_id)->where('is_archive', '=', '0')->with('chapter')->get();
            }
            
 
            $user_id = $this->auth::id();
            return view('admin.topics.index', ['results' => $topics, 'chapter' => $chapter]);
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    public function add(Request $request, $chapter_id) {
        try {

            $chapter = Chapter::where('id', '=', $chapter_id)->first();
            $user_id = $this->auth::id();
            return view('admin.topics.addEdit', [
                'chapter' => $chapter,
                'chapter_id' => $chapter_id,
                'question_id' => 0,
                'topic_id' => 0,
                'id' => 0,
                'allow_questions' => 'multiple',
                'getUrl' => '',
                'postUrl' => route('admin.question-bank.addEditChapter', ['chapter_id' => $chapter_id, 'question_id' => ""]),
                'not_found' => 0
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
        }
    }

    public function edit(Request $request, $chapter_id, $topic_id) {
        try {
            
            $chapter = Chapter::where('id', '=', $chapter_id)->first();
            $topics = Topic::where('chapter_id', '=', $chapter_id)->where('id','=',$topic_id)->first();
            
            if(empty($topics)){
                throw new \Exception('Invalid Topic or Chapter!');
            }

            $user_id = $this->auth::id();
            return view('admin.topics.addEdit', [
                'results' => $topics, 
                'topic_id' => $topic_id,
                'chapter' => $chapter,
                'chapter' => $chapter,
                'chapter_id' => $chapter_id,
                'question_id' => 0,
                'id' => 0,
                'allow_questions' => 'multiple',
                'quizTypes' => json_encode([['name' => 'Mini Quiz', 'value' => 'mini_quiz']]),
                'deleteUrl' => route('admin.question-bank.delete', ['chapter_id' => $chapter_id]),
                'getUrl' => route('admin.question-bank.ajaxTopicQuestion', ['chapter_id' => $chapter_id, 'topic_id' => $topic_id]),
                'postUrl' => route('admin.question-bank.addEditTopic', ['chapter_id' => $chapter_id, 'topic_id' => $topic_id]),
                'not_found' => 0
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return back()->with('error', $ex->getMessage());
        }
    }

    public function addEdit(Request $request, $chapter_id, $topic_id = "") {

        $this->validator($request->all())->validate();

        try {

            if (empty($topic_id)) {
                $data = new Topic();
            } else {
                $data = Topic::where('id', '=', $topic_id)->where('chapter_id', '=', $chapter_id)->first();
                if (empty($data)) {
                    throw new \Exception('Invalid Topic Selected!');
                }
            }

            if (empty($topic_id)) {
                $data->chapter_id = $chapter_id;
                $data->test_id = 0;
            }

            if ($request->has('title')) {
                $data->title = addslashes($request->input('title'));
            }

            if ($request->has('video_1')) {
                $data->video_1 = $request->input('video_1');
            }

            if ($request->has('content')) {
                $data->content = addslashes($request->input('content'));
            }

            if ($request->has('check_your_knowledge')) {
                $data->check_your_knowledge = addslashes($request->input('check_your_knowledge'));
            }

            if ($request->has('key_learnings')) {
                $data->key_learnings = addslashes($request->input('key_learnings'));
            }

            if ($request->has('status')) {
                $data->status = $request->input('status');
            }
            
            if ($request->has('lesson_duration')) {
                $data->lesson_duration = $request->input('lesson_duration');
            }
            
            if ($request->has('chek_your_knowledge_duration')) {
                $data->chek_your_knowledge_duration = $request->input('chek_your_knowledge_duration');
            }

            if ($request->has('is_archive')) {
                $data->is_archive = $request->input('is_archive');
            }

            if ($request->has('key_learnings')) {
                $data->key_learnings = addslashes($request->input('key_learnings'));
            }
            
            if ($request->has('is_locked')) {
                $data->is_locked = $request->input('is_locked');
            }
            
            $data->save();

            $topic_id = $data->id;
            
            return redirect()->route('admin.topics.edit', ['chapter_id' => $chapter_id, 'topic_id' => $topic_id])->with('success', "Topic is " . ($topic_id ? 'updated' : "created") . " Successfully!");
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

}
