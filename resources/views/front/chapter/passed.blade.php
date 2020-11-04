
@extends('layouts.front.main')

@section('meta-info')
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Courses List</title>
@endsection

@section('footer-js')
</script>
@endsection

@section('content')
<section class="features" id="features">
    <div class="container">
        <div class="section-heading text-center">
            <p class="text-muted">{{$course->name}} : Module <b>({{$module->name}})</b></p>
            <h2>{{$chapter->name}}</h2>
            <span>Review : Main Quiz</span><br />
            <span class="text-success">Your Score : <?php echo $attempt->score; ?>%</span><br />
            <span class="text-info">Date of Completion : <?php echo date($attempt->created_at); ?></span><br />
            <span class="text-danger"> Report : {{count($correct)}} out of {{count($attempts['questions'])}} are correct</span><br />
            <hr />
            <br /><center><span><a class="text-info" href="{{route('course.modules',['course_slug' => $course->slug])}}"><- Go Back To Modules</a></span></center>
            @include('helpers.flash-message')
        </div>
    </div>
    <div id="app">
        <section style="padding: 25px 0;" class="disable_text">
            <div class="container-fluid">
                <div class="assessment-question-container">
                        <?php $i = 1; ?>
                        <?php foreach($attempts['questions'] as $a): ?>
                        <div class="question-sec">
                            <div class="question-header">
                                <?php if($a->match == '1'): ?>
                                <h2 class="text-success"><i class="far fa-check-circle"></i> Well done, that's correct!</h2>
                                <?php else: ?>
                                <h2 class="text-danger"><i class="far fa-times-circle"></i> That's not quite right!</h2>
                                <?php endif; ?>
                                <h2> Question <?php echo $i; ?> : {{$a->question->name}} </h2>
                                <p>{{$a->question->description}}</p>
                            </div>

                            <!-- multiple choice question -->     
                            <?php if($a['type'] == 'multiple_choice'):?>
                            <ul>
                                <?php foreach($a['submitted_options'] as $option): ?>
                                <li>
                                    <input <?php echo $option->answer == 1 ? 'checked' : "" ;?> type="checkbox">						
                                    <label><span class="letter"><?php echo $option->prefix; ?></span> <?php echo $option->option; ?></label>						
                                    <div class="check"></div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif;?>

                            <!-- single choice option --> 
                            <?php if($a['type'] == 'single_choice'):?>
                            <ul>
                                <?php foreach($a['submitted_options'] as $option): ?>
                                <li>
                                    <input <?php echo $option->answer == 1 ? 'checked' : "" ;?> type="radio">						
                                    <label><span class="letter"><?php echo $option->prefix; ?></span> <?php echo $option->option; ?></label>						
                                    <div class="check"></div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>

                            <!-- true false -->
                            <?php if($a['type'] == 'true_false'):?>
                            <ul>
                                <?php foreach($a['submitted_options'] as $option): ?>
                                <li>
                                    <input <?php echo $option->answer == 1 ? 'checked' : "" ;?> type="radio" value="1" />
                                    <label><span class="letter"> T </span> True</label>
                                    <div class="check"></div>
                                </li>
                                <li>
                                    <input <?php echo $option->answer == 0 ? 'checked' : "" ;?> type="radio" value="0" />
                                    <label><span class="letter"> F </span> False</label>
                                    <div class="check"></div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    <?php $i++; endforeach; ?>
                </div>
            </div>  
            <center><span><a class="text-info" href="{{route('course.modules',['course_slug' => $course->slug])}}"><- Go Back To Modules</a></span></center>
        </section>
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
