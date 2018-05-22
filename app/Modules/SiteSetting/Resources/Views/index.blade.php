@extends('main::Layouts.main')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body p-b-0">
                    <h4 class="card-title">Setting</h4>
                </div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs customtab" role="tablist">
                    @foreach($groups as $key => $group)
                        @if($key == 0)
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#{{$group}}" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span> <span
                                            class="hidden-xs-down">{{studly_case($group)}}</span>
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#{{$group}}" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span> <span
                                            class="hidden-xs-down">{{studly_case($group)}}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    @foreach($groups as $key => $group)
                        @if($key == 0)
                            <div class="tab-pane active" id="{{$group}}" role="tabpanel">
                                <div class="p-20">
                                    <form action="{{route('site_setting.settings.update',$group)}}" method="post">
                                        {{csrf_field()}}
                                        <input type="hidden" name="_method" value="put">
                                        @foreach($settings as $setting)
                                            @if($setting->group == $group)
                                                <div class="card">
                                                    <div class="card-header">
                                                        <strong>{{$setting->display_name}}</strong>
                                                        <code>@{!! Creeper::setting('{{$setting->key}}') !!}</code>
                                                        <div class="card-actions">
                                                            <a href="{{route('site_setting.settings.move_up',$setting->id)}}">
                                                                <i class="sort-icons fa fa-angle-up"
                                                                   aria-hidden="true"></i>
                                                            </a>
                                                            <a href="{{route('site_setting.settings.move_down',$setting->id)}}">
                                                                <i class="sort-icons fa fa-angle-down"
                                                                   aria-hidden="true"></i>
                                                            </a>
                                                            <i class="fa fa-trash ml-2"
                                                               data-id="{{ $setting->id }}"
                                                               data-display-key="{{ $setting->key }}"
                                                               data-display-name="{{ $setting->display_name }}"></i>
                                                        </div>
                                                    </div>
                                                    <div class="card-body collapse show">
                                                        <div class="row">
                                                            <div class="form-group col-12 col-md-8">
                                                                @if($setting->type == 'text')
                                                                    <input type="text" name="{{$setting->key}}"
                                                                           class="form-control"
                                                                           value="{{old($setting->key,$setting->value)}}">
                                                                @elseif($setting->type == 'text-area')
                                                                    <textarea class="form-control"
                                                                              name="{{$setting->key}}"
                                                                              id="{{$setting->key}}" cols="30"
                                                                              rows="10">{{old($setting->key,$setting->value)}}</textarea>
                                                                @elseif($setting->type == 'text-editor')
                                                                    <textarea class="text-editor"
                                                                              name="{{$setting->key}}"
                                                                              id="{{$setting->key}}"
                                                                              rows="3">{{old($setting->key,$setting->value)}}</textarea>
                                                                @elseif($setting->type == 'code-editor')
                                                                    <div id="{{ $setting->key }}" class="ace_editor"
                                                                         name="{{ $setting->key }}"
                                                                         style="min-height: 200px;">@if(isset($setting->value)){{ $setting->value }}@endif</div>
                                                                    <textarea name="{{ $setting->key }}"
                                                                              id="{{ $setting->key }}_textarea"
                                                                              style="display: none;">@if(isset($setting->value)){{ $setting->value }}@endif</textarea>
                                                                @elseif($setting->type == 'image')
                                                                    <img id="holder-{{$setting->id}}"
                                                                         src="{{$setting->value}}"
                                                                         style="max-height: 400px"
                                                                         class="img-responsive img-thumbnail"/>
                                                                    <input type="hidden" id="thumbnail-{{$setting->id}}"
                                                                           name="{{$setting->key}}"
                                                                           value="{{old($setting->key,$setting->value)}}">
                                                                    <a data-input="thumbnail-{{$setting->id}}"
                                                                       data-preview="holder-{{$setting->id}}"
                                                                       class="custom_button btn btn-success btn-rounded lfm mt-1"
                                                                       style="color: #FFFFFF">
                                                                        Choose image
                                                                    </a>
                                                                    <a href="{{route('site_setting.settings.delete_value',$setting->id)}}"
                                                                       class="custom_button btn btn-danger btn-rounded mt-1">Delete
                                                                        image</a>
                                                                @elseif($setting->type == 'number')
                                                                    <input type="number" name="{{$setting->key}}"
                                                                           class="form-control"
                                                                           value="{{old($setting->key,$setting->value)}}">
                                                                @endif
                                                            </div>
                                                            <div class="form-group col-12 col-md-4">
                                                                <select class="form-control"
                                                                        name="group-{{$setting->key}}">
                                                                    @foreach($groups as $key => $value)
                                                                        <option value="{{$value}}"
                                                                                @if($setting->group == $value) selected @endif> {{studly_case($value)}} </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        <button class="btn btn-danger">Save</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="tab-pane" id="{{$group}}" role="tabpanel">
                                <div class="p-20">
                                    <form action="{{route('site_setting.settings.update',$group)}}" method="post">
                                        {{csrf_field()}}
                                        <input type="hidden" name="_method" value="put">
                                        @foreach($settings as $setting)
                                            @if($setting->group == $group)
                                                <div class="card">
                                                    <div class="card-header">
                                                        <strong>{{$setting->display_name}}</strong>
                                                        <code>@{!! Creeper::setting('{{$setting->key}}') !!}</code>
                                                        <div class="card-actions">
                                                            <a href="{{route('site_setting.settings.move_up',$setting->id)}}">
                                                                <i class="sort-icons fa fa-angle-up"
                                                                   aria-hidden="true"></i>
                                                            </a>
                                                            <a href="{{route('site_setting.settings.move_down',$setting->id)}}">
                                                                <i class="sort-icons fa fa-angle-down"
                                                                   aria-hidden="true"></i>
                                                            </a>
                                                            <i class="fa fa-trash ml-2"
                                                               data-id="{{ $setting->id }}"
                                                               data-display-key="{{ $setting->key }}"
                                                               data-display-name="{{ $setting->display_name }}"></i>
                                                        </div>
                                                    </div>
                                                    <div class="card-body collapse show">
                                                        <div class="row">
                                                            <div class="form-group col-12 col-md-8">
                                                                @if($setting->type == 'text')
                                                                    <input type="text" name="{{$setting->key}}"
                                                                           class="form-control"
                                                                           value="{{old($setting->key,$setting->value)}}">
                                                                @elseif($setting->type == 'text-area')
                                                                    <textarea name="{{$setting->key}}"
                                                                              id="{{$setting->key}}" cols="30"
                                                                              rows="10">{{old($setting->key,$setting->value)}}</textarea>
                                                                @elseif($setting->type == 'text-editor')
                                                                    <textarea class="text-editor"
                                                                              name="{{$setting->key}}"
                                                                              id="{{$setting->key}}"
                                                                              rows="3">{{old($setting->key,$setting->value)}}</textarea>
                                                                @elseif($setting->type == 'code-editor')
                                                                    <div id="{{ $setting->key }}" class="ace_editor"
                                                                         name="{{ $setting->key }}"
                                                                         style="min-height: 200px;">@if(isset($setting->value)){{ $setting->value }}@endif</div>
                                                                    <textarea name="{{ $setting->key }}"
                                                                              id="{{ $setting->key }}_textarea"
                                                                              style="display: none;">@if(isset($setting->value)){{ $setting->value }}@endif</textarea>
                                                                @elseif($setting->type == 'image')
                                                                    <img id="holder-{{$setting->id}}"
                                                                         src="{{$setting->value}}"
                                                                         style="max-height: 400px"
                                                                         class="img-responsive img-thumbnail"/>
                                                                    <input type="hidden" id="thumbnail-{{$setting->id}}"
                                                                           name="{{$setting->key}}"
                                                                           value="{{old($setting->key,$setting->value)}}">
                                                                    <a data-input="thumbnail-{{$setting->id}}"
                                                                       data-preview="holder-{{$setting->id}}"
                                                                       class="custom_button btn btn-success btn-rounded lfm mt-1"
                                                                       style="color: #FFFFFF">
                                                                        Choose image
                                                                    </a>
                                                                    <a href="{{route('site_setting.settings.delete_value',$setting->id)}}"
                                                                       class="custom_button btn btn-danger btn-rounded mt-1">Delete
                                                                        image</a>
                                                                @elseif($setting->type == 'number')
                                                                    <input type="number" name="{{$setting->key}}"
                                                                           class="form-control"
                                                                           value="{{old($setting->key,$setting->value)}}">
                                                                @endif
                                                            </div>
                                                            <div class="form-group col-12 col-md-4">
                                                                <select class="form-control"
                                                                        name="group-{{$setting->key}}">
                                                                    @foreach($groups as $key => $value)
                                                                        <option value="{{$value}}"
                                                                                @if($setting->group == $value) selected @endif> {{studly_case($value)}} </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        <button class="btn btn-danger">Save</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <!-- Column -->
            <div class="card">
                <div class="card-body collapse show">
                    <h4 class="card-title">Create new setting</h4>

                    <form action="{{route('site_setting.settings.store')}}" method="post"
                          class="form-material m-t-40 row">
                        {{csrf_field()}}
                        <div class="form-group col-md-3 m-t-20">
                            <label for="">Display name</label>
                            <input class="form-control" type="text" name="display_name" value="{{old('display_name')}}">
                        </div>

                        <div class="form-group col-md-3 m-t-20">
                            <label for="">Key</label>
                            <input class="form-control" type="text" name="key" value="{{old('key')}}">
                        </div>

                        <div class="form-group col-md-3 m-t-20">
                            <label for="">Type</label>

                            <select class="form-control" name="type">
                                @foreach($types as $key => $type)
                                    <option value="{{$type}}"> {{studly_case($type)}} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3 m-t-20">
                            <label for="">Group</label>
                            <select class="form-control" name="group">
                                @foreach($groups as $key => $group)
                                    <option value="{{$group}}"> {{studly_case($group)}} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12">
                            <button class="btn btn-success pull-right" type="submit"><i class="fa fa-plus-circle"></i>
                                Add new setting
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div id="delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Confirm delete setting</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <p><b>Are you sure you want to delete the <span id="delete_setting_title"></span> Setting?</b></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <form action="{{ route('site_setting.settings.destroy', ['id' => '__id']) }}" id="delete_form"
                          method="POST">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger waves-effect waves-light">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

    <style>
        .sort-icons {
            font-size: 21px;
            color: #ccc;
            position: relative;
            cursor: pointer;
        }

        .sort-icons:hover {
            color: #37474F;
        }

        .fa-trash {
            cursor: pointer;
        }

        .fa-trash:hover {
            color: #e94542;
        }
    </style>
@endpush
@push('scripts')
    <script src="Modules/Post/js/jquery.slugify.js"></script>
    <script src="Modules/Post/js/slug.js"></script>
    <script src="//cdn.ckeditor.com/4.7.3/full-all/ckeditor.js"></script>
    <script src="/vendor/laravel-filemanager/js/lfm.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.8/ace.js"></script>
    <!-- Sweet alert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script type="text/javascript">
        $('.select2').select2();

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
        $('.lfm').filemanager('image', {prefix: '/media'});

        $("input[name=display_name]").on('keyup change', function () {

            axios.get('{{route('site_setting.settings.getSlug')}}', {
                params: {
                    slug: $(this).val()
                }
            })
                .then(function (responce) {
                    $('input[name=key]').val(responce.data);
                })
                .catch(function (errors) {
                    console.log(errors);
                });

        });

        var ace_editor_element = document.getElementsByClassName("ace_editor");

        // For each ace editor element on the page
        for (var i = 0; i < ace_editor_element.length; i++) {

            // Create an ace editor instance
            var ace_editor = ace.edit(ace_editor_element[i].id);

            console.log(ace_editor_element[i].id);

            // Get the corresponding text area associated with the ace editor
            var ace_editor_textarea = document.getElementById(ace_editor_element[i].id + '_textarea');

            ace_editor.on('change', function (event, el) {
                ace_editor_id = el.container.id;
                ace_editor_textarea = document.getElementById(ace_editor_id + '_textarea');
                ace_editor_instance = ace.edit(ace_editor_id);
                ace_editor_textarea.value = ace_editor_instance.getValue();
            });
        }

        $(document).ready(function () {

            CKEDITOR.replaceAll('text-editor');
            CKEDITOR.config.filebrowserImageBrowseUrl = '/media?type=Images';
            CKEDITOR.config.allowedContent = true;
            CKEDITOR.config.verify_html = false;
            //CKEDITOR.replaceClass = 'text-editor';

            $('.fa-trash').click(function () {
                var display = $(this).data('display-name') + '/' + $(this).data('display-key');

                $('#delete_setting_title').text(display);
                $('#delete_form')[0].action = $('#delete_form')[0].action.replace('__id', $(this).data('id'));
                $('#delete-modal').modal('show');
            });
        });

    </script>
@endpush
