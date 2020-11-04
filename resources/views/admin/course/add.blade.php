@extends('layouts.admin.layout-left')

@section('title')
Add Courses
@endsection

@section('footer-js')
@include('helpers.tinymce')
<script type="text/javascript">
    $(document).ready(function () {
        $("#add-form").validate({
            rules: {
                name: {
                    required: true,
                },
                minimum_minutes: {
                    required: true,
                },
                idle_time: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 8,
                }
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
                    <h4 class="card-title" id="basic-layout-form">Course Info</h4>
                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">
                        <form id="add-form" method="POST" action='{{ route("admin.course.addupdate", ["id"=>$id]) }}'>
                            @include('helpers.flash-message')
                            @csrf
                            <?php
                            $course = $data ?? '';
                            ?>
                            <?php if(isset($course->id) && !empty(isset($course->id))):?>
                            <input type="hidden" name="id" value="<?php echo $course->id; ?>" />
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
                                    <input type="text" value="<?php echo isset($course->name) ? $course->name : "" ;?>" id="companyName" class="form-control" placeholder="Course Name" name="name">
                                </div>
                                
                                <div class="form-group">
                                    @error('image')
                                    <div class="alert alert-danger mb-2" role="alert">
                                        <strong>Oh snap!</strong> {{ $message }}
                                    </div>
                                    @enderror
                                    <label for="image">Image*</label>
                                    <input type="text" value="<?php echo isset($course->image) ? $course->image : "" ;?>" id="image" class="form-control" placeholder="Course Image" name="image">
                                </div>

                                <div class="form-group">
                                    @error('description')
                                    <div class="alert alert-danger mb-2" role="alert">
                                        <strong>Oh snap!</strong> {{ $message }}
                                    </div>
                                    @enderror
                                    <label for="projectinput8">About Course(Description)</label>
                                    <textarea id="projectinput8" rows="5" class="form-control" name="description" placeholder="About Course"><?php echo isset($course->description) ? stripcslashes($course->description) : "" ;?></textarea>
                                </div>

                                <div class="form-group">
                                    @error('password')
                                    <div class="alert alert-danger mb-2" role="alert">
                                        <strong>Oh snap!</strong> {{ $message }}
                                    </div>
                                    @enderror
                                    <label for="companyName">Password*</label>
                                    <input type="text" value="<?php echo isset($course->password) ? $course->password : "" ;?>" id="Password" class="form-control" placeholder="Password" name="password">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="projectinput5">Status</label>
                                            <select id="status" name="status" class="form-control">
                                                <option value="1" <?php echo isset($course->status) && $course->status == '1' ? "selected" : ""  ;?>>Active</option>
                                                <option value="0" <?php echo isset($course->status) && $course->status == '0' ? "selected" : ""  ;?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="projectinput5">Is Archive </label>
                                            <select id="status" name="is_archive" class="form-control">
                                                <option value="0" <?php echo isset($course->is_archive) && $course->is_archive == '0' ? "selected" : ""  ;?>>No</option>
                                                <option value="1" <?php echo isset($course->is_archive) && $course->is_archive == '1' ? "selected" : ""  ;?>>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr />
                                <div class="row">
                                    <div class="col-sm-12"><label>Assign Categories</label></div>
                                    <?php foreach($categories as $category):?>
                                    <div class="col-lg-2 col-sm-4">
                                        <div class="form-group">
                                            <label for="include_certificate"> <input value="{{$category->id}}" type="checkbox" name="category[]" <?php echo (in_array($category->id, $selected_category)) ? 'checked' : '';?> />{{$category->name}}</label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <hr />
                                <!-- -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="include_certificate">Include Certificate </label>
                                            <select id="status" name="include_certificate" class="form-control">
                                                <option value="0" <?php echo isset($course->include_certificate) && $course->include_certificate == '0' ? "selected" : ""  ;?>>No</option>
                                                <option value="1" <?php echo isset($course->include_certificate) && $course->include_certificate == '1' ? "selected" : ""  ;?>>Yes</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="is_beginner">Is Beginner </label>
                                            <select id="status" name="is_beginner" class="form-control">
                                                <option value="0" <?php echo isset($course->is_beginner) && $course->is_beginner == '0' ? "selected" : ""  ;?>>No</option>
                                                <option value="1" <?php echo isset($course->is_beginner) && $course->is_beginner == '1' ? "selected" : ""  ;?>>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="is_intermediate">Is Intermediate </label>
                                            <select id="status" name="is_intermediate" class="form-control">
                                                <option value="0" <?php echo isset($course->is_intermediate) && $course->is_intermediate == '0' ? "selected" : ""  ;?>>No</option>
                                                <option value="1" <?php echo isset($course->is_intermediate) && $course->is_intermediate == '1' ? "selected" : ""  ;?>>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="is_advanced">Is Advanced </label>
                                            <select id="status" name="is_advanced" class="form-control">
                                                <option value="0" <?php echo isset($course->is_advanced) && $course->is_advanced == '0' ? "selected" : ""  ;?>>No</option>
                                                <option value="1" <?php echo isset($course->is_advanced) && $course->is_advanced == '1' ? "selected" : ""  ;?>>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="price">Price($) </label>
                                            <input type="number" value="<?php echo isset($course->price) ? $course->price : "" ;?>" id="price" class="form-control" placeholder="20" name="price">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        @error('description')
                                        <div class="alert alert-danger mb-2" role="alert">
                                            <strong>Oh snap!</strong> {{ $message }}
                                        </div>
                                        @enderror
                                        <div class="form-group">
                                            <label for="price">Idle Time*</label>
                                            <input min="0" type="number" value="<?php echo isset($course->idle_time) ? $course->idle_time : "" ;?>" id="idle_time" class="form-control" placeholder="In Minutes" name="idle_time">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        @error('description')
                                        <div class="alert alert-danger mb-2" role="alert">
                                            <strong>Oh snap!</strong> {{ $message }}
                                        </div>
                                        @enderror
                                        <div class="form-group">
                                            <label for="price">Minimum Time*</label>
                                            <input min="0" type="number" value="<?php echo isset($course->minimum_minutes) ? $course->minimum_minutes : "" ;?>" id="minimum_minutes" class="form-control" placeholder="In Minutes" name="minimum_minutes">
                                        </div>
                                    </div>
                                    
                                </div>
                                <!-- -->
                                
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-warning mr-1" onclick="window.location.reload();">
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