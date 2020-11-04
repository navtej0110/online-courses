<?php
if(sizeof($data) > 0){
    foreach($data as $k => $d){
        $edit = route('admin.user.edit',["id" => $d->id]);
        $delete = 'GRID_CONTROLS.deleteEntry(\''.route('admin.user.delete').'\','.$d->id.')';
        $data[$k]['is_archive'] = $d->is_archive == 1 ? '<center><i class="text-success icon-android-checkmark-circle"></i></center>' : '<center><i class="text-danger icon-android-close"></i></center>';
        $data[$k]['actions'] = '<div class="btn-group">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.$edit.'">Edit</a>
                    <a class="dropdown-item" onclick="'.$delete.'" href="javascript:void(0)" type="">Delete</a>
                    <a class="dropdown-item" type="">Courses Enrolled</a>
                </div>
            </div>';
    }
}
echo json_encode([
        'recordsTotal' => $recordsTotal,
        'data' => $data,
        'recordsFiltered' => $recordsFiltered
    ]
);
?>
