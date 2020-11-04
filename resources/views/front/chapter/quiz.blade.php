
@extends('layouts.front.main')

@section('meta-info')
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Courses List</title>
@endsection

@section('footer-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.15/lodash.min.js" integrity="sha256-VeNaFBVDhoX3H+gJ37DpT/nTuZTdjYro9yBruHjVmoQ=" crossorigin="anonymous"></script>
<script src="{{ url('/js/vue-mainquiz.js') }}"></script>
<script type="text/javascript">
    var app = new Vue({
        el: '#app'
    })
</script>
<script type="text/javascript">
    window.onbeforeunload = function () {
        return "Dude, are you sure you want to leave?";
    }
</script>
<script type="text/javascript">
    var timer = new Timer();
    timer.start();
    timer.addEventListener('secondsUpdated', function (e) {
        $('.time_on_page').html(timer.getTimeValues().toString());
        window.on_screen_time = timer.getTimeValues().toString();
    });
</script>
<script type="text/javascript">
    function activityWatcher(maxInactivityTime) {
        //The number of seconds that have passed
        //since the user was active.
        var secondsSinceLastActivity = 0;

        //Five minutes. 60 x 5 = 300 seconds.
        //var maxInactivity = (60 * 5);
        var maxInactivity = (maxInactivityTime);

        //Setup the setInterval method to run
        //every second. 1000 milliseconds = 1 second.
        function runIdealTimer() {
            //console.log(secondsToHms(secondsSinceLastActivity) + ' seconds since the user was last active');
            //console.log(secondsToHms(secondsSinceLastActivity));
            $('.time_idle').text(secondsToHms(secondsSinceLastActivity));
            secondsSinceLastActivity++;
            //if the user has been inactive or idle for longer
            //then the seconds specified in maxInactivity
            if (secondsSinceLastActivity > maxInactivity) {
                console.log('User has been inactive for more than ' + maxInactivity + ' seconds');
                StopIdealTimer();
                //Redirect them to your logout.php page.
                //location.href = 'logout.php';
            }
        }
        var runIdealTimerFunc = setInterval(runIdealTimer, 1000);

        function StopIdealTimer() {
            clearInterval(runIdealTimerFunc);
        }

        function secondsToHms(d) {
            d = Number(d);
            var h = Math.floor(d / 3600);
            var m = Math.floor(d % 3600 / 60);
            var s = Math.floor(d % 3600 % 60);

            var hDisplay = h > 0 ? (h < 10 ? '0' + h : h) : "00";
            var mDisplay = m > 0 ? (m < 10 ? '0' + m : m) : "00";
            var sDisplay = s > 0 ? (s < 10 ? '0' + s : s) : "00";

            return hDisplay + ':' + mDisplay + ':' + sDisplay;
        }

        //The function that will be called whenever a user is active
        function activity() {
            //reset the secondsSinceLastActivity variable
            //back to 0
            secondsSinceLastActivity = 0;
        }

        //An array of DOM events that should be interpreted as
        //user activity.
        var activityEvents = [
            'mousedown', 'mousemove', 'keydown',
            'scroll', 'touchstart'
        ];

        //add these events to the document.
        //register the activity function as the listener parameter.
        activityEvents.forEach(function (eventName) {
            document.addEventListener(eventName, activity, true);
        });
    }
    activityWatcher(600);
</script>
@endsection

@section('content')
<section class="features" id="features">
    <div class="container">
        <div class="section-heading text-center">
            <p class="text-muted"><a href="{{$course_link}}">{{$course->name}}</a> : Module <b>({{$module->name}})</b></p>
            <h2><a href="{{$chapter_link}}">{{$chapter->name}}</a></h2>
            <span>Main Quiz</span><br />
            <hr />
            @include('helpers.flash-message')
        </div>
    </div>
    <div id="app">
        <?php if(sizeof($questions) > 0): ?>
        <vue-mainquiz
            :chapter="{{json_encode($chapter)}}"
            :module="{{json_encode($module)}}"
            :course="{{json_encode($course)}}"
            :questions="{{json_encode($questions)}}"
            already_answered=""
            submit-main-quiz="{{$submit_quiz}}"
            passing-score="{{$chapter->minimum_passing_grades}}"
            ></vue-mainquiz>
        <?php else: ?>
        <center><h3>No Quiz Found!</h3></center>
        <?php endif; ?>
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
    <!-- timmers -->
    <div class="footer-stick">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
                Time on Page : <button type="button" class="btn btn-warning btn-min-width mr-1 mb-1 time_on_page"></button>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                Time Idle : <button type="button" class="btn btn-danger btn-min-width mr-1 mb-1 time_idle"></button>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12">
                Max Idle Time : <button type="button" class="btn btn-info btn-min-width mr-1 mb-1"> Seconds</button>
            </div>
        </div>
    </div>
    <!-- timers -->
</section>
@endsection
