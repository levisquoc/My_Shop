<div class="modal-header">
    <h4 class="modal-title">{{$post->name}}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center>

                        <img src="{{$post->image}}" id="holder" class="w-100" style="height: 150px"/>

                    </center>
                </div>

                <div class="card-body">
                    @if(isset($post->category->name))
                        <div class="row">
                            <label class="col-md-12">Category :</label>
                            <div class="col-md-12">
                                <p><i class="fa fa-user-o"></i> {{$post->category->name}}</p>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <label class="col-md-12">Author :</label>
                        <div class="col-md-12">
                            <p><i class="fa fa-user-o"></i> {{$post->author->name}}</p>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-12">Status:</label>
                        <div class="col-md-12">
                            <p><i class="fa fa-user-o"></i> {{$post->sstatus}}</p>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-12">Postion:</label>
                        <div class="col-md-12">
                            <p><i class="fa fa-user-o"></i> {{$post->pos}}</p>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-12">Created :</label>
                        <div class="col-md-12">
                            <p><i class="fa fa-user-o"></i> {{$post->created_at}}</p>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-12">Updated :</label>
                        <div class="col-md-12">
                            <p><i class="fa fa-user-o"></i> {{$post->updated_at}}</p>
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
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#settings" role="tab">Settings</a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="settings" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <label class="col-md-12">Title:</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-user-o"></i> {{$post->title}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-12">Slug:</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-user-o"></i> {{$post->slug}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-12">Description:</label>
                                <div class="col-md-12">
                                    <p>{{$post->description}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-12">Content:</label>
                                <div class="col-md-12">
                                    {!!$post->content!!}
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-12">SEO Title:</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-user-o"></i> {{$post->seo_title}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-12">SEO Keyword :</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-user-o"></i> {{$post->seo_keyword}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-12">SEO Description :</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-user-o"></i> {{$post->seo_description}}</p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
    @if(Creeper::canOrabort('update-posts'))
        <a href="{{route('blog.posts.edit', $post->id)}}" class="btn btn-success waves-effect">Edit</a>
    @endif
</div>