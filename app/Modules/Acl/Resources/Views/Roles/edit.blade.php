@extends('main::Layouts.main')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit role: {{$role->display_name}} <a
                                class="btn btn-danger btn-circle pull-right" data-toggle="tooltip" title="Back"
                                href="{{route('acl.roles.index')}}"><i class="fa fa-times"></i></a></h4>

                    <div class="p-20">
                        <form class="form-material" action="{{route('acl.roles.update', $role->id)}}" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="_method" value="PUT">
                            <div class="form-group">
                                <label for="display_name">Display name</label>
                                <input class="form-control form-control-line" type="text" name="display_name"
                                       id="display_name" placeholder="Name (display name)"
                                       value="{{old('display_name', $role->display_name)}}">
                            </div>
                            <div class="form-group">
                                <label for="display_name">Slug</label>
                                <input class="form-control form-control-line" type="text" name="name" id="name"
                                       placeholder="Slug" value="{{old('name', $role->name)}}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="display_name">Description</label>
                                <input class="form-control form-control-line" type="text" name="description"
                                       id="description" placeholder="Describe what this permission does"
                                       value="{{old('description', $role->description)}}">
                            </div>
                            @if(isset($permissions) &&$role->name != 'superadministrator')
                                <div class="form-group">
                                    <h5><strong>Permissions</strong></h5>
                                    <a href="#" class="permission-select-all">Select All</a> / <a href="#"
                                                                                                  class="permission-deselect-all">Deselect
                                        All</a>
                                    <ul class="list-group permissions p-t-10">
                                        @foreach($permissions as $resource => $permission)
                                            <li class="list-group-item">
                                                <input type="checkbox" id="{{$resource ? : 'basic'}}"
                                                       class="permission-group">
                                                <label for="{{$resource ? : 'basic'}}"><strong>{{ucwords($resource? : 'basic')}}</strong></label>
                                                <ul class="list-group">
                                                    @foreach($permission as $perm)
                                                        <li class="list-group-item">
                                                            <input type="checkbox" id="permission-{{$perm->id}}"
                                                                   name="permissions[]" class="the-permission"
                                                                   value="{{$perm->id}}"
                                                                   @if(in_array($perm->id, $role->permissions->pluck('id')->toArray())) checked @endif>
                                                            <label for="permission-{{$perm->id}}">{{$perm->display_name}}
                                                                <small>( {{$perm->description}} )</small>
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <button type="submit" class="btn waves-effect waves-light btn-success" name="button">Save
                            </button>
                        </form>
                    </div>

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
    <script>
        $('document').ready(function () {


            $('.permission-group').on('change', function () {
                $(this).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
            });

            $('.permission-select-all').on('click', function () {
                $('ul.permissions').find("input[type='checkbox']").prop('checked', true);
                return false;
            });

            $('.permission-deselect-all').on('click', function () {
                $('ul.permissions').find("input[type='checkbox']").prop('checked', false);
                return false;
            });

            function parentChecked() {
                $('.permission-group').each(function () {
                    var allChecked = true;
                    $(this).siblings('ul').find("input[type='checkbox']").each(function () {
                        if (!this.checked) allChecked = false;
                    });
                    $(this).prop('checked', allChecked);
                });
            }

            parentChecked();

            $('.the-permission').on('change', function () {
                parentChecked();
            });
        });
    </script>
@endpush
