<div class="modal-header">
    <h4 class="modal-title">{{$cate->name}}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center>
                        <img src="Modules/Acl/images/useravatar.svg" id="holder" class="img-circle w-100"
                             style="height: 150px"/>
                    </center>
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
                                <label class="col-md-12">Name:</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-check-square-o"></i> {{$cate->name}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-12">Slug:</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-check-square-o"></i> {{$cate->slug}}</p>
                                </div>
                            </div>

                            @if(isset($cate->parent_id))
                                <div class="row">
                                    <label class="col-md-12">Parent:</label>
                                    <div class="col-md-12">
                                        <p><i class="fa fa-check-square-o"></i> {{$cate->parent->name}}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <label class="col-md-12">Status:</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-check-square-o"></i> {{$cate->status}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-12">Position:</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-check-square-o"></i> {{$cate->pos}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-12">Created:</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-check-square-o"></i> {{$cate->created_at}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-12">Updated:</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-check-square-o"></i> {{$cate->updated_at}}</p>
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
    @if(Creeper::canOrabort('update-categories'))
        <a href="{{route('blog.categories.edit', $cate->id)}}" class="btn btn-success waves-effect">Edit</a>
    @endif
</div>