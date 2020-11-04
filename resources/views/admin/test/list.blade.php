@extends('layouts.admin.layout-left')

@section('title')
Modules List
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
@include('helpers.flash-message')
<section class="card">
    <div class="card-block min-height">
        <div class="row">
            <div class="col-sm-12 col-lg-4 col-xl-2 float-right">
                <ul class="pl-0 list-unstyled">
                    <li class="mb-1">
                        <button type="button" onclick="window.location.href = '{{route('admin.test.add')}}'" class="btn btn-success btn-block"><i class="icon-android-add-circle"></i> Add Modules</button>
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
                    <th>Time Limit</th>
                    <th>Idle Time</th>
                    <th>Chapters</th>
                    <th>Courses</th>
                    <th>Archived?</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (sizeof($records) > 0): foreach ($records as $record): ?>
                        <tr id="record-row-{{$record->id}}">
                            <th><input name="id" value="<?php echo $record->id; ?>" type="checkbox" /></th>
                            <td><?php echo $record->id; ?></td>
                            <td><?php echo $record->name; ?></td>
                            <td><?php echo $record->time_limit; ?></td>
                            <td><?php echo $record->idle_time; ?></td>
                            <td><?php echo count($record->chapters); ?></td>
                            <td style="font-size: 13px;">
                                <?php
                                if(sizeof($record->courses) > 0){
                                    echo '<ol>';
                                    foreach ($record->courses as $m){
                                        echo '<li><a href="'.route('admin.course.tests',['course_id'=>$m->course->id]).'">'.$m->course->name.'</a></li>';
                                    }
                                    echo '</ol>';
                                }
                                ?>
                            </td>
                            <!--<td><?php //echo $record->status == 1 ? '<i class="text-success icon-android-checkmark-circle"></i>' : '<i class="text-danger icon-android-close"></i>'; ?></td>-->
                    <td><?php echo $record->is_archive == 1 ? '<i class="text-success icon-android-checkmark-circle"></i>' : '<i class="text-danger icon-android-close"></i>'; ?></td>
                    <td class="right-align">
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{route('admin.chapter.add',['test_id'=>$record->id])}}">Add Chapter</a>
                                <a class="dropdown-item" href="{{route('admin.test.edit',['id'=>$record->id])}}">Edit</a>
                                <a class="dropdown-item" onclick="GRID_CONTROLS.deleteEntry('{{route('admin.test.delete')}}', {{$record->id}})" href="javascript:void(0)" type="">Delete</a>
                                <a class="dropdown-item" href="{{route('admin.chapter.list',['test_id'=>$record->id])}}">Chapters</a>
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
                    <th>Time Limit</th>
                    <th>Idle Time</th>
                    <th>Chapters</th>
                    <th>Courses</th>
                    <th>Archived?</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
</section>
@endsection