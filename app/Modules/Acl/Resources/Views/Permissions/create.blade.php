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
                    <div class="vtabs customvtab w-100">
                        <ul class="nav nav-tabs tabs-vertical" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#basic"
                                                    role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Basic Permission</span> </a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#crud" role="tab"><span
                                            class="hidden-sm-up"><i class="ti-user"></i></span> <span
                                            class="hidden-xs-down">CRUD Permission</span></a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="basic" role="tabpanel">
                                <div class="p-20">
                                    <form class="form-material"
                                          action="{{route('acl.permissions.store',['permission_type' => 'basic'])}}"
                                          method="post">
                                        {{csrf_field()}}
                                        <div class="form-group">
                                            <input class="form-control form-control-line" type="text"
                                                   name="display_name" id="display_name"
                                                   placeholder="Name (display name)" value="{{old('display_name')}}">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control form-control-line" type="text" name="name"
                                                   id="name" placeholder="Slug" value="{{old('name')}}">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control form-control-line" type="text" name="description"
                                                   id="description" placeholder="Describe what this permission does"
                                                   value="{{old('description')}}">
                                        </div>
                                        <button type="submit" class="btn waves-effect waves-light btn-success"
                                                name="button">Submit
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane  p-20" id="crud" role="tabpanel">
                                <div class="p-20">
                                    <form class="form-material"
                                          action="{{route('acl.permissions.store',['permission_type' => 'crud'])}}"
                                          method="post">
                                        {{csrf_field()}}
                                        <div class="form-group">
                                            <div class="controls">
                                                <input class="form-control form-control-line" type="text"
                                                       name="resource"
                                                       id="resource"
                                                       v-model.trim="resource"
                                                       required
                                                       data-validation-required-message="This field is required"
                                                       minlength="3"
                                                       placeholder="Resource" value="{{old('resource')}}">
                                            </div>
                                        </div>
                                        <div class="form-group row" v-if="resource.length >= 3">
                                            <div class="col-12 col-sm-2">
                                                <div class="form-group mb-0">
                                                    <input type="checkbox" id="browse" v-model="crudSelected"
                                                           value="browse" checked/>
                                                    <label for="browse">Browse</label>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <input type="checkbox" id="create" v-model="crudSelected"
                                                           value="create" checked/>
                                                    <label for="create">Create</label>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <input type="checkbox" id="read" v-model="crudSelected" value="read"
                                                           checked/>
                                                    <label for="read">Read</label>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <input type="checkbox" id="update" v-model="crudSelected"
                                                           value="update" checked/>
                                                    <label for="update">Update</label>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <input type="checkbox" id="delete" v-model="crudSelected"
                                                           value="delete" checked/>
                                                    <label for="delete">Delete</label>
                                                </div>
                                            </div>
                                            <input type="hidden" name="crud_selected" :value="crudSelected">
                                            <div class="col-12 col-sm-10">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                        <th>Name</th>
                                                        <th>Slug</th>
                                                        <th>Description</th>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="item in crudSelected">
                                                            <td v-text="crudName(item)"></td>
                                                            <td v-text="crudSlug(item)"></td>
                                                            <td v-text="crudDescription(item)"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn waves-effect waves-light btn-success"
                                                name="button">Submit
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
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
        var app = new Vue({
            el: '#crud',
            data: {
                permissionType: 'basic',
                resource: '',
                crudSelected: ['browse', 'create', 'read', 'update', 'delete']
            },
            methods: {
                crudName: function (item) {
                    return item.substr(0, 1).toUpperCase() + item.substr(1) + " " + app.resource.substr(0, 1).toUpperCase() + app.resource.substr(1);
                },
                crudSlug: function (item) {
                    return item.toLowerCase() + "-" + app.resource.toLowerCase();
                },
                crudDescription: function (item) {
                    return "Allow a User to " + item.toUpperCase() + " a " + app.resource.substr(0, 1).toUpperCase() + app.resource.substr(1);
                }
            }
        });
    </script>
@endpush
