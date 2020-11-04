@extends('layouts.front.main')

@section('meta-info')
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Courses List</title>
@endsection

@section('footer-js')
<style>
    .goto-checkout a, .goto-course a {
    background: #1483d8;
    padding: 6px 10px;
    color: #fff;
    font-weight: 600;
    line-height: normal;
}
.goto-checkout, .goto-course {
    margin-top: 15px;
}
div#cart_table {
    position: relative;
    padding-top: 100px;
    padding-bottom: 50px;
}
  
  </style>
@endsection

@section('content')
<div class="container"> 

    <div id="cart_table">  
        <h1>Course for Enrollment</h1>                                                                                  
  
  <div class="table-responsive">
    <table class="table table-bordered" id="cart-table">
      <thead>
        <tr>
          <th>Number</th>
          <th>CourseName</th>
          <th>Price</th>
          <!-- <th>Date</th> -->
          <th> Delete </th>
        </tr>
      </thead>
      <tbody id=>
        <?php if(!empty($cart_items)):
                $i=1;
                $price=0;
                foreach($cart_items as $items):
                    $price+=$items['course_price'];
                    $course_id=$items['course_id'];
                    echo $course_id;
                    ?>
        <tr>
          <td><?php echo $i;?></td>
          <td><?php echo $items['course_name']?></td>
          <td><?php echo $items['course_price'] ?></td>
          <!-- <td>03 March 2020</td> -->
          <td> <a href={{url('/course/cart/remove/'.$course_id)}}><img src="https://img.icons8.com/color/64/000000/delete-sign.png"/ style="width: 20px;cursor: pointer;"></a> </td>
        </tr>
        
        <?php   
            $i++;
            endforeach;
        else:
            echo 'No course for enrollment';
            endif;
            ?>
        <tr> 
            <td colspan="3" align="right"> <b> Total </b> </td>
            <td> <b> $<?php echo isset($price)?$price:0;?> </b> </td>          
        </tr>
      </tbody>
    </table>
  </div>
  <div class="row">
    <div class="col-sm-6">
        <?php if(isset($price) && $price>0):?>
        <div class="goto-checkout"> <a href="{{url('/course/payment/'.$price)}}"> Go To Checkout</a> </div>
    <?php endif;?>
    </div>
    <div class="col-sm-6">
        <div class="goto-course text-right"> <a href="{{url('/courses')}}">Go To Course Page </a> </div>
    </div>
  </div>
</div>
</div>

<section class="contact bg-primary" id="contact">
    <div class="container">
        <h2>We
            <i class="fas fa-heart"></i>
            new Students!</h2>
        <ul class="list-inline list-social">
            <li class="list-inline-item social-twitter">
                <a href="#">
                    <i class="fab fa-twitter"></i>
                </a>
            </li>
            <li class="list-inline-item social-facebook">
                <a href="#">
                    <i class="fab fa-facebook-f"></i>
                </a>
            </li>
            <li class="list-inline-item social-google-plus">
                <a href="#">
                    <i class="fab fa-google-plus-g"></i>
                </a>
            </li>
        </ul>
    </div>
</section>
@endsection