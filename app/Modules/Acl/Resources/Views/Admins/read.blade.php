<div class="modal-header">
    <h4 class="modal-title">{{$admin->name}}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center>
                        <img src="{{$admin->avatar}}" id="holder" class="img-circle w-100" style="height: 150px"/>
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
                                <label class="col-md-12">Username:</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-user-o"></i> {{$admin->name}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <label for="example-email" class="col-md-12">Email:</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-at"></i> {{$admin->email}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-12">Role:</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-anchor"></i> {{$admin->roles->first()->display_name}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-12">Phone No:</label>
                                <div class="col-md-12">
                                    <p><i class="fa fa-phone"></i> {{$admin->phone ? : 'None'}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-12">Gender:</label>
                                <div class="col-md-12">
                                    <p>
                                        @if($admin->gender == 'Other')
                                            <i class="fa fa-transgender"></i> {{$admin->gender}}
                                        @elseif($admin->gender == 'Male')
                                            <i class="fa fa-mars"></i> {{$admin->gender}}
                                        @else
                                            <i class="fa fa-venus"></i> {{$admin->gender}}
                                        @endif
                                    </p>
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
    @if(Creeper::canOrabort('update-admins'))
        <a href="{{route('acl.admins.edit', $admin->id)}}" class="btn btn-success waves-effect">Edit</a>
    @endif
</div>