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
                    <h4 class="card-title">Edit {{$item->name}} <a class="btn btn-danger btn-circle pull-right"
                                                                   data-toggle="tooltip" title="Back"
                                                                   href="{{route('blog.posts.index')}}"><i
                                    class="fa fa-times"></i></a></h4>

                    <form class="form-horizontal form-material row" action="{{route('blog.posts.update', $item->id)}}"
                          method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="put">
                        @if(!empty($item->image))
                            <input type="hidden" id="thumbnail" name="image" value="{{$item->image}}">
                        @else
                            <input type="hidden" id="thumbnail" name="image">
                    @endif
                    <!-- Column -->
                        <div class="col-lg-4 col-xlg-3 col-md-5">
                            <div class="card">
                                <div class="card-body">
                                    <center class="m-t-30">
                                        @if(!empty($item->image))
                                            <img src="{{$item->image}}" id="holder" style="width:100%; height: auto"/>
                                        @else
                                            <img id="holder" style="width:100%; height: auto"/>
                                        @endif
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
                                            <select class="form-control form-control-line" id="status" name="status">
                                                <option value="draft" @if($item->status == 'draft') selected @endif>
                                                    Draft
                                                </option>
                                                <option value="pending" @if($item->status == 'pending') selected @endif>
                                                    Pending
                                                </option>
                                                @if(Creeper::canOrabort('can-publish') || $item->status == 'publish')
                                                    <option value="publish" @if($item->status == 'publish') selected
                                                            @elseif($item->status == 'publish' && !Creeper::canOrabort('can-publish')) selected
                                                            disabled @endif>Publish
                                                    </option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="name" class="col-md-12">Position (<a href="javascript:void(0);"
                                                                                         id="get_top"
                                                                                         class="italic"><em>Priority</em></a>)</label>
                                        <div class="col-md-12">
                                            <input type="number" name="pos" class="form-control form-control-line"
                                                   value="{{$item->pos}}" id="pos" min="0">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card">
                                <ul class="nav nav-tabs profile-tab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#settings"
                                                            role="tab">CATEGORIES</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="settings" role="tabpanel">
                                        <div class="card-body">

                                            <div class="form-group">
                                                <label for="exampleSelect1" class="col-md-12">Categories</label>
                                                <div class="col-md-12">
                                                    <select class="form-control form-control-line" id="category"
                                                            name="category">
                                                        <option value="">Uncategorized</option>
                                                        @if(count($categories) > 0)
                                                            @foreach($categories as $category)
                                                                <option value="{{$category->id}}" {{ $item->category_id == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <ul class="nav nav-tabs profile-tab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#settings"
                                                            role="tab">TAGS</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="settings" role="tabpanel">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <select name="tags[]" id="tagss" class="form-control"
                                                        multiple="multiple" style="width: 100%;">
                                                    @foreach($tags as $tag)
                                                        <option value="{{$tag->id}}"
                                                                @if($item->tags->where('id',$tag->id)->isNotEmpty()) selected @endif>{{$tag->name}}</option>
                                                    @endforeach
                                                </select>


                                            </div>

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
                                                <label for="name" class="col-md-12">Title</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="title"
                                                           class="form-control form-control-line"
                                                           value="{{$item->title}}" id="title">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="slug" class="col-md-12">Slug</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="slug"
                                                           class="form-control form-control-line" id="slug"
                                                           value="{{$item->slug}}" readonly="readonly">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="desc" class="col-md-12">Description</label>
                                                <div class="col-md-12">
                                                    <textarea class="form-control form-control-line" id="desc"
                                                              name="desc" rows="3">{{$item->description}}</textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="content" class="col-md-12">Content</label>
                                                <div class="col-md-12">
                                                    <textarea class="form-control form-control-line" id="content"
                                                              name="content" rows="3">{{$item->content}}</textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs profile-tab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#settings"
                                                            role="tab">SEO</a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="settings" role="tabpanel">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="seo_title" class="col-md-12">Seo title</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="seo_title"
                                                           class="form-control form-control-line" id="seo_title"
                                                           value="{{$item->seo_title}}">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="seo_keyword" class="col-md-12">Seo keyword</label>
                                                <div class="col-md-12">
                                                    <textarea class="form-control form-control-line" id="seo_keyword"
                                                              name="seo_keyword"
                                                              rows="3">{{$item->seo_keyword}}</textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="seo_desc" class="col-md-12">Seo description</label>
                                                <div class="col-md-12">
                                                    <textarea class="form-control form-control-line" id="seo_desc"
                                                              name="seo_desc"
                                                              rows="3">{{$item->seo_description}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button class="btn btn-success col-md-12" type="submit">Update</button>
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
    <style>
        .item-content-block {
            padding: 20px;
            border-top: 2px solid #f6f6f2;
            background-color: #FFF;
            display: block;
        }

        .tags a {
            background-color: #756f5d;
            padding: 10px;
            color: #fff;
            display: inline-block;
            font-size: 11px;
            text-transform: uppercase;
            line-height: 11px;
            border-radius: 2px;
            margin-bottom: 5px;
            margin-right: 2px;
            text-decoration: none;
        }

        .tags a:hover {
            background-color: #a38018;
        }
    </style>
@endpush
@push('scripts')
    <script src="Modules/Post/js/jquery.slugify.js"></script>
    <script src="Modules/Post/js/slug.js"></script>
    <script src="/vendor/laravel-filemanager/js/lfm.js"></script>
    <script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>

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
        $('#lfm').filemanager('image', {prefix: '/media'});

    </script>
    <script>
        $("#category").select2();

        $("input[name=title]").on('keyup change', function () {
            axios.get('{{route('blog.categories.getSlug')}}', {
                params: {
                    slug: $(this).val(),
                    type: true
                }
            })
                .then(function (responce) {
                    $('input[name=slug]').val(responce.data);
                })
                .catch(function (errors) {
                    console.log(errors);
                });

            $('input[name=seo_title]').val($(this).val());

        });
        $("#desc").on('keyup change', function () {
            $("#seo_desc").html($(this).val());
        });

    </script>
    <script>
        var options = {
            filebrowserImageBrowseUrl: '/media?type=Images'
        };
        $(document).ready(function () {
            CKEDITOR.replace('content', options);
        });
    </script>

    <script>
        $("#tagss").select2({
            tags: true,
            tokenSeparators: [',']
        });

        $("a[id^=tag]").one('click', function (e) {
            var itemID = $(this).attr("id");
            var item = $(this).text();
            e.preventDefault();

            $('#tagss').append('<option value="' + item + '">' + item + '</option>');
            $('#tagss').select2('val', item, true);

        });

        $('#get_top').on('click', function () {
            var pos = {{App\Modules\Posts\Models\Post::max('pos')+1}};
            $('input[name="pos"]').val(pos);
        });
    </script>
@endpush
