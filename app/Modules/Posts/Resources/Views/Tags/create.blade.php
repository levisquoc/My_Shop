@extends('main::Layouts.main')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Create new tag <a class="btn btn-danger btn-circle pull-right"
                                                             data-toggle="tooltip" title="Back"
                                                             href="{{route('blog.tags.index')}}"><i
                                    class="fa fa-times"></i></a></h4>

                    <form class="form-horizontal form-material row" action="{{route('blog.tags.store')}}" method="post">
                    {{csrf_field()}}

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
                                                <label for="name" class="col-md-12">Name</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="name"
                                                           class="form-control form-control-line"
                                                           value="{{old('name')}}" id="name">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="slug" class="col-md-12">Slug</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="slug"
                                                           class="form-control form-control-line" id="slug"
                                                           value="{{old('slug')}}" readonly="readonly">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-md-12">Position</label>
                                                <div class="col-md-12">
                                                    <input type="number" name="pos"
                                                           class="form-control form-control-line"
                                                           value="{{App\Modules\Posts\Models\Tag::max('pos')+1}}"
                                                           id="pos" min="0" placeholder="Thứ tự hiển thị">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="exampleSelect1" class="col-md-12">Status</label>
                                                <div class="col-md-12">
                                                    <select class="form-control" id="status" name="status">
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
        $("input[name=name]").on('keyup change', function () {

            axios.get('{{route('blog.tags.getSlug')}}', {
                params: {
                    slug: $(this).val()
                }
            })
                .then(function (responce) {
                    $('input[name=slug]').val(responce.data);
                })
                .catch(function (errors) {
                    console.log(errors);
                });

        });
    </script>
@endpush
