@extends('main::Layouts.main')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Create new permissions <a class="btn btn-danger btn-circle pull-right"
                                                                     data-toggle="tooltip" title="Back"
                                                                     href="{{route('acl.permissions.index')}}"><i
                                    class="fa fa-times"></i></a></h4>
                    <!-- Nav tabs -->
                    <form class="form-material" action="{{route('acl.permissions.update',$permission->id)}}"
                          method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group">
                            <input class="form-control form-control-line" type="text" name="display_name"
                                   id="display_name" placeholder="Name (display name)"
                                   value="{{old('display_name',$permission->display_name)}}">
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-line" type="text" name="name" id="name"
                                   placeholder="Slug" value="{{old('name',$permission->name)}}" disabled>
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-line" type="text" name="description"
                                   id="description" placeholder="Describe what this permission does"
                                   value="{{old('description',$permission->description)}}">
                        </div>
                        <button type="submit" class="btn waves-effect waves-light btn-success" name="button">Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
@endpush
@push('scripts')
    <script src="Modules/Main/js/validation.js"></script>
    <script>
        !function (window, document, $) {
            "use strict";
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
        }(window, document, jQuery);
    </script>
    <!-- Sweet alert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

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
@endpush
