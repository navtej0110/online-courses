@extends('layouts.admin.layout-left')

@section('title')
<?php if(!empty($chapter)): ?>
Topics List : {{$chapter->name}}
<?php else: ?>
Topics List
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
            <?php if(!empty($chapter)): ?>
            <div class="col-sm-12 col-lg-4 col-xl-2 float-right">
                <ul class="pl-0 list-unstyled">
                    <li class="mb-1">
                        <?php
                        $add = route('admin.topics.add',['chapter_id' => $chapter->id]);
                        ?>
                        <button type="button" onclick="window.location.href = '<?php echo $add; ?>'" class="btn btn-success btn-block"><i class="icon-android-add-circle"></i> Add Topic</button>
                    </li>
                </ul>
            </div>
            <?php endif;?>
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
                    <th>Title</th>
                    <th>Chapter</th>
                    <th>Is Locked?</th>
                    <th>Lesson Duration</th>
                    <th>Check Knowledge Duration</th>
                    <th>Is Archived</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (sizeof($results) > 0): foreach ($results as $course): ?>
                        <?php if(!empty($chapter)): ?>
                        <?php  $edit = route('admin.topics.edit',['chapter_id' => $chapter->id, 'topic_id' => $course->id]);  ?>
                        <?php else: ?>
                        <?php  $edit = route('admin.topics.edit',['chapter_id' => $course->chapter->id, 'topic_id' => $course->id]);  ?>
                        <?php endif; ?>
                        <tr id="record-row-{{$course->id}}">
                            <th><input name="id" value="<?php echo $course->id; ?>" type="checkbox" /></th>
                            <td><?php echo $course->id; ?></td>
                            <td><?php echo $course->title; ?></td>
                            <td><a target="_blank" href="{{route('admin.topics.index',['chapter_id'=>$course->chapter->id])}}"><?php echo $course->chapter->name; ?></a></td>
                            <td><?php echo $course->is_locked == 0 ? 'Open' : 'Locked' ?></td>
                            <td><?php echo $course->lesson_duration; ?> min</td>
                            <td><?php echo $course->chek_your_knowledge_duration; ?> min</td>
                            <td><?php echo $course->is_archive ? '<i class="text-success icon-android-checkmark-circle"></i>' : '<i class="text-danger icon-android-close"></i>'; ?></td>
                            <td class="right-align">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <?php if($course->is_locked == '0'):?>
                                        <a class="dropdown-item" href="<?php echo $edit; ?>">Edit</a>
                                        <?php else: ?>
                                        <a class="dropdown-item" href="<?php echo $edit; ?>">View</a>
                                        <?php endif; ?>
                                        
                                        <a class="dropdown-item" onclick="" href="javascript:void(0)" type="">Delete</a>
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
                    <th>Title</th>
                    <th>Chapter</th>
                    <th>Is Locked?</th>
                    <th>Lesson Duration</th>
                    <th>Check Knowledge Duration</th>
                    <th>Is Archived</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
</section>
@endsection