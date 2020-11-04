@extends('layouts.admin.layout-left')

@section('title')
Course List
@endsection

@section('meta-info')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('footer-js')
<script type="text/javascript">
    $(document).ready(function () {
        $('#data-list').DataTable({
            responsive: true
        });
    });
</script>
@endsection

@section('content')
<section class="card">
    <div class="card-block min-height">
        <div class="row">
            <div class="col-sm-12 col-lg-4 col-xl-2 float-right">
                <ul class="pl-0 list-unstyled">
                    <li class="mb-1">
                        <button type="button" onclick="window.location.href = '{{route('admin.course.add')}}'" class="btn btn-success btn-block"><i class="icon-android-add-circle"></i> Add Course</button>
                    </li>
                </ul>
            </div>
            <div class="col-sm-12 col-lg-4 col-xl-2 float-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-warning" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions
                    </button>
                    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="">Delete Selected</a>
                        <!--<a class="dropdown-item" type="">Activate Selected</a>
                        <a class="dropdown-item" type="">Disable Selected</a>-->
                    </div>
                </div>
            </div>
        </div>
        <table id="data-list" class="display" style="width:100%">
            <thead>
                <tr>
                    <th><input name="select_all" value="1" id="checkbox-select-all" type="checkbox" /></th>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>Modules</th>
                    <th>Password</th>
                    <th>Status</th>
                    <th>Is Archived</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (sizeof($courses) > 0): foreach ($courses as $course): ?>
                        <tr id="record-row-{{$course->id}}">
                            <th><input name="id" value="<?php echo $course->id; ?>" type="checkbox" /></th>
                            <td><?php echo $course->id; ?></td>
                            <td><?php echo $course->name; ?></td>
                            <td style="font-size: 13px;">
                                <?php 
                                if(sizeof($course->modules) > 0){
                                    echo '<ol>';
                                    foreach ($course->modules as $m){
                                        echo '<li><a href="'.route('admin.chapter.list',['test_id'=>$m->test->id]).'">'.$m->test->name.'</a></li>';
                                    }
                                    echo '</ol>';
                                }
                                ?>
                            </td>
                            <td><?php echo $course->password; ?></td>
                            <td><?php echo $course->status == 1 ? '<i class="text-success icon-android-checkmark-circle"></i>' : '<i class="text-danger icon-android-close"></i>'; ?></td>
                    <td><?php echo $course->is_archive ? '<i class="text-success icon-android-checkmark-circle"></i>' : '<i class="text-danger icon-android-close"></i>'; ?></td>
                    <td class="right-align">
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{route('admin.course.edit',['id'=>$course->id])}}">Edit</a>
                                <a class="dropdown-item" onclick="GRID_CONTROLS.deleteEntry('{{route('admin.course.delete')}}', {{$course->id}})" href="javascript:void(0)" type="">Delete</a>
                                <a class="dropdown-item" href="{{route('admin.course.tests',['course_id' => $course->id])}}" type="">Assign Modules</a>
                            </div>
                        </div>
                    </td>
                    </tr>
                    <?php
                endforeach;
            endif;
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>Modules</th>
                    <th>Password</th>
                    <th>Status</th>
                    <th>Is Archived</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
</section>
@endsection