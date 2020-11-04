<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\View\Composers;

use Illuminate\Support\Facades\Auth;
use App\Models;
use Illuminate\View\View;

class UserComposer {

    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $auth;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(Auth $auth) {
        // Dependencies automatically resolved by service container...
        $this->auth = $auth;
    }

    /**
     * 
     * @return string
     */
    protected function getGuard() {
        if (Auth::guard('admin')->check()) {
            return "admin";
        } elseif (Auth::guard('web')->check()) {
            return "web";
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view) {
        $view->with('logged_in_user', $this->auth::user());
        $view->with('logged_in_user_guard', $this->getGuard());
    }

}
