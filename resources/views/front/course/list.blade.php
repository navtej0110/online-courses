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
            <h2>Courses You Enrolled</h2>
            <p class="text-muted">Check out the courses you can do with us!</p>
            <hr>
            @include('helpers.flash-message')
        </div>
        <div class="row">

            <div class="col-lg-12 my-auto">
                <div class="container-fluid">
                    <div class="row">
                        <?php if (!empty($courses)): foreach ($courses as $course): ?>
                                <div class="col-lg-4 col-sm-12 col-md-6">
                                    <div class="feature-item">
                                        <?php if(!in_array($course->course->id, $session)): ?>
                                        <i class="icon-lock text-primary"></i>
                                        <?php else: ?>
                                        <i class="icon-lock-open text-primary"></i>
                                        <?php endif; ?>
                                        <h3><?php echo $course->course->name; ?></h3>
                                        <p class="text-muted"><?php //echo $course->course->description; ?></p>
                                        <?php if(!in_array($course->course->id, $session)): ?>
                                        <form method="post" action="{{route('front.course.authenticate')}}">
                                            @csrf
                                            <div class="col-sm-12">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <input name="course" value="<?php echo ( $course->course->id);?>" class="form-control" type="hidden" />
                                                        <input class="form-control" name="password" type="password" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-warning btn-min-width mr-1 mb-1">Open Course</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <?php else: ?>
                                        <div class="col-sm-12">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <a href="{{route('front.course.tests',['course_slug' => $course->course->slug])}}" class="btn btn-warning btn-min-width mr-1 mb-1">Open Course</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            <?php endforeach;
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