@extends('main::Layouts.main')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{$role->display_name}}
                        <a class="btn btn-danger btn-circle pull-right" data-toggle="tooltip" title="Back"
                           href="{{route('acl.roles.index')}}"><i class="fa fa-times"></i></a>
                        <a class="btn btn-success btn-circle pull-right m-r-10" data-toggle="tooltip" title="Edit"
                           href="{{route('acl.roles.edit',$role->id)}}"><i class="fa fa-pencil"></i></a>
                    </h4>
                    <div class="p-20">
                        <div class="form-group">
                            <h4><strong>Slug</strong>: {{$role->name}}</h4>
                        </div>
                        <div class="form-group">
                            <h4><strong>Description</strong>: {{$role->description}}</h4>
                        </div>
                        @if($role->name != 'superadministrator')
                            <div class="form-group">
                                <h5><strong>Permissions</strong></h5>
                                <div id="permissions_tree"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .list-group-item {
            border-radius: 0 !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- Treeview Plugin JavaScript -->
    <script src="Modules/Main/plugins/bootstrap-treeview-master/dist/bootstrap-treeview.min.js"></script>
    <script>
        $(document).ready(function () {
            var permissionsData = [
                    @foreach($permissions as $resource => $permission)
                {
                    text: '{{studly_case($resource ? :'basic')}}',
                    href: '#{{$resource ? :'basic'}}',
                    icon: 'fa fa-star',
                    tags: ['{{count($permission)}}'],
                    nodes: [
                            @foreach($permission as $perm)
                        {
                            text: '{{$perm->display_name." (".$perm->description.")"}}',
                            href: '#{{$perm->name}}',
                            tags: ['0']
                        },
                        @endforeach
                    ]
                },
                @endforeach
            ];

            $('#permissions_tree').treeview({

                expandIcon: 'fa fa-plus-square-o',
                onhoverColor: "rgba(0, 0, 0, 0.05)",
                selectedBackColor: "#03a9f3",
                collapseIcon: 'fa fa-minus-square-o',
                nodeIcon: 'fa fa-bookmark',
                showBorder: false,
                data: permissionsData
            });
        });

    </script>
@endpush

