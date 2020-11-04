<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOptionsAnswers extends Model {

    protected $table = "question_options_answers";

    /**
     * 
     * @param type $question_id
     * @param type $data
     * @return boolean
     */
    public function multipleChoice($question_id, $data) {
        if (empty($data)) {
            return false;
        }

        $this::where('question_id', '=', $question_id)->update(['is_archive' => 1]);

        foreach ($data as $d) {
            $params[] = [
                'option' => $d['name'],
                'answer_boolean' => $d['answer_boolean'],
                'status' => $d['status'],
                'is_archive' => $d['is_archive'],
                'answer_string' => $d['answer_string'],
                'question_id' => $question_id,
                'prefix' => $d['prefix'],
                'images' => $d['images'],
                'name' => $d['name'],
                'description' => $d['description'],
            ];
        }

        if (sizeof($params) > 0) {
            return $this::insert($params);
        }

        return false;
    }

    /**
     * 
     * @param type $question_id
     * @param type $data
     * @return boolean
     */
    public function singleChoice($question_id, $data) {
        if (empty($data)) {
            return false;
        }
        
        $this::where('question_id', '=', $question_id)->update(['is_archive' => 1]);

        foreach ($data as $d) {
            $params[] = [
                'option' => $d['name'],
                'answer_boolean' => $d['answer_boolean'],
                'status' => $d['status'],
                'is_archive' => $d['is_archive'],
                'answer_string' => $d['answer_string'],
                'question_id' => $question_id,
                'prefix' => $d['prefix'],
                'images' => $d['images'],
                'name' => $d['name'],
                'description' => $d['description'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        if (sizeof($params) > 0) {
            return $this::insert($params);
        }

        return false;
    }

    /**
     * 
     * @param type $question_id
     * @param type $data
     * @return boolean
     */
    public function trueFalse($question_id, $data) {
        if (empty($data)) {
            return false;
        }

        $this::where('question_id', '=', $question_id)->update(['is_archive' => 1]);

        foreach ($data as $d) {
            $params[] = [
                'option' => $d['name'],
                'answer_boolean' => $d['answer_boolean'] == 'true' ? 1 : 0,
                'status' => $d['status'],
                'is_archive' => $d['is_archive'],
                'answer_string' => $d['answer_string'],
                'question_id' => $question_id,
                'prefix' => $d['prefix'],
                'images' => $d['images'],
                'name' => $d['name'],
                'description' => $d['description'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        if (sizeof($params) > 0) {
            return $this::insert($params);
        }

        return false;
    }

    /**
     * 
     * @param type $question_id
     * @param type $data
     * @return boolean
     */
    public function yesNo($question_id, $data) {
        if (empty($data)) {
            return false;
        }

        $params = [];
        $data = $data['yes_no'];

        $this::where('question_id', '=', $question_id)->update(['is_archive' => 1]);

        $params = [
            [
                'option' => "Yes",
                'question_id' => $question_id,
                'prefix' => "Yes",
                'answer_string' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'answer_boolean' => $data['answer'] == 'true' ? 1 : 0
            ], [
                'option' => "No",
                'question_id' => $question_id,
                'prefix' => "No",
                'answer_string' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'answer_boolean' => $data['answer'] == 'false' ? 1 : 0
            ]
        ];

        if (sizeof($params) > 0) {
            return $this::insert($params);
        }

        return false;
    }

}
