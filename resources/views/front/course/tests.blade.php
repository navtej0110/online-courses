@extends('layouts.front.main')

@section('meta-info')
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Courses List</title>
@endsection

@section('content')
<section class="features" id="features">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Course: <?php echo $course->name; ?></h2>
            <div class="col-lg-12"><?php echo stripslashes($course->description); ?></div>
            <hr>
            @include('helpers.flash-message')
        </div>
        <div class="row">

            <div class="col-lg-12 my-auto">
                <div class="container-fluid">
                    <div class="row">
                        <?php 
                            if (sizeof($tests) > 0): foreach ($tests as $test): ?>
                                <div class="col-lg-4 col-sm-12 col-md-6">
                                    <div class="feature-item">
                                        <i class="icon-screen-smartphone text-primary"></i>
                                        <h3>
                                            <a href="{{route('front.test.attempt',['course_slug' => $course->slug, 'test_slug' => $test->test->slug])}}"><?php echo $test->test->name; ?></a>
                                        </h3>
                                        <p class="text-muted"><b>Questions: </b><?php echo count($test->test->questions); ?></p>
                                    </div>
                                </div>

                            <?php
                            endforeach;
                            
                            else:
                                echo '<div class="col-sm-12"><center><h2>No Test Found!</h2></center></div>';
                            
                        endif;
                            
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

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