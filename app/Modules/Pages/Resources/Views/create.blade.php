@extends('main::Layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Create new page <a class="btn btn-danger btn-circle pull-right"
                                                              data-toggle="tooltip" title="Back"
                                                              href="{{route('pages.index')}}"><i
                                    class="fa fa-times"></i></a></h4>

                    <form class="form-horizontal form-material row" action="{{route('pages.store')}}" method="post">
                        {{csrf_field()}}
                        <input type="hidden" id="thumbnail" name="image">
                        <!-- Column -->
                        <div class="col-lg-4 col-xlg-3 col-md-5">
                            <div class="card">
                                <div class="card-body">
                                    <center class="m-t-30">

                                        <img id="holder" style="width:100%; height: auto"/>

                                    </center>
                                </div>
                                <div style="position: relative !important;">
                                    <a id="lfm" data-input="thumbnail" data-preview="holder"
                                       class="custom_button btn btn-success btn-rounded w-50" style="top: -20%;">
                                        Choose image
                                    </a>
                                    <hr>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="exampleSelect1" class="col-md-12">Status</label>
                                        <div class="col-md-12">
                                            <select class="form-control form-control-line" id="status" name="status"
                                                    selected="selected">
                                                <option value="draft" {{old('status') == 'draft' ? "selected" : ""}}>
                                                    Draft
                                                </option>
                                                <option value="pending" {{old('status') == 'pending' ? "selected" : ""}}>
                                                    Pending
                                                </option>
                                                <option value="publish" {{old('status') == 'publish' ? "selected" : ""}}>
                                                    Publish
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="pos" class="col-md-12">Posion</label>
                                        <div class="col-md-12">
                                            <input type="number" name="pos" class="form-control form-control-line"
                                                   value="{{App\Modules\Pages\Models\Page::max('pos')+1}}" id="pos"
                                                   min="0">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Column -->

                        <!-- Column -->
                        <div class="col-lg-8 col-xlg-9 col-md-7">
                            <div class="tab-content">
                                <div class="card">
                                    <!-- Nav tabs -->
                                    @if(count(config('creeper.language')) > 1)
                                        <ul class="nav nav-tabs profile-tab" role="tablist">
                                            @foreach(config('creeper.language') as $key => $item)
                                                <li class="nav-item"><a class="nav-link {{ $key == 0 ? 'active' : '' }}"
                                                                        data-toggle="tab"
                                                                        href="#{{ $item['code'] }}_language" role="tab"><img
                                                                src="{{ $item['flag'] }}"> {{ $item['name'] }}</a></li>
                                            @endforeach
                                        </ul>
                                @endif
                                <!-- Tab panes -->
                                    <div class="tab-content">
                                        @foreach(config('creeper.language') as $key  => $item)
                                            <div class="tab-pane {{ $key == 0 ? 'active' : '' }}"
                                                 id="{{ $item['code'] }}_language" role="tabpanel">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="name" class="col-md-12">Title</label>
                                                        <div class="col-md-12">
                                                            <input type="text"
                                                                   name="language[{{ $item['code'] }}][name]"
                                                                   class="form-control form-control-line"
                                                                   value="{{old('name')}}"
                                                                   id="name"
                                                                   onchange="getSlug(this);"
                                                                   onkeyup="getSlug(this);"
                                                                   data-url="{{route('pages.getSlug')}}"
                                                                   data-type="true"
                                                                   data-target-slug="language[{{ $item['code'] }}][slug]"
                                                                   data-target-seo-title="language[{{ $item['code'] }}][seo_title]">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="slug" class="col-md-12">Slug</label>
                                                        <div class="col-md-12">
                                                            <input type="text"
                                                                   name="language[{{ $item['code'] }}][slug]"
                                                                   class="form-control form-control-line" id="slug"
                                                                   value="{{old('slug')}}" readonly="readonly">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="desc" class="col-md-12">Description</label>
                                                        <div class="col-md-12">
                                                        <textarea class="form-control form-control-line" id="desc"
                                                                  name="language[{{ $item['code'] }}][description]"
                                                                  rows="3"
                                                                  onkeyup='$("#seo_desc_{{ $item['code'] }}").html($(this).val());'
                                                                  onchange='$("#seo_desc_{{ $item['code'] }}").html($(this).val());'>{{old('desc')}}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="content" class="col-md-12">Content</label>
                                                        <div class="col-md-12">
                                                            <textarea class="form-control form-control-line text-editor"
                                                                      id="content"
                                                                      name="language[{{ $item['code'] }}][content]"
                                                                      rows="3">{{old('content')}}</textarea>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="card">
                                    @if(count(config('creeper.language')) > 1)
                                        <ul class="nav nav-tabs profile-tab" role="tablist">
                                            @foreach(config('creeper.language') as $key  => $item)
                                                <li class="nav-item"><a class="nav-link {{ $key == 0 ? 'active' : '' }}"
                                                                        data-toggle="tab"
                                                                        href="#{{ $item['code'] }}_seo_language"
                                                                        role="tab"><img src="{{ $item['flag'] }}"
                                                                                        alt=""> {{ $item['name'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    <div class="tab-content">
                                        @foreach(config('creeper.language') as $key  => $item)
                                            <div class="tab-pane {{ $key == 0 ? 'active' : '' }}"
                                                 id="{{ $item['code'] }}_seo_language" role="tabpanel">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="seo_title" class="col-md-12">Seo title</label>
                                                        <div class="col-md-12">
                                                            <input type="text"
                                                                   name="language[{{ $item['code'] }}][seo_title]"
                                                                   class="form-control form-control-line" id="seo_title"
                                                                   value="{{old('seo_title')}}">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="seo_keyword" class="col-md-12">Seo keyword</label>
                                                        <div class="col-md-12">
                                                            <textarea class="form-control form-control-line"
                                                                      id="seo_keyword"
                                                                      name="language[{{ $item['code'] }}][seo_keyword]"
                                                                      rows="3">{{old('seo_keyword')}}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="seo_desc" class="col-md-12">Seo description</label>
                                                        <div class="col-md-12">
                                                            <textarea class="form-control form-control-line"
                                                                      id="seo_desc_{{ $item['code'] }}"
                                                                      name="language[{{ $item['code'] }}][seo_description]"
                                                                      rows="3">{{old('seo_desc')}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button class="btn btn-success col-md-12" type="submit">Create</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="/vendor/laravel-filemanager/css/lfm.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
@endpush
@push('scripts')
    <script src="/vendor/laravel-filemanager/js/lfm.js"></script>
    <script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>

    <!-- Sweet alert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <!-- Main js -->
    <script src="Modules/Main/js/main.js"></script>

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
