@extends('main::Layouts.main')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Admins</h4>
                    <div class="icons">
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                Action
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void (0);" onclick="location.reload();">Reload
                                    this page</a>
                                @if(Creeper::canOrabort('delete-admins'))
                                    <a class="dropdown-item" href="javascript:void (0);" onclick="deleteSelected();">Delete
                                        selected items</a>
                                @endif

                            </div>
                        </div>
                        @if(Creeper::canOrabort('create-admins'))
                            <a href="{{route('acl.admins.create')}}" class="btn btn-outline-success btn-rounded"><i
                                        class="fa fa-plus"></i> Add new</a>
                        @endif
                    </div>
                    <div class="table-responsive m-t-40">
                        <table class="display nowrap table table-hover table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="select_all" id="select_all"></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
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
    @if(Creeper::canOrabort('delete-admins'))
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
                        <form id="delete_form" action="{{route('acl.admins.index')}}" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Yes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- !Delete form -->
    @endif
    @if(Creeper::canOrabort('read-admins'))
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

        @if(!Creeper::canOrabort('delete-admins'))
            .delete {
            display: none;
        }

        @endif
        @if(!Creeper::canOrabort('update-admins'))
            .edit {
            display: none;
        }

        @endif
        @if(!Creeper::canOrabort('read-admins'))
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
        var table = $('.table').DataTable({
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
            ajax: "{{route('acl.admins.serverside.getadmins')}}",
            "columns": [
                {data: 'check', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'gender', name: 'gender'},
                {data: 'action', name: 'Action', orderable: false, searchable: false}
            ]
        });

        $('#select_all').on('click', function () {
            // Get all rows with search applied
            var rows = table.rows({'search': 'applied'}).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[name="id[]"]', rows).prop('checked', this.checked);
        });
                @if(Creeper::canOrabort('delete-admins'))
        var removeFormAction;

        function deleteEl(e) {
            var formRemove = $('#delete_form')[0];
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
                    text: "Please select permissions for use this action",
                    type: "error"
                });
            }
            else {
                $('.preloader').show();
                axios.get('{{route('acl.admins.deleteSelected')}}', {
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
        @if(Creeper::canOrabort('read-admins'))
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
