<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Providers\RouteServiceProvider;
use App\Models\Course;
use App\Models\Question;
use App\Models\QuestionOptionsAnswers;
use Illuminate\Support\Facades\DB;
use App\Models\Test;

class QuestionsController extends AdminController {

    /**
     * 
     * @param array $data
     * @return type
     */
    protected function validatorAdd(array $data) {
        return Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'description' => ['required',],
                    'type' => ['required']
        ]);
    }
    
    protected function validatorEdit(array $data) {
        return Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'description' => ['required',]
        ]);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function add(Request $request, $test_id) {
        $test = Test::where('id','=',$test_id)->first();
        return view('admin.question.add', ['test' => $test, 'id' => '', 'test_id' => $test_id]);
    }

    /**
     * 
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function edit(Request $request, $test_id, $id) {
        try {
            $test = Test::where('id','=',$test_id)->first();
            $data = Question::where('id', '=', $id)
                ->with(['options_answers' =>function($query){
                $query->orderBy('id','ASC');
            }])->first();
            
            if (empty($data)) {
                throw new \Exception('Invalid Record Selected!');
            }
            
            return view('admin.question.add', ['test' => $test,'id' => $id, 'data' => $data, 'test_id' => $test_id]);
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    /**
     * 
     * @param Request $request
     * @throws \Exception
     */
    public function delete(Request $request) {
        try {

            $payload = parent::deleteRecord($request, Question::class);
            echo json_encode($payload);
        } catch (\Exception $ex) {
            echo json_encode(['status' => 0, 'error' => $ex->getMessage()]);
        }
    }
    /**
     * 
     * @param Request $request
     * @throws \Exception
     */
    public function locked(Request $request) {
        try {
           
            $payload = parent::lockQuestion($request, Question::class);
            echo json_encode($payload);
        } catch (\Exception $ex) {
            echo json_encode(['status' => 0, 'error' => $ex->getMessage()]);
        }
    }
    /**
     * 
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function addUpdate(Request $request, $id = null) {
        if(empty($id)){
        $this->validatorAdd($request->all())->validate();
        }else{
            $this->validatorEdit($request->all())->validate();
        }

        $data = $request->all();

        try {
            DB::beginTransaction();

            if (empty($id)) {
                $data = new Question();
            } else {
                $data = Question::where('id', '=', $id)->first();
                if (empty($data)) {
                    throw new \Exception('Invalid Test Selected!');
                }
                if($data->is_locked == 1){
                    throw new \Exception('The Question is Locked it can not be Edited!');
                }
            }

            $data->name = $request['name'];
            $data->description = $request['description'];
            if($request->has('type')){
                $data->type = $request['type'];
            }
            
            if($request->has('is_locked')){
                $data->is_locked = $request['is_locked'];
            }else{
                $data->is_locked = 0;
            }
            
            $data->test_id = $request['test_id'];
            $data->number_of_answers = 0;
            $data->test_questions = 0;
            $data->created_by_admin_id = $this->auth::id();
            
            $data->save();

            switch ($request['type']) {
                case 'multiple_choice';
                    (new QuestionOptionsAnswers())->multipleChoice($data->id, $request['types']);
                    break;
                
                case 'single_choice';
                    (new QuestionOptionsAnswers())->singleChoice($data->id, $request['types']);
                    break;
                
                case 'true_false';
                    (new QuestionOptionsAnswers())->trueFalse($data->id, $request['types']);
                    break;
                
                case 'yes_no';
                    (new QuestionOptionsAnswers())->yesNo($data->id, $request['types']);
                    break;
            }
            
            DB::commit();

            return redirect()->route('admin.question.list',['test_id' => $data->test_id])->with('success', "Question/Answers is " . ($id ? 'updated' : "created") . " Successfully!");
        } catch (\Exception $ex) {
            DB::rollBack();
            return back()->with('error', $ex->getMessage());
        }
    }

    public function index(Request $request, $test_id) {
        $records = Question::where('test_id','=',$test_id)->where('is_archive','=',0)->get();
        $test = Test::where('id','=',$test_id)->first();
        return view('admin.question.list', ['test' => $test,'records' => $records, 'test_id' => $test_id]);
    }

}
