<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserCourses;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class UserCourseValidation {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        try {
            
            if (empty(Auth::id())) {
                $request->attributes->set('loggedin', 0);
                return $next($request);
            } else {
                $slug = $request->route('course_slug');
                $user_id = Auth::id();

                $course = (new Course())->getFromSlug($slug);

                if (empty($course)) {
                    throw new \Exception('Invalid Course Selected!', 404);
                }

                $access = (new UserCourses())->checkAccess($user_id, $course->id);

                if ($access === false) {
                    $request->attributes->set('is_accessable', 0);
                } else {
                    $request->attributes->set('is_accessable', 1);
                }
                
                $request->attributes->set('loggedin', 1);
                $request->attributes->set('course', $course);

                return $next($request);
            }
        } catch (\Exception $ex) {
            return new Response(view('exception', ['code' => $ex->getCode(), 'error' => $ex->getMessage()]));
        }
    }

}
