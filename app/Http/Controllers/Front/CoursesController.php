<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\UserCourses;
use App\Models\Course;
use App\Models\Test;
use App\Models\CourseTestsRelation;
use App\userCoursePayment;
use Session;
use URL;
use Srmklive\PayPal\Services\ExpressCheckout;

class CoursesController extends FrontController {

    public function index(Request $request) {

        $session = [];
        if ($request->session()->has('logged_in_courses')) {
            $session = $request->session()->get('logged_in_courses');
        }

        $user_id = $this->auth::id();
        $courses = UserCourses::where('user_id', '=', $user_id)->with('course')->with('tests')->get();

        return view('front.course.list', ['courses' => $courses, 'session' => $session]);
    }

    public function listAll(Request $request) {
        try {
            $user_id = $this->auth::id();
            $courses = Course::where('is_archive', '=', '0')
                    ->with('categories')
                    ->with('payment')
                    ->with(['modules' => function($query){
                        $query->with(['chapters']);
                    }])
                    ->get();
                    $crs=$courses->toArray();
                   
                    
                    $course_percentage=$this->getCoursepercentage($crs, $user_id);
                                       
                    for($i=0;$i<count($crs);$i++)
                        {
                            $courses[$i]['percentage']=0;
                            $courses[$i]['payment_status']=0;
                          if(!empty($course_percentage[$i]['modules']) )
                          {
                            $courses[$i]['percentage']=$course_percentage[$courses[$i]['id']];
                          } 
                           if(!empty($crs[$i]['payment']) && $crs[$i]['payment'][0]['user_id']==$user_id )
                          {
                            $courses[$i]['payment_status']=1;
                          } 
                          $courses[$i]['user_id']=isset($user_id) ? $user_id : 0;
                    }
                    $cart_count=0;
                     if ($request->session()->has('cart_item')) {
                        $cart_count=$this->getCount($request);
                    }
                   
                  
                                        
                    
            
            if (sizeof($courses) > 0) {
                foreach ($courses as $course) {
                    $course->image = empty($course->image) 
                            ? "https://lh3.googleusercontent.com/21Mlc_bfmIP34V4MgJMtr1S9sGbxNGVdj7ncT_jmiQNAhvqJNYwWhnOdKuY2h57SpOuaOk_aF5dAnrz0w4tbDLVy0wxZHJCUQC3y=s500"
                            : $course->image;
                    $course->link = route('course.modules', ['course_slug' => $course->slug]);
                    $course->duration = (function() use($course) {
                        $duration = [];
                        foreach($course->modules as $m){
                            foreach($m->chapters as $c){
                                $duration[] = $c->duration;
                            }
                        }

                        return floor(array_sum($duration) / 60).' hours '.($duration % 60).' minutes';
                    })();
                    
                    
                }
            }

            return view('front.course.listall', ['courses' => $courses,'course_percentage'=>$course_percentage,'cart_count'=>$cart_count]);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }
 public function getCoursepercentage($crs, $user_id)
 {
        try{        
                    $crs_id=[];
                    if(!empty($crs))
                    {
                        for($i=0;$i<count($crs);$i++)
                            {
                                if(!empty($crs[$i]['modules']))
                                {
                                     $crs_id[]=$crs[$i]['id'];
                                }
                               
                         }

                    }
                    
                    $course_progress=$this->courseProgress($crs,$user_id);
                   
                    $crs_prc=[];
                    
                    for($i=0;$i<count($course_progress);$i++)
                    {
                        $crs_prc[$i]['course_id']=$course_progress[$i]['course_id'];
                        $sum=0;
                        foreach($course_progress[$i]['percentage'] as $prc)
                        {
                            $sum+=$prc;
                        }
                        $total=(!empty($course_progress[$i]['percentage'] )) ? count($course_progress[$i]['percentage']) :1;
                        $crs_prc[$i]['prc']=$sum/$total;

                    }

                     $count=0;
                     $crs_v=[];
                     for($i=0;$i<count($crs_id);$i++)
                        {
                            for($j=0;$j<count($crs_prc);$j++)
                                {
                                    if($crs_prc[$j]['course_id']==$crs_id[$i])
                                    {
                                        $count+=1;
                                        $crs_v[$crs_prc[$j]['course_id']][]=$crs_prc[$j]['prc'];
                                        
                                    }
                                    $count=0;
                            }

                        }

                    $final=[];
                    $n=0;
                    foreach($crs_id as $id)
                           {
                                foreach ($crs_v[$id] as $v) {
                                        $n+=$v;
                                    }
                                $count=(!empty($crs_v[$id] )) ? count($crs_v[$id]) :1;
                                $final[$id]=$n/$count;
                                $n=0; 
                                
                                
                           }
                   return $final;
        }
        catch (\Exception $ex) {
            echo "The exception was created on line: " . $ex->getLine();
             echo "error is: " . $ex->getMessage();
            exit;
        }
                    
 }
 public function courseProgress($crs,$user_id)
 {

    try{

            $new=[];
            $i=0;
            $j=0;
            foreach($crs  as $course)
                {
                        
                    if(!empty($course['modules']))
                    {
                        foreach ($course['modules'] as $module ) 
                        {
                            $new[$i]['course_id']=$module['course_id'];
                            $new[$i]['module_id']=$module['test_id'];
                            $new[$i]['chapters']=$module['chapters'];
                            $i++;
                    }
                            
                }
                $j++;
            }
            
            $progress=[];
            $k=0;
            $m=0;
            foreach($new as $new1)
             {
                $course_id=$new1['course_id'];
                $module_id=$new1['module_id'];
                $progress[$m]['percentage']=$this->chapterPercentage($new1['chapters'],$user_id,$course_id,$module_id); 
                $progress[$m]['course_id']=$course_id;
                $progress[$m]['module_id']=$module_id;
                $m++;
                        
                }

        return $progress;
         }
        catch (\Exception $ex) {
            echo "The exception was created on line: " . $ex->getMessage();
        }

                   
 }
    /**
     * 
     * @param Request $request
     * @param type $course_slug
     * @return type
     * @throws \Exception
     */
    public function tests(Request $request, $course_slug) {
        $user_id = $this->auth::id();
        $session = [];
        $course = Course::where('slug', '=', $course_slug)->first();

        if ($request->session()->has('logged_in_courses')) {
            $session = $request->session()->get('logged_in_courses');
        }

        try {
            if (empty($course)) {
                throw new \Exception('Invalid Course or Not associated with your account!');
            }

            if ($course->is_archive == 1) {
                throw new \Exception('Invalid Course or Not associated with your account!');
            }

            if (!in_array($course->id, $session)) {
                throw new \Exception('Invalid Course or Not associated with your account!');
            }

            $current = UserCourses::where('user_id', '=', $user_id)
                    ->where('course_id', '=', $course->id)
                    ->first();

            if (empty($current)) {
                throw new \Exception('Invalid Course or Not associated with your account!');
            }

            $tests = CourseTestsRelation::where('course_id', '=', $course->id)->where('status', '=', 1)
                            ->with(['test' => function($query) {
                                    $query->with(['questions' => function($query) {
                                            $query->where('is_locked', '=', '1')->where('is_archive', '=', 0);
                                        }]);
                                }])->get();

            return view('front.course.tests', ['course' => $course, 'tests' => $tests]);
        } catch (\Exception $ex) {
            return redirect()->route('front.course.list')->with('error', $ex->getMessage());
        }
    }

    public function authenticate(Request $request) {
        $course_id = $request->input('course');
        $password = $request->input('password');

        try {
            // check user relation with course
            $user_id = $this->auth::id();
            $course = UserCourses::where('user_id', '=', $user_id)->where('course_id', '=', $course_id)->with('course')->with('tests')->first();

            if (empty($course)) {
                throw new \Exception('You are not associated with requested Course!');
            }

            // check password against course
            if ($password != $course->course->password) {
                throw new \Exception('Invalid password : ' . $course->course->name . '!');
            }

            $session = [];
            if ($request->session()->has('logged_in_courses')) {
                $session = $request->session()->get('logged_in_courses');
            }

            $session[] = $course_id;

            $request->session()->put('logged_in_courses', $session);
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('front.course.tests', ['course_slug' => $course->course->slug]);
    }
/**
     * 
     * @param Request $request
     * @param type $course_slug
     * @return type
     * @throws \Exception
     */
    public function attempt(Request $request, $course_id) {
        return view('front.course.tests');
    }
/**
     * 
     * @param Request $request
     * @param type $course_slug
     * @return type
     * @throws \Exception
     */

 public function cartFunction(Request $request,$course_id,$user_id,$course_price)
    {
        try{
            
                $request->session()->push('cart_item', [
                                'course_id' => $course_id,
                                'course_price' => $course_price
                            ]);

                echo $this->getCount($request);
  
        }
        catch (\Exception $ex) {
            echo "The exception was created on line: " . $ex->getMessage();
        }

        }

    public function cartList(Request $request)
    {
        try{
         
        $cart_items=Session::get('cart_item');

        
        $course_id=[];
        $items=[];
        $j=0;
        for($i=0;$i<count($cart_items);$i++)
        {
            
            if(!in_array($cart_items[$i]['course_id'],$course_id))
            {
                    $course_id[]=$cart_items[$i]['course_id'];
                    $items[$j]['course_id']=$cart_items[$i]['course_id'];
                    $course_detail=(new Course())->where('id','=',$cart_items[$i]['course_id'])->get()->toArray();

                    $items[$j]['course_name']=(!empty($course_detail))?$course_detail[0]['name']:'';
                    $items[$j]['course_price']=$cart_items[$i]['course_price'];

                    $j++;
            }
        }
       
        }
         catch (\Exception $ex) {
            echo "The exception was created on line: " . $ex->getMessage();
        }
        return view('front.course.cart',['cart_items'=>$items]);
    }
    public function getCount($request)
    {
        try{
        
        $cart_items=$request->session()->get('cart_item');
        $course_id=[];
        $items=[];
        $j=0;
        for($i=0;$i<count($cart_items);$i++)
        {
            
            if(!in_array($cart_items[$i]['course_id'],$course_id))
            {
                 $course_id[]=$cart_items[$i]['course_id'];
                 $items[$j]['course_id']=$cart_items[$i]['course_id'];

                  $items[$j]['course_price']=$cart_items[$i]['course_price'];
                  $j++;
            }
        }
       
        return count($items);
        }
         catch (\Exception $ex) {
            echo "The exception was created on line: " . $ex->getMessage();
        }
        return view('front.course.cart',['cart_items'=>$items]);
    }

   public function removeCartItem(Request $request,$item_id)
    {
        try
        {

            $data=$request->session()->pull('cart_item');
           $new=[];
            //$data=$request->session()->get('cart_item');
           foreach ($data as $key => $value) {

                if($value['course_id'] != $item_id) {
                    $found = $key;
                    $new[]=$value;
                    Session::push('cart_item' , $value); 
                   
                }
            }
            
           }
             catch (\Exception $ex) {
            echo "The exception was created on line: " . $ex->getMessage();
        }
           
          //return redirect()->route('cartList');
        $url=URL::to('/course/cart');
           return redirect($url);
           



        }

        public function checkout($total)
        {
            /*$data=app()->call('App\Http\Controllers\Front\PaypalController@payment', [$total])*/
            $data=$this->payment($total);
            print_r($data);
           
            
        }
        public function payment($total=0)
        {
            $data = [];
            $data['items'] = [
                    ['name' => 'Course',
                    'price' => $total,
                    'desc'  => 'Description for Course',
                    'qty' => 1
                ]
        ];
                      /*  $total = 0;
            foreach($data['items'] as $item) {
                $total += $item['price']*$item['qty'];
            }*/
            $data['invoice_id'] = 1;
            $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
            $data['return_url'] = URL::to('/course/success');
            $data['cancel_url'] = URL::to('/course/cancel');
            $data['total'] = $total;
            /*echo '<pre>';
            print_r($data);
            exit;*/
            $provider = new ExpressCheckout;
            $response = $provider->setExpressCheckout($data);
            $response = $provider->setExpressCheckout($data, true);
           /* echo '<pre>';
            print_r($response);exit;*/
            return redirect($response['paypal_link']);

    }



    /**

     * Responds with a welcome message with instructions

     *

     * @return \Illuminate\Http\Response

     */

    protected function cancel()

    {
        
        dd('Your payment is canceled. You can create cancel page here.');

    }



    /**

     * Responds with a welcome message with instructions

     *

     * @return \Illuminate\Http\Response

     */

    public function success(Request $request)

    {
        echo '<pre>';
        $provider = new ExpressCheckout;
        $response = $provider->getExpressCheckoutDetails($request->token);
        $amount=$response['AMT'];
        $currency=$response['CURRENCYCODE'];
       $data=Session::get('cart_item');
       $user_id = $this->auth::id();
       $new=[];
       if(!empty($data))
            {
                foreach($data as $dt)
                    {
                    $course_detail=(new Course())->where('id','=',$dt['course_id'])->get()->toArray();
                    /*echo '<pre>';
                    print_r($course_detail[0]['name']);*/
                    $return=(new userCoursePayment())->savePayment($dt['course_id'],$course_detail[0]['name'], $user_id,$amount,$currency);
                  $new[]= $return; 


            }

       }
        dd('Your payment was successfully. You can create success page here.');

       /*echo '<pre>';
       print_r($new);exit;
       $request->session()->flush();
       $this->checkv();*/
        
   /* $response = $provider->getExpressCheckoutDetails($request->token);
    

        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {

            dd('Your payment was successfully. You can create success page here.');

        }



        dd('Something is wrong.');
*/
    }

    public function checkv()
    {

        dd('Your payment was successfully. You can create success page here.');

    }
}
