<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->
        <div class="user-profile" style="background: url(Modules/Main/images/background/user-info.jpg) no-repeat;">
            <!-- User profile image -->
            <div class="profile-img"><img src="{{Auth::guard('admin')->user()->avatar}}" alt="user"/></div>
            <!-- User profile text-->
            <div class="profile-text">
                <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true"
                   aria-expanded="true">
                    {{Auth::guard('admin')->user()->name}}
                </a>
                <div class="dropdown-menu animated flipInY">
                    <a href="#" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{route('admin.logout')}}" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
                </div>
            </div>
        </div>
        <!-- End User profile text-->
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap">PERSONAL</li>
                <li>
                    <a class="waves-effect waves-dark" href="{{url('admin')}}"><i class="mdi mdi-gauge"></i><span
                                class="hide-menu">Dashboard</span> </a>
                </li>
                @if(Creeper::canOrabort('browse-admins') || Creeper::canOrabort('browse-roles') || Creeper::canOrabort('browse-permissions'))
                    <li @if(Request::is('admin/admins/*', 'admin/roles/*', 'admin/permissions/*')) class="active" @endif>
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="icon-people"></i><span
                                    class="hide-menu">Manage Admins</span></a>
                        <ul aria-expanded="false" class="collapse">
                            @if(Creeper::canOrabort('browse-admins'))
                                <li @if(Request::is('admin/admins/*')) class="active" @endif>
                                    <a class="waves-effect waves-dark @if(Request::is('admin/admins/*')) active @endif"
                                       href="{{route('acl.admins.index')}}"><i class="icon-user"></i> Admins </a>
                                </li>
                            @endif
                            @if(Creeper::canOrabort('browse-roles'))
                                <li @if(Request::is('admin/roles/*')) class="active" @endif>
                                    <a class="waves-effect waves-dark @if(Request::is('admin/roles/*')) active @endif"
                                       href="{{route('acl.roles.index')}}"><i class="icon-energy"></i> Role </a>
                                </li>
                            @endif
                            @if(Creeper::canOrabort('browse-permissions'))
                                <li @if(Request::is('admin/permissions/*')) class="active" @endif>
                                    <a class="waves-effect waves-dark @if(Request::is('admin/permissions/*')) active @endif"
                                       href="{{route('acl.permissions.index')}}"><i class="icon-user-following"></i>
                                        Permissons</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(Creeper::canOrabort('browse-posts') || Creeper::canOrabort('browse-tags') || Creeper::canOrabort('browse-categories') || Creeper::canOrabort('browse-rss'))
                    <li @if(Request::is('admin/posts/*', 'admin/tags/*', 'admin/categories/*', 'admin/rss/*')) class="active" @endif>
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="icon-notebook"></i><span
                                    class="hide-menu">Manage Posts</span></a>
                        <ul aria-expanded="false" class="collapse">
                            @if(Creeper::canOrabort('browse-categories'))
                                <li @if(Request::is('admin/categories/*')) class="active" @endif>
                                    <a class="waves-effect waves-dark @if(Request::is('admin/categories/*')) active @endif"
                                       href="{{route('blog.categories.index')}}"><i
                                                class="mdi mdi-format-list-bulleted"></i> Catalog </a>
                                </li>
                            @endif
                            @if(Creeper::canOrabort('browse-posts'))
                                <li @if(Request::is('admin/posts/*')) class="active" @endif>
                                    <a class="waves-effect waves-dark @if(Request::is('admin/posts/*')) active @endif"
                                       href="{{route('blog.posts.index')}}"><i class="mdi mdi-note-text"></i> Posts </a>
                                </li>
                            @endif
                            @if(Creeper::canOrabort('browse-tags'))
                                <li @if(Request::is('admin/tags/*')) class="active" @endif>
                                    <a class="waves-effect waves-dark @if(Request::is('admin/tags/*')) active @endif"
                                       href="{{route('blog.tags.index')}}"><i class="mdi mdi-tag-multiple"></i> Tags
                                    </a>
                                </li>
                            @endif
                            @if(Creeper::canOrabort('browse-rss'))
                                <li @if(Request::is('admin/rss/*')) class="active" @endif>
                                    <a class="waves-effect waves-dark @if(Request::is('admin/rss/*')) active @endif"
                                       href="{{route('rss.rss.index')}}"><i class="mdi mdi-rss-box"
                                                                            style="color: #ff6600;"></i> RSS </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(Creeper::canOrabort('browse-pages'))
                    <li>
                        <a class="waves-effect waves-dark @if(Request::is('admin/pages/*')) active @endif"
                           href="{{ route('pages.index') }}"><i class="fa fa-file-text-o" aria-hidden="true"></i><span
                                    class="hide-menu">Manage Page</span></a>
                    </li>
                @endif

                @if(Creeper::canOrabort('browse-userlogs'))
                    <li @if(Request::is('admin/logs/*')) class="active" @endif>
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="icon-notebook"></i><span
                                    class="hide-menu">User Logs</span></a>
                        <ul aria-expanded="false" class="collapse">
                            @if(Creeper::canOrabort('browse-userlogs'))
                                <li @if(Request::is('admin/logs/*')) class="active" @endif>
                                    <a class="waves-effect waves-dark @if(Request::is('admin/logs/*')) active @endif"
                                       href="{{route('blog.logs.index')}}"><i class="mdi mdi-format-list-bulleted"></i>
                                        Logs </a>
                                </li>
                            @endif

                        </ul>
                    </li>

                @endif

                @if(Creeper::canOrabort('browse-galleries'))
                    <li>
                        <a class="waves-effect waves-dark @if(Request::is('admin/galleries/*')) active @endif"
                           href="{{ route('galleries.index') }}"><i class="fa fa-photo" aria-hidden="true"></i><span
                                    class="hide-menu">Galleries</span></a>
                    </li>
                @endif

                @if(Creeper::canOrabort('browse-widgets'))
                    <li>
                        <a class="waves-effect waves-dark @if(Request::is('admin/widgets/*')) active @endif"
                           href="{{ route('widgets.index') }}"><i class="fa fa-code" aria-hidden="true"></i><span
                                    class="hide-menu">Widgets</span></a>
                    </li>
                @endif

                @if(Creeper::canOrabort('browse-contacts'))
                    <li>
                        <a class="waves-effect waves-dark @if(Request::is('admin/contacts/*')) active @endif"
                           href="{{ route('contacts.index') }}"><i class="fa fa-envelope-o" aria-hidden="true"></i><span
                                    class="hide-menu">Contact</span></a>
                    </li>
                @endif


                @if(Creeper::canOrabort('access-settings'))

                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="icon-wrench"></i><span
                                    class="hide-menu">Setting</span></a>
                        <ul aria-expanded="false" class="collapse">
                            @if(Creeper::canOrabort('access-settings'))
                                <li>
                                    <a class="waves-effect waves-dark"
                                       href="{{route('site_setting.settings.index')}}"><i class=" icon-settings"></i>
                                        Site setting</a>
                                </li>
                            @endif
                            @if(Creeper::canOrabort('manage-menu'))
                                <li>
                                    <a class="waves-effect waves-dark" href="{{ route('menus.index') }}"><i
                                                class="fa fa-bars" aria-hidden="true"></i> Manage Menu</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
    <!-- Bottom points-->
    <div class="sidebar-footer">
    @if(Creeper::canOrabort('access-settings'))
        <!-- item-->
            <a href="{{route('site_setting.settings.index')}}" class="link" data-toggle="tooltip" title="Settings"><i
                        class="ti-settings"></i></a>
    @endif

    <!-- item-->
        <a href="{{route('admin.logout')}}" class="link" data-toggle="tooltip" title="Logout"><i
                    class="mdi mdi-power"></i></a>
    </div>
    <!-- End Bottom points-->
</aside>
