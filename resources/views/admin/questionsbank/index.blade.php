@extends('layouts.admin.layout-left')

@section('title')
Question/Answers : <a href="{{route('admin.question-bank.getChapterQuestions',['chapter_id' => $chapter->id])}}">{{$chapter->name}}</a>
@endsection

@section('header-css')
<link type="text/css" rel="stylesheet" href="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.css" />
@endsection

@section('footer-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.11/vue.js" integrity="sha256-NSuqgY2hCZJUN6hDMFfdxvkexI7+iLxXQbL540RQ/c4=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js" integrity="sha256-T/f7Sju1ZfNNfBh7skWn0idlCBcI3RwdLSS4/I7NQKQ=" crossorigin="anonymous"></script>
<script src="{{ url('/js/vue-questionsbank.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    new Vue({el: '#app'});
</script>
@endsection

@section('content')
<section id="basic-form-layouts">
    <div class="row match-height">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="basic-layout-form">Questions Info</h4>
                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div style="min-height: 450px;" class="card-body collapse in">
                    <div class="card-block">
                        <div id="app">
                            <?php if($not_found == 0): ?>
                            <vue-questionsbank 
                                :quiz-types="{{$quizTypes}}"
                                :display-types="[{name:'Slider',value:'slider'},{name:'Default',value:'default'}]"
                                allow-questions="{{$allow_questions}}"
                                chapter-id="{{$chapter_id}}"
                                topic-id="{{$topic_id}}"
                                id="{{$id}}"
                                get-url="{{$getUrl}}"
                                post-url="{{$postUrl}}"
                                delete-url="{{$deleteUrl}}"
                                is_locked="{{$is_locked}}"
                            >
                            </vue-questionsbank>
                            <?php else: ?>
                            <h2>Invalid Question or Question not Found!</h2>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection