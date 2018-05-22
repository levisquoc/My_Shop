@php
    $latest_posts = Creeper::getList('App\Modules\Posts\Models\Post', 'publish', 4, false);
@endphp

<header id="site-header">
    <section class="top-bar">
        <div class="container">
            <ul class="list-unstyled d-flex flex-wrap">
                <li>{{\Carbon\Carbon::now()->format('l\\, F jS\\, Y ')}}</li>
                <li class="d-flex">
                    <span class="font-weight-bold mr-1">Latest:</span>
                    <ul class="list-unstyled newsticker">
                        @foreach($latest_posts as $post)
                            <li>
                                <a href="{{route('blogDetail',['cate'=> $post->category->slug, 'slug'=> $post->slug])}}">{{$post->title}}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>
    </section>

    <section class="logo">
        <div class="container">
            <a href="">
                <img src="{!! Creeper::setting('site-logo') !!}" width="250">
            </a>
        </div>
    </section>
</header>
<!-- Nav -->
<nav id="site-nav" class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav w-100">
                @include('site.layouts.nav', ['items' => Creeper::menu('main-menu'), 'innerLoop' => false])
                {{--@foreach(Creeper::menu('main-menu') as $item)--}}
                {{--<li class="nav-item {{$item->class}}">--}}
                {{--<a class="nav-link" href="@if(str_contains($item->link, ['page','post','product'])) {{route('navigation', Creeper::getLink($item->link))}} @else {{$item->link}} @endif">{!! $item->label !!}</a>--}}
                {{--</li>--}}
                {{--@endforeach--}}
                <li class="nav-item ml-auto">
                    <a class="nav-link"><i class="fa fa-search open-search-bar" aria-hidden="true"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa fa-random" aria-hidden="true"></i></a>
                </li>
            </ul>
        </div>

        <div class="search-bar">
            <form class="search-form" action="">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for..." aria-label="Search for...">
                    <span class="input-group-btn">
				        <button class="btn btn-secondary" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
				      </span>
                </div>
            </form>
        </div>
    </div>
</nav>