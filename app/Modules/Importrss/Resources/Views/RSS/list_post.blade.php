@extends('main::Layouts.main')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">RSS</h4>
                    <div class="icons">
                        <div class="btn-group">
                            <select class="form-control rounded-0" id="categories">
                                <option value="">--Select Category--</option>
                                @foreach($categories as $category)
                                    @if(count($category->child) == 0)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <a href="javascript:void(0)" class="btn btn-outline-success btn-rounded" id="btndownload"><i
                                        class="fa fa-download"></i> Download</a>
                        </div>
                        <br/>
                        <small>Keep this field null if you want to use default category</small>
                    </div>

                    <div class="table-responsive m-t-40">
                        <table class="display nowrap table table-hover table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="select_all" id="select_all"></th>
                                <th>Image</th>
                                <th>title</th>
                                <th>Excerpt</th>
                                <th>Status</th>
                                <th>Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i = 1; @endphp

                            @foreach($data as $item)
                                @php
                                    $item=json_decode($item);
                                    $CheckExist = App\Modules\Posts\Models\Post::where('title','like',$item->title)->get();

                                    $status = $CheckExist->count()!=0 ? $CheckExist->count() : "0";
                                @endphp

                                <tr>
                                    <td><input type="checkbox" class="check_item" name="id[]" value="{{$i}}"
                                               style="position: inherit; opacity: 1;"></td>
                                    <td><img src="{{$item->image}}" width="130" height="100"/></td>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->description}}</td>
                                    <td>{!! $CheckExist->count()!=0 ? '<span class="badge badge-danger">Exist</span>' : '<span class="badge badge-success">Not Exist</span>' !!}</td>
                                    <td>{{$status}}</td>
                                </tr>
                                @php $i++; @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('style')
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

    <style>
        #select_all {
            position: inherit;
            opacity: 1;
        }

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

        $('#select_all').on('click', function () {
            // Check/uncheck checkboxes for all rows in the table
            $('input[name="id[]"]').prop('checked', this.checked);
        });


        $('#btndownload').click(function () {
            $('.preloader').show();
            var arr = [];
            $("tbody input[type=checkbox]").each(function () {
                if ($(this).is(":checked")) {
                    arr.push($(this).val());
                }
            });

            var url = "{{ route('rss.rss.download') }}",
                link = "{{$link}}",
                category = $('#categories').val();
            $.ajax({
                type: 'POST',
                url: url,
                data: {arr: arr, link: link, category: category, rss_id: {{$rss->id}}, '_token': '{{csrf_token()}}'},
                success: function (response) {
                    console.log(response);
                    if (response) {
                        location.reload();
                    }
                }
            });

        });
    </script>
@endpush
