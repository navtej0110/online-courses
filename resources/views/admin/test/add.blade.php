@extends('layouts.admin.layout-left')

@section('title')
Add/Edit Module
@endsection

@section('footer-js')
<!--@include('helpers.tinymce')-->
<script type="text/javascript">
    $(document).ready(function () {
        $("#add-form").validate({
            rules: {
                name: {
                    required: true,
                },
                time_limit: {
                    required: true,
                },
                idle_time: {
                    required: true,
                },
                /*content: {
                    required: true,
                }*/
            }
        });
    });
</script>
@endsection

@section('content')
<section id="basic-form-layouts">
    <div class="row match-height">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="basic-layout-form">Test Info</h4>
                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">
                        <form id="add-form" method="POST" action='{{ route("admin.test.addupdate", ["id"=>$id]) }}'>
                            @include('helpers.flash-message')
                            @csrf
                            <?php if(isset($data->id) && !empty(isset($data->id))):?>
                            <input type="hidden" name="id" value="<?php echo $data->id; ?>" />
                            <?php endif;?>
                            
                            <div class="form-body">
                                <h4 class="form-section"><i class="icon-clipboard4"></i> Requirements</h4>

                                <div class="form-group">
                                    @error('name')
                                    <div class="alert alert-danger mb-2" role="alert">
                                        <strong>Oh snap!</strong> {{ $message }}
                                    </div>
                                    @enderror
                                    <label for="companyName">Name*</label>
                                    <input type="text" value="<?php echo isset($data->name) ? $data->name : Request::old('name') ;?>" id="companyName" class="form-control" placeholder="Course Name" name="name">
                                </div>

                                <div class="form-group">
                                    @error('description')
                                    <div class="alert alert-danger mb-2" role="alert">
                                        <strong>Oh snap!</strong> {{ $message }}
                                    </div>
                                    @enderror
                                    <label for="projectinput8">Description</label>
                                    <textarea id="projectinput8" rows="5" class="form-control" name="description" placeholder="About Course"><?php echo isset($data->description) ? stripcslashes($data->description) :  Request::old('description') ;?></textarea>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        @error('time_limit')
                                        <div class="alert alert-danger mb-2" role="alert">
                                            <strong>Oh snap!</strong> {{ $message }}
                                        </div>
                                        @enderror
                                        <div class="form-group">
                                            <label for="projectinput5">Time Limit</label>
                                            <input type="number" value="<?php echo isset($data->time_limit) ? $data->time_limit : Request::old('time_limit') ;?>" id="time_limit" class="form-control" placeholder="In Seconds" name="time_limit">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        @error('idle_time')
                                        <div class="alert alert-danger mb-2" role="alert">
                                            <strong>Oh snap!</strong> {{ $message }}
                                        </div>
                                        @enderror
                                        <div class="form-group">
                                            <label for="projectinput5">Idle Time</label>
                                            <input type="number" value="<?php echo isset($data->idle_time) ? $data->idle_time : Request::old('idle_time') ;?>" id="idle_time" class="form-control" placeholder="In Seconds" name="idle_time">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    @error('content')
                                    <div class="alert alert-danger mb-2" role="alert">
                                        <strong>Oh snap!</strong> {{ $message }}
                                    </div>
                                    @enderror
                                    <label for="projectinput8">Page Content</label>
                                    <textarea id="content" rows="5" class="editor form-control" name="content" placeholder="content"><?php echo isset($data->content) ? stripcslashes($data->content) : "" ;?></textarea>
                                </div>                                
                            </div>
                            
                            <div class="row">
                                    <!--<div class="col-md-6">
                                        <div class="form-group">
                                            <label for="projectinput5">Scatter</label>
                                            <select id="status" name="scatter" class="form-control">
                                                <option value="1" <?php //echo isset($data->scatter) && $errors->scatter == '1' ? "selected" : ""  ;?>>Yes</option>
                                                <option value="0" <?php //echo isset($data->scatter) && $errors->scatter == '0' ? "selected" : ""  ;?>>No</option>
                                            </select>
                                        </div>
                                    </div>-->
                                    
                                    <div class="col-md-4">
                                            <div class="form-group">
                                                    <label for="is_locked">Is Locked? </label>
                                                    <select id="is_locked" name="is_locked" class="form-control">
                                                        <option value="0" <?php echo isset($data->is_locked) && $data->is_locked == '0' ? "selected" : ""  ;?>>No</option>
                                                        <option value="1" <?php echo isset($data->is_locked) && $data->is_locked == '1' ? "selected" : ""  ;?>>Yes</option>
                                                    </select>
                                            </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">Status </label>
                                            <select id="status" name="status" class="form-control">
                                                <option value="1" <?php echo isset($data->status) && $errors->status == '1' ? "selected" : ""  ;?>>Active</option>
                                                <option value="0" <?php echo isset($data->status) && $errors->status == '0' ? "selected" : ""  ;?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_archive">Is Archive </label>
                                            <select id="status" name="is_archive" class="form-control">
                                                <option value="0" <?php echo isset($data->is_archive) && $errors->is_archive == '0' ? "selected" : ""  ;?>>No</option>
                                                <option value="1" <?php echo isset($data->is_archive) && $errors->is_archive == '1' ? "selected" : ""  ;?>>Yes</option>
                                            </select>
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