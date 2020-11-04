
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
    <div class="container-fluid">
        <div class="section-heading text-center">
            <h2>{{$course->name}}</h2>
            <p class="text-muted">{{$course->description}}</p>
            <hr>
            @include('helpers.flash-message')
        </div>
        <h2 class="module-title"> Modules ({{count($modules)}}) </h2>
        <div class="accrodion-section">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                <?php foreach($modules as $module): $i = 1; ?>
                <div class="panel panel-default">
                    <div class="panel-heading active" role="tab" id="heading_{{$module->test->id}}">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{$module->test->id}}" aria-expanded="true" aria-controls="collapseOne">
                                {{$module->test->name}} ({{count($module->test->chapters)}})
                            </a>
                        </h4>
                    </div>
                    <div id="collapse_{{$module->test->id}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_{{$module->test->id}}">
                        <?php foreach($module->test->chapters as $chapter):?>
                        <div class="panel-body">
                            <a href="" class="module-progress-card">
                                <div class="module-progress-card-icon">
                                    <img src="https://lh3.googleusercontent.com/7nId5qqZMpCWyJRM7Ug8wiVAOaWOPlkIjnzHXHOdwZG2DA7jQ9ze8Mv4PnPiOCWYiZnKS6qwGffTR0gJuZlZb6_39ZExnkz7AAZfmL8" alt="The online opportunity">
                                </div>
                                <h4 class="module-progress-card-title">{{$chapter->name}}</h4>
                                <p class="module-progress-card-duration">
                                    <i class="fa fa-clock Icons"> </i><span>15 min</span>
                                </p>
                                <div class="module-progress-card-progress">        
                                    <div class="module-progress-card-progress-bar"><span style="width: 0%;"></span></div>
                                    <span class="module-progress-card-progress-label">0%</span>
                                </div>
                                <div class="module-progress-card-cta">
                                    <i class="fa fa-arrow-right right-arrow"> </i>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php $i++; endforeach; ?>
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