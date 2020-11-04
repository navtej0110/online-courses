@extends('layouts.front.main')

@section('meta-info')
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>New Age - Start Bootstrap Theme</title>
@endsection

@section('header-css')
<style type="text/css">
    section.features .section-heading {
        margin-bottom: 20px;
    }
</style>
@endsection

@section('footer-js')
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
            secondsSinceLastActivity++;
            //console.log(secondsToHms(secondsSinceLastActivity) + ' seconds since the user was last active');
            //console.log(secondsToHms(secondsSinceLastActivity));
            $('.time_idle').text(secondsToHms(secondsSinceLastActivity));
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#attempt').click(function () {
            
            $.ajax({
                method: "POST",
                url: "{{route('front.test.answersubmit',['course_slug'=> $course_slug, 'test_slug'=>$test_slug])}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $('#anwswers_submitions').serialize(),
                success: function (result) {
                    
                },
                beforeSend: function () {

                }
                , complete: function () {

                }, error: function () {
                    //bootbox.alert("There is some error please try Later!!");
                }
            });
            return false;
        });
    });
</script>
@endsection

@section('content')

<section class="features" id="features">
    <div class="container">
        <div class="section-heading text-center">
            <h3><?php echo $test->name; ?></h3>
            <hr>
        </div>
        <div class="row">

            <div class="col-lg-12 my-auto">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php echo stripslashes($test->content); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal -->
        <?php if (sizeof($test->questions) > 0): ?>
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Questions</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="anwswers_submitions" method="post">
                                @csrf
                                <?php
                                if (!empty($test->questions)) {
                                    echo '<ol>';
                                    foreach ($test->questions as $question) {
                                        if (!empty($question->options_answers)) {
                                            switch ($question->type) {

                                                case 'single_choice':
                                                    echo '<li>' . $question->name . '</li>';
                                                    echo '<ul class="question-options">';
                                                    foreach ($question->options_answers as $qa) {
                                                        echo '<li>' . $qa->prefix . '.' . $qa->option . ' <input value="' . $qa->id . '" name="question[' . $question->id . '][option_id][]" type="radio" /></li>';
                                                    }
                                                    echo '</ul>';
                                                    break;

                                                case 'multiple_choice':
                                                    echo '<li>' . $question->name . '</li>';
                                                    echo '<ul class="question-options">';
                                                    foreach ($question->options_answers as $qa) {
                                                        echo '<li>' . $qa->prefix . '.' . $qa->option . ' <input value="' . $qa->id . '" name="question[' . $question->id . '][option_id][]" type="checkbox" /></li>';
                                                    }
                                                    echo '</ul>';
                                                    break;

                                                case 'true_false':
                                                    echo '<li>' . $question->name . '</li>';
                                                    echo '<ul class="question-options">';
                                                    foreach ($question->options_answers as $qa) {
                                                        echo '<li>' . $qa->option . ' <input value="' . $qa->id . '" name="question[' . $question->id . '][option_id][]" type="radio" /></li>';
                                                    }
                                                    echo '</ul>';
                                                    break;

                                                case 'yes_no':
                                                    echo '<li>' . $question->name . '</li>';
                                                    echo '<ul class="question-options">';
                                                    foreach ($question->options_answers as $qa) {
                                                        echo '<li>' . $qa->option . ' <input value="' . $qa->id . '" name="question[' . $question->id . '][option_id][]" type="radio" /></li>';
                                                    }
                                                    echo '</ul>';
                                                    break;
                                            }
                                        }
                                    }
                                    echo '</ol>';
                                }
                                ?>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" id="attempt" class="btn btn-primary">Record My Time / Check Answers</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <!-- modal -->

        <div class="footer-stick">
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-12">
                    Time on Page : <button type="button" class="btn btn-warning btn-min-width mr-1 mb-1 time_on_page"></button>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    Time Idle : <button type="button" class="btn btn-danger btn-min-width mr-1 mb-1 time_idle"></button>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12">
                    Max Idle Time : <button type="button" class="btn btn-info btn-min-width mr-1 mb-1"><?php echo $test->idle_time; ?> Seconds</button>
                </div>

                <?php if (sizeof($test->questions) > 0): ?>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <button type="button" data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-success btn-min-width mr-1 mb-1">Questions</button>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <button type="button" style="font-size:12px" class="btn btn-success btn-min-width mr-1 mb-1">Record My Time/Check Answers</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

</section>

<section class="contact bg-primary" id="contact">
    <div class="container">
        <h2>We
            <i class="fas fa-heart"></i>
            new friends!</h2>
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