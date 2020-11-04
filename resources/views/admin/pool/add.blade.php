@extends('layouts.admin.layout-left')

@section('title')
Add Pool
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
                    <h4 class="card-title" id="basic-layout-form">Pool Info</h4>
                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">
                        <form id="add-form" method="POST" action='{{ route("admin.pool.addupdate", ["id"=>$id]) }}'>
                            @include('helpers.flash-message')
                            @csrf
                            <?php
                            $result = $data ?? '';
                            ?>
                            <?php if(isset($result->id) && !empty(isset($result->id))):?>
                            <input type="hidden" name="id" value="<?php echo $result->id; ?>" />
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
                                    <input type="text" value="<?php echo isset($result->name) ? $result->name : "" ;?>" id="companyName" class="form-control" placeholder="Course Name" name="name">
                                </div>

                                <div class="form-group">
                                    @error('description')
                                    <div class="alert alert-danger mb-2" role="alert">
                                        <strong>Oh snap!</strong> {{ $message }}
                                    </div>
                                    @enderror
                                    <label for="projectinput8">Description</label>
                                    <textarea id="projectinput8" rows="5" class="form-control" name="description" placeholder="About Course"><?php echo isset($result->description) ? stripcslashes($result->description) : "" ;?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="projectinput5">Status</label>
                                            <select id="status" name="status" class="form-control">
                                                <option value="1" <?php echo isset($result->status) && $result->status == '1' ? "selected" : ""  ;?>>Active</option>
                                                <option value="0" <?php echo isset($result->status) && $result->status == '0' ? "selected" : ""  ;?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="projectinput5">Is Archive </label>
                                            <select id="status" name="is_archive" class="form-control">
                                                <option value="0" <?php echo isset($result->is_archive) && $result->is_archive == '0' ? "selected" : ""  ;?>>No</option>
                                                <option value="1" <?php echo isset($result->is_archive) && $result->is_archive == '1' ? "selected" : ""  ;?>>Yes</option>
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