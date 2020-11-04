@extends('layouts.front.main')

@section('meta-info')
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Courses List</title>
@endsection
<style>
/* 20-03-2020 */
#cart-count { color: #fff !important; padding-left: 15px; padding-right: 15px; font-size: 13px; }
#cart-count .badge {
    font-size: 12px;
    padding-right: 4px;
    margin-right: 5px;
}
/**/
.course-list-card ul.course_features { padding-left: 0 ; }
.course-list-card ul.course_features li { position: relative; padding-left: 24px; margin-bottom: 5px; }
.course-list-card ul.course_features li i { position: absolute; left: 0; top: 7px; font-size: 14px; }

.course-list-card .card-content-bottom {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 16px;
    margin-bottom: 20px;
}
.course-list-card .btn { letter-spacing: 1px; font-size: 0.8rem; }

</style>
@section('footer-js')
<script src="{{ url('/js/vue-courses.js') }}"></script>
<script type="text/javascript">
var app = new Vue({
  el: '#app'
})
</script>
@endsection

@section('content')
<section class="features" id="features">
    <div class="container-fluid">
        <div class="section-heading text-center">
            <h2>Online Courses</h2>
            <p class="text-muted">Discover a range of free learning content designed to help grow your business or jumpstart your career. You can learn by selecting individual modules, or dive right in and take an entire course end-to-end.</p>
            <hr>
            @include('helpers.flash-message')
        </div>
        <div id="app">
         <?php //echo json_encode($courses) ;?>
            <vue-courses
                :courses="{{json_encode($courses)}}"
                course-link="/course/modules/"
                cart_count={{json_encode($cart_count)}}
                ></vue-courses>
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