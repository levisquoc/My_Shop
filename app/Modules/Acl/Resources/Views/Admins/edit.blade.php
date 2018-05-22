@extends('main::Layouts.main')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit {{$admin->name}} <a class="btn btn-danger btn-circle pull-right"
                                                                    data-toggle="tooltip" title="Back"
                                                                    href="{{route('acl.admins.index')}}"><i
                                    class="fa fa-times"></i></a></h4>

                    <form class="form-horizontal form-material row" action="{{route('acl.admins.update', $admin->id)}}"
                          method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="put">
                        <input type="hidden" id="thumbnail" name="avatar" value="{{$admin->avatar}}">
                        <!-- Column -->
                        <div class="col-lg-4 col-xlg-3 col-md-5">
                            <div class="card">
                                <div class="card-body">
                                    <center class="m-t-30">
                                        <img src="{{$admin->avatar}}" id="holder" class="img-circle w-50"
                                             style="height: 150px"/>
                                        <a id="lfm" data-input="thumbnail" data-preview="holder"
                                           class="custom_button btn btn-success btn-rounded w-50">
                                            Choose avatar
                                        </a>
                                    </center>
                                </div>
                                <div>
                                    <hr>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="col-md-12">Role*</label>
                                        <div class="col-md-12">
                                            <select class="form-control form-control-line select2" name="role">
                                                <option value="" disabled selected>--Select Role--</option>
                                                @foreach($roles as $role)
                                                    <option value="{{$role->id}}"
                                                            @if($admin->hasRole($role->name)) selected @endif>{{$role->display_name.' ( '.$role->description.' )'}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Phone No</label>
                                        <div class="col-md-12">
                                            <input type="text" name="phone" placeholder="+8492xxxxxx"
                                                   class="form-control form-control-line"
                                                   value="{{old('phone', $admin->phone)}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Gender</label>
                                        <div class="col-md-12">
                                            <input name="gender" value="Male" type="radio" class="with-gap"
                                                   id="gender_male" @if($admin->gender == 'Male') checked @endif>
                                            <label for="gender_male">Male</label>

                                            <input name="gender" value="Other" type="radio" class="with-gap"
                                                   id="gender_orther" @if($admin->gender == 'Other') checked @endif>
                                            <label for="gender_orther">Other</label>

                                            <input name="gender" value="Female" type="radio" class="with-gap"
                                                   id="gender_female" @if($admin->gender == 'Female') checked @endif>
                                            <label for="gender_female">Female</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button class="btn waves-effect waves-light btn-danger" data-toggle="modal"
                                                    data-target="#ganeral_password" type="button">Generate Password
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                        <!-- Column -->
                        <div class="col-lg-8 col-xlg-9 col-md-7">
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
                                                <label class="col-md-12">Username*</label>
                                                <div class="col-md-12">
                                                    <p>{{$admin->name}}</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="example-email" class="col-md-12">Email*</label>
                                                <div class="col-md-12">
                                                    <input type="email" name="email" placeholder="johnathan@admin.com"
                                                           class="form-control form-control-line" name="example-email"
                                                           id="example-email" value="{{old('email', $admin->email)}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12">
                                                    New Password*
                                                </label>
                                                <small class="col-md-12 form-text text-muted">Leave empty to keep the
                                                    same
                                                </small>
                                                <div class="col-md-12">
                                                    <input type="password" name="password"
                                                           class="form-control form-control-line"
                                                           value="{{old('password')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12">Confirm Password*</label>
                                                <div class="col-md-12">
                                                    <input type="password" name="password_confirmation"
                                                           class="form-control form-control-line">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12">Secret Password*</label>
                                                @if(is_null($admin->secret_password))
                                                    <small class="col-md-12 form-text text-muted">Secret password
                                                        necessary for get new password when forgot your password.
                                                    </small>
                                                @else
                                                    <small class="col-md-12 form-text text-muted">Leave empty to keep
                                                        the same
                                                    </small>
                                                @endif
                                                <div class="col-md-12">
                                                    <input type="password" name="secret_password"
                                                           class="form-control form-control-line">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5 class="box-title">Category manage</h5>
                                                <select id='category_manage' name="cate_id[]" multiple='multiple'>
                                                    @foreach($categories as $category)
                                                        <option value="{{$category->id}}"
                                                                @if(in_array($category->id,$admin->categories->pluck('id')->toArray())) selected @endif>{{$category->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button class="btn btn-success" type="submit">Save</button>

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

@section('modals')
    <div class="modal fade" id="ganeral_password" tabindex="-1" role="dialog" aria-labelledby="ganeral_password"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ganeral_password">Generate Password</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row form-horizontal form-material">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="pass_length" class="col-md-12">Password Length:</label>
                                <div class="col-md-12">
                                    <select class="form-control" name="pass_length" id="pass_length">
                                        <optgroup label="Week">
                                            <option value="6">6</option>
                                            <option value="8">8</option>
                                            <option value="10">10</option>
                                        </optgroup>
                                        <optgroup label="Medium">
                                            <option value="12" selected>12</option>
                                            <option value="14">14</option>
                                            <option value="16">16</option>
                                        </optgroup>
                                        <optgroup label="Strong">
                                            <option value="18">18</option>
                                            <option value="20">20</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="checkbox" class="form-control" name="inc_sym" id="inc_sym" checked>
                                    <label for="inc_sym">Include Symbols</label>
                                    <small>( e.g. @#$% )</small>
                                </div>
                                <div class="col-md-12">
                                    <input type="checkbox" class="form-control" name="inc_num" id="inc_num">
                                    <label for="inc_num">Include Numbers</label>
                                    <small>( e.g. 1234 )</small>
                                </div>
                                <div class="col-md-12">
                                    <input type="checkbox" class="form-control" name="inc_low" id="inc_low" checked>
                                    <label for="inc_low">Include Lowercase Characters</label>
                                    <small>( e.g. abcd )</small>
                                </div>
                                <div class="col-md-12">
                                    <input type="checkbox" class="form-control" name="inc_upp" id="inc_upp" checked>
                                    <label for="inc_upp">Include Uppercase Characters</label>
                                    <small>( e.g. ABCD )</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="final_pass" class="col-md-12">Your New Password:</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="final_pass" id="final_pass" readonly>
                                    <div class="alert alert-warning m-t-10">
                                        <h3 class="text-warning">
                                            <i class="fa fa-exclamation-triangle"></i> Warning
                                        </h3>
                                        Please copy this password and save it any place you can remember.
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-info waves-effect text-left" onclick="rand_pass()">Generate
                        Password
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="/vendor/laravel-filemanager/css/lfm.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link href="Modules/Main/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css"/>
@endpush
@push('scripts')
    <script src="Modules/Main/js/validation.js"></script>
    <script src="/vendor/laravel-filemanager/js/lfm.js"></script>
    <script type="text/javascript" src="Modules/Main/plugins/multiselect/js/jquery.multi-select.js"></script>
    <script>
        !function (window, document, $) {
            "use strict";
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
        }(window, document, jQuery);
    </script>
    <!-- Sweet alert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script type="text/javascript">
        $('.select2').select2();
        $('#password').on('change', function () {
            $('#password-confirm').val('');
        });
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

        function rand_pass() {
            var possible = '';
            var length = $('#pass_length').val();
            var text = '';

            if ($('#inc_sym').is(':checked')) {
                possible += '![]{}()%&*$#^<>~@|';
            }
            if ($('#inc_num').is(':checked')) {
                possible += '0123456789';
            }
            if ($('#inc_low').is(':checked')) {
                possible += 'abcdefghijklmnopqrstuvwxyz';
            }
            if ($('#inc_upp').is(':checked')) {
                possible += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            }

            for (var i = 0; i < length; i++) {
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            $('#final_pass').val(text);
        }

        $('#category_manage').multiSelect();
    </script>
@endpush
