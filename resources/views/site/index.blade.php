@extends('site.layouts.main')
@section('meta-title', Creeper::setting('site-title'))
@section('meta-description', Creeper::setting('site-description'))
@section('meta-keywords', Creeper::setting('site-keyword'))
@section('title', Creeper::setting('site-title'))
@section('content')
    <section class="section-hot-news">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-7">
                    <div id="carouselHotNews" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($posts_slide as $key => $post)
                                <div class="carousel-item {{$key == 0 ? 'active': ''}}">
                                    <img class="d-block w-100" src="{{$post->image}}" alt="{{$post->title}}">
                                    <article class="box-detail">
                                        <h5 class="text-truncate"><a
                                                    href="{{route('blogDetail',['cate'=> $post->category->slug, 'slug'=> $post->slug])}}">{{$post->title}}</a>
                                        </h5>
                                        <div class="box-meta top">
                                            <span class="item date"><a
                                                        href="#">{{\Carbon\Carbon::parse($post->create_at)->format('D j\\, Y')}}</a></span>
                                            <span class="item auth"><a href="#">{{$post->author->name}}</a></span>
                                            <span class="item comment"><a href="#">0</a></span>
                                        </div>
                                    </article>
                                </div>
                                @if($key == 4)
                                    @break
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5 pl-lg-0">
                    <article class="h-100">
                        @foreach($posts_slide as $key => $post)
                            @if($key >=4)
                                @if($key % 2 == 0)
                                    <ul class="list-news list-unstyled d-flex">
                                        @endif
                                        <li class="item">
                                            <img src="{{$post->image}}" alt="{{$post->title}}">
                                            <article class="box-detail">
                                                <h5 class="text-truncate"><a
                                                            href="{{route('blogDetail',['cate'=> $post->category->slug, 'slug'=> $post->slug])}}">{{$post->title}}</a>
                                                </h5>
                                                <div class="box-meta top">
                                                    <span class="item date"><a
                                                                href="#">{{\Carbon\Carbon::parse($post->create_at)->format('D j\\, Y')}}</a></span>
                                                    <span class="item comment"><a href="#">0</a></span>
                                                </div>
                                            </article>
                                        </li>
                                        @if($key % 2 != 0)
                                    </ul>
                                @endif
                            @endif
                        @endforeach
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="section-news">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-9">
                    @foreach($categories as $category)
                        @php
                            $posts = $category->posts()->where('status', 'publish')->take(5)->get();
                        @endphp
                        @if(isset($posts) && count($posts) !=0)
                            <div class="heading"><span>{{$category->name}}</span></div>

                            <div class="row">
                                @foreach($posts as $key => $post)
                                    @if($key == 0)
                                        <div class="col-12 col-md-6">
                                            <article class="item-news verticle">
                                                <div class="image">
                                                    <figure>
                                                        <a href="{{route('blogDetail',['cate'=> $post->category->slug, 'slug'=> $post->slug])}}">
                                                            <img src="{{$post->image}}" alt="{{$post->title}}">
                                                        </a>
                                                    </figure>
                                                </div>
                                                <div class="content">
                                                    <h5 class="title"><a
                                                                href="{{route('blogDetail',['cate'=> $post->category->slug, 'slug'=> $post->slug])}}">{{$post->title}}</a>
                                                    </h5>
                                                    <div class="box-meta top">
                                                        <span class="item date"><a
                                                                    href="#">{{\Carbon\Carbon::parse($post->create_at)->format('D j\\, Y')}}</a></span>
                                                        <span class="item auth">{{$post->author->name}}</span>
                                                    </div>
                                                    <p class="desc">
                                                        {{$post->description}}
                                                    </p>
                                                    <div class="box-meta bottom">
                                                        <span class="item comment"><a href="#">0 Comment(s)</a></span>
                                                        @if(isset($post->tags) && count($post->tags) !=0)
                                                            <span class="item tag">
                                                                @foreach($post->tags as $tag)
                                                                    <a href="">{{$tag->name}}</a>,
                                                                @endforeach
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </article>
                                        </div>
                                    @else
                                        @if($key == 1)
                                            <div class="col-12 col-md-6">
                                                @endif
                                                <article class="item-news horizontal d-flex">
                                                    <div class="image">
                                                        <figure>
                                                            <a href="{{route('blogDetail',['cate'=> $post->category->slug, 'slug'=> $post->slug])}}">
                                                                <img src="{{$post->image}}" alt="{{$post->title}}">
                                                            </a>
                                                        </figure>
                                                    </div>
                                                    <div class="content">
                                                        <h6 class="title"><a
                                                                    href="{{route('blogDetail',['cate'=> $post->category->slug, 'slug'=> $post->slug])}}">{{$post->title}}</a>
                                                        </h6>
                                                        <div class="box-meta top">
                                                            <span class="item date"><a
                                                                        href="#">{{\Carbon\Carbon::parse($post->create_at)->format('D j\\, Y')}}</a></span>
                                                            <span class="item auth">{{$post->author->name}}</span>
                                                        </div>
                                                        <div class="box-meta bottom">
                                                            <span class="item comment"><a
                                                                        href="#">0 Comment(s)</a></span>
                                                            @if(isset($post->tags) && count($post->tags) !=0)
                                                                <span class="item tag">
                                                                @foreach($post->tags as $tag)
                                                                        <a href="">{{$tag->name}}</a>,
                                                                    @endforeach
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </article>
                                                @if($key == 4)
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>

                        @endif

                    @endforeach
                </div>
                <div class="col-12 col-lg-3">
                    <div class="heading"><span>News</span></div>
                    @foreach($posts_sidebar as $key => $post)
                        @if($key == 0)
                            <article class="item-news verticle">
                                <div class="image">
                                    <figure>
                                        <a href="{{route('blogDetail',['cate'=> $post->category->slug, 'slug'=> $post->slug])}}">
                                            <img src="{{$post->image}}" alt="{{$post->title}}">
                                        </a>
                                    </figure>
                                </div>
                                <div class="content">
                                    <h5 class="title"><a
                                                href="{{route('blogDetail',['cate'=> $post->category->slug, 'slug'=> $post->slug])}}">{{$post->title}}</a>
                                    </h5>
                                    <div class="box-meta top">
                                        <span class="item date"><a
                                                    href="#">{{\Carbon\Carbon::parse($post->create_at)->format('D j\\, Y')}}</a></span>
                                        <span class="item auth">{{$post->author->name}}</span>
                                    </div>
                                    <p class="desc">
                                        {{$post->description}}
                                    </p>
                                    <div class="box-meta bottom">
                                        <span class="item comment"><a href="#">0 Comment(s)</a></span>
                                        @if(isset($post->tags) && count($post->tags) !=0)
                                            <span class="item tag">
                                                            @foreach($post->tags as $tag)
                                                    <a href="">{{$tag->name}}</a>,
                                                @endforeach
                                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @else
                            <article class="item-news horizontal d-flex">
                                <div class="image">
                                    <figure>
                                        <a href="{{route('blogDetail',['cate'=> $post->category->slug, 'slug'=> $post->slug])}}">
                                            <img src="{{$post->image}}" alt="{{$post->title}}">
                                        </a>
                                    </figure>
                                </div>
                                <div class="content">
                                    <h6 class="title"><a
                                                href="{{route('blogDetail',['cate'=> $post->category->slug, 'slug'=> $post->slug])}}">{{$post->title}}</a>
                                    </h6>
                                    <div class="box-meta top">
                                        <span class="item date"><a
                                                    href="#">{{\Carbon\Carbon::parse($post->create_at)->format('D j\\, Y')}}</a></span>
                                        <span class="item auth">{{$post->author->name}}</span>
                                    </div>
                                    <div class="box-meta bottom">
                                        <span class="item comment"><a href="#">0 Comment(s)</a></span>
                                        @if(isset($post->tags) && count($post->tags) != 0)
                                            <span class="item tag">
                                                @foreach($post->tags as $tag)
                                                    <a href="">{{$tag->name}}</a>,
                                                @endforeach
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endif
                    @endforeach
                    <div class="heading"><span>Links</span></div>
                    <article class="links">
                        <p class="item"><a href="#">Lorem ipsum dolor</a></p>
                        <p class="item"><a href="#">Lorem ipsum dolor</a></p>
                        <p class="item"><a href="#">Lorem ipsum dolor</a></p>
                        <p class="item"><a href="#">Lorem ipsum dolor</a></p>
                        <p class="item"><a href="#">Lorem ipsum dolor</a></p>
                        <p class="item"><a href="#">Lorem ipsum dolor</a></p>
                    </article>
                </div>
            </div>
        </div>
    </section>
@endsection
