@extends('main::Layouts.main')
@push('style')
    <link rel="stylesheet" href="/vendor/laravel-filemanager/css/lfm.css">
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit {{$item->title}} <a class="btn btn-danger btn-circle pull-right"
                                                                    data-toggle="tooltip" title="Back"
                                                                    href="{{route('ads.index')}}"><i
                                    class="fa fa-times"></i></a></h4>

                    <form class="form-horizontal form-material row" action="{{route('ads.update', $item->id)}}"
                          method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="put">
                        <!-- Column -->
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <div class="card">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs profile-tab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#settings"
                                                            role="tab">Settings</a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="settings" role="tabpanel">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="link" class="col-md-12">Title</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="title"
                                                           class="form-control form-control-line"
                                                           value="{{old('title', $item->title)}}" id="title">
                                                </div>
                                            </div>

                                            <input type="hidden" id="thumbnail" name="image"
                                                   value="{{old('image', $item->image)}}">
                                            <!-- Column -->
                                            <div class="form-group">
                                                <label for="image" class="col-md-12">Image <a id="lfm"
                                                                                              data-input="thumbnail"
                                                                                              data-preview="holder"
                                                                                              class="btn btn-success btn-rounded w-10">
                                                        Choose image
                                                    </a></label>
                                                <div class="col-md-12">
                                                    <img id="holder" class="mt-2 img-thumbnail"
                                                         src="{{old('image', $item->image)}}"/>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <label for="link" class="col-md-12">Link</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="link"
                                                           class="form-control form-control-line" id="link"
                                                           value="{{old('link', $item->link)}}">
                                                </div>
                                            </div>
                                            @php $positions = config('creeper.position_advert'); @endphp
                                            <div class="form-group">
                                                <label for="position" class="col-md-12">Position</label>
                                                <div class="col-md-12">
                                                    <select class="form-control" id="position" name="position">
                                                        <option value="">None</option>
                                                        @if(count($positions) > 0)
                                                            @foreach($positions as $key => $position)
                                                                <option value="{{$position}}" {{ old('position', $position) == $item->position ? 'selected':'' }}>{{$position}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="publish_date" class="col-md-12">Publish date</label>
                                                <div class="col-md-12">
                                                    <input class="form-control datetimepicker" name="publish_date"
                                                           value="{{old('publish_date', $item->publish_date)}}"
                                                           id="publish_date">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="expiration_date" class="col-md-12">Expiration date</label>
                                                <div class="col-md-12">
                                                    <input class="form-control datetimepicker" name="expiration_date"
                                                           value="{{old('expiration_date', $item->expiration_date)}}"
                                                           id="expiration_date">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="status" class="col-md-12">Status</label>
                                                <div class="col-md-12">
                                                    <select class="form-control" id="status" name="status">
                                                        <option value="draft" {{old('status', $item->status) == 'draft' ? "selected" : ""}}>
                                                            Draft
                                                        </option>
                                                        <option value="pending" {{old('status', $item->status) == 'pending' ? "selected" : ""}}>
                                                            Pending
                                                        </option>
                                                        <option value="publish" {{old('status', $item->status) ? "selected" : ""}}>
                                                            Publish
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button class="btn btn-success" type="submit">Create</button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link href="Modules/Main/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
          rel="stylesheet">
    <link rel="stylesheet" href="/vendor/laravel-filemanager/css/lfm.css">
@endpush
@push('scripts')


    <!-- Sweet alert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.js"></script>
    <script src="Modules/Main/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="/vendor/laravel-filemanager/js/lfm.js"></script>
    <script type="text/javascript">
                @if (!$errors->isEmpty())
        var err = '<ul style="list-style:none; padding: 0">';
        @foreach ($errors->all() as $err)
            err += '<li><h4>{{$err}}</h4></li>';
        @endforeach
            err += '</ul>';
        swal({
            title: "Oops...",
            text: err,
            type: "error",
            html: true
        });
        @endif

    </script>

    <script type="text/javascript">
        $('.datetimepicker').bootstrapMaterialDatePicker({format: 'YYYY-MM-DD HH:mm'});
                @if (!$errors->isEmpty())
        var err = '<ul style="list-style:none; padding: 0">';
        @foreach ($errors->all() as $err)
            err += '<li><h4>{{$err}}</h4></li>';
        @endforeach
            err += '</ul>';
        swal({
            title: "Oops...",
            text: err,
            type: "error",
            html: true
        });
        @endif
        $('#lfm').filemanager('image', {prefix: '/media'});
    </script>
@endpush
