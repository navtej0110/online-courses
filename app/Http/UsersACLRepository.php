<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http;

use Alexusmai\LaravelFileManager\Services\ACLService\ACLRepository;

class UsersACLRepository implements ACLRepository {

    /**
     * Get user ID
     *
     * @return mixed
     */
    public function getUserID() {
        return \Auth::id();
    }

    /**
     * Get ACL rules list for user
     *
     * @return array
     */
    public function getRules(): array {
        if (\Auth::guard('admin')) {
            return [
                ['disk' => 'public', 'path' => '*', 'access' => 2],
            ];
        }
        /*if (\Auth::id() === 1) {
            return [
                ['disk' => 'public', 'path' => '*', 'access' => 2],
            ];
        }

        return [
            ['disk' => 'disk-name', 'path' => '/', 'access' => 1], // main folder - read
            ['disk' => 'disk-name', 'path' => 'users', 'access' => 1], // only read
            ['disk' => 'disk-name', 'path' => 'users/' . \Auth::user()->name, 'access' => 1], // only read
            ['disk' => 'disk-name', 'path' => 'users/' . \Auth::user()->name . '/*', 'access' => 2], // read and write
        ];*/
    }

}
