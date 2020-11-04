@extends('layouts.admin.layout-left')

@section('title')
Add Topic : <a href="{{route('admin.topics.index',['chapter_id' => $chapter->id])}}">{{$chapter->name}}</a>
@endsection

@section('breadcrumb')

@endsection

@section('header-css')
<link type="text/css" rel="stylesheet" href="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.css" />
@endsection

@section('footer-js')
@include('helpers.tinymce')
<script type="text/javascript">
    $(document).ready(function () {
        $("#add-form").validate({
            rules: {
                title: {
                    required: true,
                },
                content: {
                    required: true,
                },
                video_1: {
                    required: true,
                },
                lesson_duration: {
                    required: true,
                },
                chek_your_knowledge_duration: {
                    required: true,
                }
            }
        });
    });
</script>

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
                    <h4 class="card-title" id="basic-layout-form">Course Topic</h4>
                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">
                        <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <span class="modal-title" id="exampleModalLabel"><b>Set Mini Quiz</b></span>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <?php if(!empty($topic_id)): ?>
                                    <div id="app" class="modal-body modal-vue-questionbank">
                                            <?php if ($not_found == 0): ?>
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
                                                    >
                                            </vue-questionsbank>
                                        <?php else: ?>
                                            <h2>Invalid Question or Question not Found!</h2>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                        </div>
                    </div>

                    <form id="add-form" method="POST" action='{{ route("admin.topics.addEdit", ["chapter_id" => $chapter->id, "topic_id" => $topic_id]) }}'>
                        @include('helpers.flash-message')
                        @csrf
                        <?php
                        $course = $results ?? '';
                        ?>
                        <?php if (isset($course->id) && !empty(isset($course->id))): ?>
                            <input type="hidden" name="id" value="<?php echo $course->id; ?>" />
                        <?php endif; ?>

                        <div class="form-body">
                            <h4 class="form-section"><i class="icon-clipboard4"></i> Requirements 
                                <?php if(!empty($topic_id)): ?>
                                    <span style="cursor:pointer;" class="text-success" data-toggle="modal" data-target=".bd-example-modal-xl"><i class="icon-perm_data_setting"></i>Mini Quiz</span>
                                <?php endif; ?>
                            </h4>

                            <div class="form-group">
                                @error('title')
                                <div class="alert alert-danger mb-2" role="alert">
                                    <strong>Oh snap!</strong> {{ $message }}
                                </div>
                                @enderror
                                <label for="companyName">Title*</label>
                                <input type="text" value="<?php echo isset($course->title) ? $course->title : ""; ?>" id="title" class="form-control" placeholder="Title" name="title">
                            </div>

                            <div class="form-group">
                                @error('video_1')
                                <div class="alert alert-danger mb-2" role="alert">
                                    <strong>Oh snap!</strong> {{ $message }}
                                </div>
                                @enderror
                                <label for="companyName">Video Url*(Youtube, Vimeo etc)</label>
                                <input type="text" value="<?php echo isset($course->video_1) ? $course->video_1 : ""; ?>" id="video" class="form-control" placeholder="Video" name="video_1">
                            </div>

                            <div class="form-group">
                                @error('content')
                                <div class="alert alert-danger mb-2" role="alert">
                                    <strong>Oh snap!</strong> {{ $message }}
                                </div>
                                @enderror
                                <label for="projectinput8">Content Main*</label>
                                <textarea id="projectinput8" rows="5" class="form-control" name="content" placeholder="Content"><?php echo isset($course->content) ? stripcslashes($course->content) : ""; ?></textarea>
                            </div>

                            <div class="form-group">
                                @error('key_learnings')
                                <div class="alert alert-danger mb-2" role="alert">
                                    <strong>Oh snap!</strong> {{ $message }}
                                </div>
                                @enderror
                                <label for="projectinput8">Key Learnings</label>
                                <textarea id="projectinput8" rows="5" class="form-control" name="key_learnings" placeholder="Key Learnings"><?php echo isset($course->key_learnings) ? stripcslashes($course->key_learnings) : ""; ?></textarea>
                            </div>

                            <div class="form-group">
                                @error('check_your_knowledge')
                                <div class="alert alert-danger mb-2" role="alert">
                                    <strong>Oh snap!</strong> {{ $message }}
                                </div>
                                @enderror
                                <label for="projectinput8">Check Your Knowledge</label>
                                <textarea id="projectinput8" rows="5" class="form-control" name="check_your_knowledge" placeholder="Check Your Knowledge"><?php echo isset($course->check_your_knowledge) ? stripcslashes($course->check_your_knowledge) : ""; ?></textarea>
                            </div>
                            
                            <div class="row">
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                            <label for="is_locked">Is Locked? </label>
                                            <select id="is_locked" name="is_locked" class="form-control">
                                                    <option value="0" <?php echo isset($course->is_locked) && $course->is_locked == '0' ? "selected" : ""  ;?>>No</option>
                                                    <option value="1" <?php echo isset($course->is_locked) && $course->is_locked == '1' ? "selected" : ""  ;?>>Yes</option>
                                            </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        @error('chek_your_knowledge_duration')
                                        <div class="alert alert-danger mb-2" role="alert">
                                            <strong>Oh snap!</strong> {{ $message }}
                                        </div>
                                        @enderror
                                        <label for="projectinput5">Check Your Knowledge Duration</label>
                                        <input type="number" min="0" value="<?php echo isset($course->chek_your_knowledge_duration) ? $course->chek_your_knowledge_duration : ""; ?>" id="video" class="form-control" placeholder="In Minutes" name="chek_your_knowledge_duration">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        @error('lesson_duration')
                                        <div class="alert alert-danger mb-2" role="alert">
                                            <strong>Oh snap!</strong> {{ $message }}
                                        </div>
                                        @enderror
                                        <label for="lesson_duration">Lesson Duration </label>
                                        <input type="number" min="0" value="<?php echo isset($course->lesson_duration) ? $course->lesson_duration : ""; ?>" id="video" class="form-control" placeholder="In Minutes" name="lesson_duration">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="projectinput5">Status</label>
                                        <select id="status" name="status" class="form-control">
                                            <option value="1" <?php echo isset($course->status) && $course->status == '1' ? "selected" : ""; ?>>Active</option>
                                            <option value="0" <?php echo isset($course->status) && $course->status == '0' ? "selected" : ""; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="projectinput5">Is Archive </label>
                                        <select id="status" name="is_archive" class="form-control">
                                            <option value="0" <?php echo isset($course->is_archive) && $course->is_archive == '0' ? "selected" : ""; ?>>No</option>
                                            <option value="1" <?php echo isset($course->is_archive) && $course->is_archive == '1' ? "selected" : ""; ?>>Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-warning mr-1">
                                <i class="icon-cross2"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-check2"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
