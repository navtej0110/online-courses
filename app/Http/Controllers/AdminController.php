<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller {

    protected $auth;

    public function __construct(Auth $auth) {
        $this->auth = $auth;
    }

    /**
     * 
     * @param Request $request
     * @param type $id
     * @param type $model
     * @return type
     * @throws Exception
     */
    public function editRecord(Request $request, $id, $model) {
        $data = $model::where('id', '=', $id)->first();

        if (empty($data)) {
            throw new \Exception('Invalid Record Selected!');
        }

        return ['id' => $id, 'data' => $data];
    }

    /**
     * 
     * @param Request $request
     * @throws \Exception
     */
    public function deleteRecord($request, $model) {
        if (!isset($request['id']) || empty($request['id'])) {
            throw new \Exception('Invalid Record ID?');
        }

        $id = $request['id'];

        $record = $model::where('id', '=', $id)->first();

        if (empty($record)) {
            throw new \Exception('Invalid Record ID?');
        }

        $record->is_archive = 1;
        $record->save();

        return ['status' => 1, 'id' => $id, 'payload' => 'Entry Archived Successfully!'];
    }

    /**
     * 
     * @param Request $request
     * @throws \Exception
     */
    public function lockQuestion($request, $model) {
        if (!isset($request['id']) || empty($request['id'])) {
            throw new \Exception('Invalid Record ID?');
        }

        $id = $request['id'];

        $record = $model::where('id', '=', $id)->first();

        if (empty($record)) {
            throw new \Exception('Invalid Record ID?');
        }

        $record->is_locked = 1;
        $record->save();

        return ['status' => 1, 'id' => $id, 'payload' => 'Question Locked Successfully!'];
    } 

}
