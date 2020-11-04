<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class userCoursePayment extends Model
{
    protected $table = "user_payments";
    protected $fillable = ['user_id','test_ids','test_names','duration','course_id','course_name','payment_method','payment_mode','payment_status','payment_recurring','payment_amount','payment_currency','status'];
    public function savePayment($course_id,$course_name,$user_id,$amount,$currency)
    {

       $data=$this->create(array(
            'user_id' =>$user_id,
            'test_ids'=>0,
            'test_names'=>'',
            'duration'=>0,
            'course_id'  => $course_id,
            'course_name'=>$course_name,
            'payment_method'=>'paypal',
            'payment_mode'=>'manual',
            'payment_status'=>'confirmed',
            'payment_recurring'=>0,
            'payment_amount'=>$amount,
            'payment_currency'=>$currency,
            'status'=>1

                        
        ));
        return $data;
    }
}
