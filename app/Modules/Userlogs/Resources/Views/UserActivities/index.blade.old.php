@extends('main::Layouts.main')
@section('content')
<div class="row">
    <div class="col-12">
        <!-- Column -->
        <div class="card">
            <div class="card-body p-b-0">
                <h4 class="card-title">Setting</h4>
            </div>
            <ul class="nav nav-tabs customtab" role="tablist">

                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#all" role="tab">
                        <span class="hidden-sm-up"><i class="ti-home"></i></span> <span
                                class="hidden-xs-down">All</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#users" role="tab">
                        <span class="hidden-sm-up"><i class="ti-home"></i></span> <span
                                class="hidden-xs-down">Users</span>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="all" role="tabpanel">
                    <div class="table-responsive m-t-40">
                        <table class="display nowrap table table-index table-hover table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="select_all" id="select_all"></th>
                                <th>UserID</th>
                                <th>UserName</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Phone</th>

                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="users" role="tabpanel">
                    <div class="table-responsive m-t-40">
                        asds
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div id="read_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="trash_modal" aria-hidden="true"
     style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>

</div>

@endsection


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
        ajax: "{{route('blog.logs.serverside.getlogs')}}",
        columns: [
            {data: 'check', orderable: false, searchable: false},
            {data: 'user_id', name: 'user_id'},
            {data: 'user_ip', name: 'user_ip',},
            {data: 'action', name: 'action',},
            {data: 'object', name: 'object',},
            {data: 'value_before', name: 'value_before',},
            {data: 'value_after', name: 'value_after',},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'Action', orderable: false, searchable: false}
        ]
    });


    function read(e) {
        var id = $(e).data('target');

        axios.get(id)
            .then(function (response) {

                $('#read_modal .modal-content').html(response.data);
                $('#read_modal').modal('show');
            })
            .catch(function (error) {
                console.log(error);
            })
    };

</script>
@endpush