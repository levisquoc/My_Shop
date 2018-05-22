@extends('main::Layouts.main')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Categories</h4>
                    <div class="icons">
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                Action
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void (0);" onclick="location.reload();">Reload
                                    this page</a>
                                @if(Creeper::canOrabort('delete-categories'))
                                    <a class="dropdown-item" href="javascript:void (0);" onclick="deleteSelected();">Delete
                                        selected items</a>
                                @endif
                            </div>
                        </div>
                        @if(Creeper::canOrabort('create-categories'))
                            <a href="{{route('blog.categories.create')}}" class="btn btn-outline-success btn-rounded"><i
                                        class="fa fa-plus"></i> Add new</a>
                        @endif

                        @if(Creeper::canOrabort('delete-categories') && count($trashed) != 0)
                            <button type="button" class="btn btn-rounded btn-outline-warning btn-trash">Trash
                                &nbsp; {{count($trashed)}}</button>
                        @endif


                    </div>
                    <div class="table-responsive m-t-40">
                        <table class="display nowrap table table-index table-hover table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="select_all" id="select_all"></th>
                                <th>Name</th>
                                <th>Parent</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @if(Creeper::canOrabort('delete-categories'))
        <!--  Delete form  -->
        <div id="delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title">Confirm delete permisson</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <p><b>Are you sure to delete <strong id="target-name"></strong> permission ?</b></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        <form id="delete_form" action="{{route('blog.categories.index')}}" method="post">
                            {{ method_field("DELETE") }}
                            {{csrf_field()}}
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Yes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- !Delete form -->
        <!-- View Trash -->
        @if(count($trashed) !=0)
            <div id="trash_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="trash_modal"
                 aria-hidden="true" style="display: none;">

                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><i class="voyager-trash"></i> Trash list</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>

                        </div>
                        <div class="modal-body">
                            <div class="btn-group">
                                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu">
                                    @if(Creeper::canOrabort('delete-categories'))
                                        <a class="dropdown-item" href="javascript:void (0);"
                                           onclick="deleteTrashSelected();">Delete selected items</a>
                                        <a class="dropdown-item" href="javascript:void (0);"
                                           onclick="restoreTrashSelected();">Restore selected items</a>
                                    @endif
                                </div>
                            </div>


                            <table id="tableTrash" class="table table-bordered table-striped mt-2">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" name="select_all" id="select_all"></th>
                                    <th>Name</th>
                                    <th>Parent</th>
                                    <th>Status</th>
                                    <th>Deleted</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($trashed as $data)
                                    <tr>
                                        <td><input type="checkbox" class="check_item" id="{{ $data->id }}"
                                                   value="{{$data->id}}" style="position: inherit; opacity: 1;"
                                                   name="id[]"></td>
                                        <td>{{$data->name}}</td>
                                        <td>{{$data->parent ? $data->parent->name : 'None'}}</td>
                                        <td>{{$data->status}}</td>
                                        <td>{{Carbon\Carbon::parse($data->deleted_at)->diffForHumans()}}</td>
                                        <td>
                                            <a href="{{route('blog.categories.remove',$data->id)}}"
                                               class="btn btn-circle btn-danger remove" data-id="{{ $data->id }}"
                                               data-toggle="tooltip" data-original-title="Delete"> <i
                                                        class="fa fa-close"></i> </a>

                                            <a href="{{route('blog.categories.restore',$data->id)}}"
                                               class="btn btn-circle btn-info" data-toggle="tooltip"
                                               data-original-title="Restore"> <i class="fa fa-history"></i> </a>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        @endif
        <!-- View Trash -->
        <!-- Delete from Trash -->
        <div id="remove_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title">Confirm remove category</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <p><b>Are you sure to remove <strong id="target-name"></strong> category ?</b></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        <form id="remove_form" action="{{route('blog.categories.index')}}" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="_method" value="REMOVE">
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Yes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete from Trash -->
    @endif
    @if(Creeper::canOrabort('read-categories'))
        <!-- View profile -->
        <div id="profile-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="profile-modal"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                </div>
            </div>
        </div>
        <!-- View profile -->
    @endif
@stop

@push('style')
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

    <style>
        #select_all {
            position: inherit;
            opacity: 1;
        }

        @if(!Creeper::canOrabort('delete-categories'))
            .delete {
            display: none;
        }

        @endif
        @if(!Creeper::canOrabort('update-categories'))
            .edit {
            display: none;
        }

        @endif
        @if(!Creeper::canOrabort('read-categories'))
            .read {
            display: none;
        }
        @endif
    </style>
@endpush

@push('scripts')
    <!-- This is data table -->
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script type="text/javascript">
        var table = $('.table-index').DataTable({
            columnDefs: [{
                targets: 0,
                searchable: false,
                orderable: false
            }],
            order: [],
            dom: 'lBfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            processing: true,
            serverSide: true,
            ajax: "{{route('blog.categories.serverside.getcategories')}}",
            columns: [
                {data: 'check', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'parent.name', name: 'parent_id', defaultContent: "<strong>None</strong>"},
                {data: 'status', name: 'status'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'Action', orderable: false, searchable: false}
            ]
        });

        $('#select_all').on('click', function () {
            // Get all rows with search applied
            var rows = table.rows({'search': 'applied'}).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[name="id[]"]', rows).prop('checked', this.checked);
        });

                @if(Creeper::canOrabort('delete-categories'))
        var removeFormAction;

        function deleteEl(e) {
            var formRemove = $('#delete_form')[0];
            //console.log(formRemove);
            var target = $(e).data('target');
            var target_name = $(e).data('target-name');
            if (!removeFormAction) { // Save form action initial value
                removeFormAction = formRemove.action;
            }

            formRemove.action = removeFormAction.match(/\/[0-9]+$/)
                ? removeFormAction.replace(/([0-9]+$)/, target)
                : removeFormAction + target;

            $('#target-name').html(target_name);
            $('#delete-modal').modal('show');
        }

        function deleteSelected() {
            var data = [];
            $(".check_item:checked").each(function () {
                data.push(this.value);
            });
            if (data.length === 0) {
                swal({
                    title: "Oops...",
                    text: "Please select categories for use this action",
                    type: "error"
                });
            }
            else {
                $('.preloader').show();
                axios.get('{{route('blog.categories.deleteSelected')}}', {
                    params: {
                        data: data
                    }
                })
                    .then(function (response) {
                        console.log(response);
                        if (response.status == 200) {
                            $('.preloader').hide();
                            location.reload();
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        };
        @if(isset($trashed))
        $('.btn-trash').on('click', function () {
            $('#trash_modal').modal('show');
        });
                @endif

        var trash_table = $('#tableTrash').DataTable({
                columnDefs: [{
                    targets: 0,
                    searchable: false,
                    orderable: false
                }],
                order: []
            });

        $('#tableTrash #select_all').on('click', function () {
            // Get all rows with search applied
            var rows = trash_table.rows({'search': 'applied'}).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[name="id[]"]', rows).prop('checked', this.checked);
        });

        function deleteTrashSelected() {
            var data = [];
            $("#tableTrash .check_item:checked").each(function () {
                data.push(this.value);
            });
            if (data.length === 0) {
                swal({
                    title: "Oops...",
                    text: "Please select categories for use this action",
                    type: "error"
                });
            }
            else {
                $('.preloader').show();
                axios.get('{{route('blog.categories.deleteTrashSelected')}}', {
                    params: {
                        data: data
                    }
                })
                    .then(function (response) {
                        console.log(response);
                        if (response.status == 200) {
                            location.reload();
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        };

        function restoreTrashSelected() {
            var data = [];
            $("#tableTrash .check_item:checked").each(function () {
                data.push(this.value);
            });
            if (data.length === 0) {
                swal({
                    title: "Oops...",
                    text: "Please select categories for use this action",
                    type: "error"
                });
            }
            else {
                $('.preloader').show();
                axios.get('{{route('blog.categories.restoreTrashSelected')}}', {
                    params: {
                        data: data
                    }
                })
                    .then(function (response) {
                        console.log(response);
                        if (response.status == 200) {
                            location.reload();
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        };

        @endif
        @if(Creeper::canOrabort('read-categories'))
        function read(e) {
            var id = $(e).data('target');

            axios.get(id)
                .then(function (response) {
                    $('#profile-modal .modal-content').html(response.data);
                    $('#profile-modal').modal('show');
                })
                .catch(function (error) {
                    console.log(error);
                })
        };
        @endif
    </script>
@endpush
