@extends('layouts.admin.layout-left')

@section('title')
Questions List : {{$test->name}}
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
    });</script>
@endsection

@section('content')
@include('helpers.flash-message')
<section class="card">
    <div class="card-block min-height">
        <div class="row">
            <div class="col-sm-12 col-lg-4 col-xl-2 float-right">
                <ul class="pl-0 list-unstyled">
                    <li class="mb-1">
                        <button type="button" onclick="window.location.href = '{{route('admin.question.add', ['test_id' => $test_id])}}'" class="btn btn-success btn-block"><i class="icon-android-add-circle"></i> Add Questions</button>
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
                    <th>Name</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Number Of Answers</th>
                    <th>Test Questions</th>
                    <th>Is Archived</th>
                    <th>Locked?</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (sizeof($records) > 0): foreach ($records as $record): ?>
                        <tr id="record-row-{{$record->id}}">
                            <th><input name="id" value="<?php echo $record->id; ?>" type="checkbox" /></th>
                            <td><?php echo $record->name; ?></td>
                            <td><?php echo $record->description; ?></td>
                            <td><?php 
                                switch($record->type){
                                    case 'single_choice':
                                        echo 'Single Choice';
                                        break;
                                    
                                    case 'multiple_choice':
                                        echo 'Multipe Choice';
                                        break;
                                    
                                    case 'true_false':
                                         echo 'True/False';
                                        break;
                                    
                                    case 'yes_no':
                                        echo 'Yes/No';
                                        break;
                                };?>
                            </td>
                            <td><?php echo count($record->answers); ?></td>
                            <td><?php echo $record->test_questions; ?></td>
                            <td><?php echo $record->is_archive ? '<i class="text-success icon-android-checkmark-circle"></i>' : '<i class="text-danger icon-android-close"></i>'; ?></td>
                            <td><?php echo $record->is_locked == 0 ? 'Open' : 'Locked'; ?></td>
                            <td class="right-align">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <?php if($record->is_locked == '0'): ?>
                                        <a class="dropdown-item" href="{{route('admin.question.edit',['test_id' => $record->test_id, 'id'=>$record->id])}}">Edit</a>
                                        <?php else: ?>
                                        <a class="dropdown-item" href="{{route('admin.question.edit',['test_id' => $record->test_id, 'id'=>$record->id])}}">View</a>
                                        <?php endif; ?>
                                        <a class="dropdown-item" onclick="GRID_CONTROLS.deleteEntry('{{route('admin.question.delete')}}', {{$record->id}})" href="javascript:void(0)" type="">Delete</a>
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
                    <th>Name</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Number Of Answers</th>
                    <th>Test Questions</th>
                    <th>Is Archived</th>
                    <th>Locked?</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
</section>
@endsection