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
                                                                   href="{{route('rss.rss.index')}}"><i
                                    class="fa fa-times"></i></a></h4>

                    <form class="form-horizontal form-material row" action="{{route('rss.rss.update', $item->id)}}"
                          method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="put">
                        <input type="hidden" name="tag_id" value="{{$item->id}}">

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
                                                <label for="rss_link" class="col-md-12">RSS Link</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="rss_link"
                                                           class="form-control form-control-line"
                                                           value="{{$item->rss_link}}" id="name">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="page_target" class="col-md-12">Page Target</label>
                                                <div class="col-md-12">
                                                    <select class="form-control" id="page_target" name="page_target">
                                                        <option value="">None</option>
                                                        @if(count($pages_target) > 0)
                                                            @foreach($pages_target as $key => $page_target)

                                                                <option value="{{$page_target}}" {{ $page_target == $item->page_target ? 'selected':''     }}>{{$page_target}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="page_target" class="col-md-12">Category</label>
                                                <div class="col-md-12">
                                                    <select class="form-control" id="page_target" name="cate_id">
                                                        <option value="" selected disabled="">--Select category--
                                                        </option>
                                                        @foreach($categories as $category)
                                                            @if(count($category->child) == 0)
                                                                <option value="{{$category->id}}"
                                                                        @if($category->id == $item->cate_id) selected @endif>{{$category->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button class="btn btn-success" type="submit">Update</button>

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
@endpush
@push('scripts')
    <script src="Modules/Post/js/jquery.slugify.js"></script>
    <script src="Modules/Post/js/slug.js"></script>

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

    </script>
    <script>
        $("#parent").select2();

        $("input[name=name]").on('keyup change', function () {

            axios.get('{{route('blog.tags.getSlug')}}', {
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

        });
        $('#get_top').on('click', function () {
            var pos = {{App\Modules\Posts\Models\Tag::max('pos')+1}};
            $('input[name="pos"]').val(pos);
        });
    </script>
@endpush
