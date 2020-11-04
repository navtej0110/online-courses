@extends('layouts.admin.layout-left')

@section('title')
Course : {{$course->name}}
@endsection

@section('footer-js')
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#search').multiselect({
            search: {
                left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
            },
            fireSearch: function (value) {
                return value.length > 3;
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#add-form").validate({
            rules: {
                to: {
                    required: true,
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
                @include('helpers.flash-message')
                <form id="add-form" method="POST" action='{{ route("admin.course.addupdatetests", ["course_id"=>$course->id]) }}'>
                    @csrf
                    <div class="row" style="padding:15px;min-height: 550px">
                        <div class="col-xs-5">
                            <select name="from[]" id="search" class="form-control" size="8" multiple="multiple">
                                <?php if (sizeof($tests) > 0): foreach ($tests as $test): ?>
                                        <option value="<?php echo $test->id; ?>"><?php echo $test->name; ?></option>
                                    <?php endforeach;
                                endif; ?>
                            </select>
                        </div>

                        <div class="col-xs-2">
                            <button type="button" id="search_rightAll" class="btn btn-block"><i class="icon-android-arrow-dropright-circle"></i></i></button>
                            <button type="button" id="search_rightSelected" class="btn btn-block"><i class="icon-android-arrow-forward"></i></button>
                            <button type="button" id="search_leftSelected" class="btn btn-block"><i class="icon-android-arrow-back"></i></i></button>
                            <button type="button" id="search_leftAll" class="btn btn-block"><i class="icon-android-arrow-dropleft-circle"></i></button>
                        </div>

                        <div class="col-xs-5">
                            <select name="to[]" id="search_to" class="form-control" size="8" multiple="multiple">
                            <?php if(sizeof($courseTests) > 0): foreach($courseTests as $ctest):?>
                                <option value="<?php echo $ctest->test_id ;?>"><?php echo $ctest->test->name; ?></option>
                            <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-actions">
                                <button onclick="window.location.reload()" type="button" class="btn btn-warning mr-1">
                                    <i class="icon-cross2"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="icon-check2"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>                           
            </div>
        </div>
    </div>
</div>
</section>
@endsection