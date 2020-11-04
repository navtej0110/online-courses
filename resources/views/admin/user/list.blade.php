@extends('layouts.admin.layout-left')

@section('title')
Users List
@endsection

@section('meta-info')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('footer-js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#data-list').DataTable({
            "aoColumnDefs": [
                { "bSortable": false, "aTargets": [ 2 ] },
            ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                data:{"_token": "{{ csrf_token() }}"},
                "url": "{{route('admin.user.listajax')}}",
                "type": "POST"
            },
            "columns": [
                { "data": "name" },
                { "data": "email" },
                { "data" : "is_archive"},
                { "data" : "actions"}                
            ]
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
                        <button type="button" onclick="window.location.href = '{{route('admin.user.add')}}'" class="btn btn-success btn-block"><i class="icon-android-add-circle"></i> Add User</button>
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
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>Disabled</th>
                    <th>Actions</th>
                </tr>
            </thead>
            
            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>Disabled</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
</section>
@endsection