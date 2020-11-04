@extends('layouts.admin.layout-left')

@section('title')
<?php if(!empty($test)): ?>
Chapters List : {{$test->name}}
<?php else: ?>
Chapters List
<?php endif; ?>
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
            <?php if(!empty($test)): ?>
            <div class="col-sm-12 col-lg-4 col-xl-2 float-right">
                <ul class="pl-0 list-unstyled"> 
                    <li class="mb-1">
                        <?php  $add = route('admin.chapter.add',['test_id'=> $test_id]);  ?>
                        <button type="button" onclick="window.location.href = '<?php echo $add; ?>'" class="btn btn-success btn-block"><i class="icon-android-add-circle"></i> Add Chapter</button>
                    </li>
                </ul>
            </div>
            <?php endif; ?>
            
            <div class="col-sm-12 col-lg-4 col-xl-2 float-right" style="margin-bottom: 20px;">
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
                    <th>Module</th>
                    <th>Is Locked</th>
                    <th>Is Archived</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (sizeof($results) > 0): foreach ($results as $course): ?>
                        <?php if(!empty($test)): ?>
                            <?php $edit = route('admin.chapter.edit',['test_id'=> $test_id,'id' => $course->id]); ?>
                        <?php else: ?>
                            <?php $edit = route('admin.chapter.edit',['test_id'=> $course->module->id,'id' => $course->id]); ?>
                        <?php endif;?>
                        <tr id="record-row-{{$course->id}}">
                            <th><input name="id" value="<?php echo $course->id; ?>" type="checkbox" /></th>
                            <td><?php echo $course->id; ?></td>
                            <td><?php echo $course->name; ?></td>
                            <td><a target="_blank" href="{{route('admin.chapter.list',['test_id'=>$course->test_id])}}"><?php echo $course->module->name; ?></a></td>
                            <td><?php echo $course->is_locked == 0 ? 'Open' : 'Locked'; ?></td>
                            <td><?php echo $course->is_archive ? '<i class="text-success icon-android-checkmark-circle"></i>' : '<i class="text-danger icon-android-close"></i>'; ?></td>
                            <td class="right-align">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="{{route('admin.topics.add',['chapter_id'=>$course->id])}}">Add Topic</a>
                                        
                                        <?php if($course->is_locked == '0'):?>
                                        <a class="dropdown-item" href="<?php echo $edit; ?>">Edit</a>
                                        <?php else: ?>
                                        <a class="dropdown-item" href="<?php echo $edit; ?>">View</a>
                                        <?php endif; ?>
                                        <!--<a class="dropdown-item" onclick="GRID_CONTROLS.deleteEntry('<?php //echo $delete; ?>}}', {{$course->id}})" href="javascript:void(0)" type="">Delete</a>-->
                                        <a class="dropdown-item" href="{{route('admin.question-bank.getChapterQuestions',['chapter_id' => $course->id])}}" type="">Main Quiz</a>
                                        <a class="dropdown-item" href="{{route('admin.topics.index',['chapter_id' => $course->id])}}" type="">Topics</a>
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
                    <th>Module</th>
                    <th>Description</th>
                    <th>Is Archived</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
</section>
@endsection