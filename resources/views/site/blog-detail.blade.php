@php
    $posts_sidebar = Creeper::getList('App\Modules\Posts\Models\Post', 'sidebar_' . $post->slug, 'publish', 5,true);
    $posts_related = Creeper::getListRelated('App\Modules\Posts\Models\Post', $post->slug,'publish', ['model' => 'App\Modules\Posts\Models\Category', 'slug' => $cate], 3);
@endphp

@extends('site.layouts.main')
@section('meta-title', $post->seo_title ? $post->seo_title : Creeper::setting('site-title'))
@section('meta-description', $post->seo_description ? $post->seo_description : Creeper::setting('site-description'))
@section('meta-keywords', $post->seo_keyword ? $post->seo_keyword : Creeper::setting('site-keyword'))
@section('title', $post->title ? $post->title : Creeper::setting('site-title'))
@section('content')
    <section class="section-news">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-9">
                    <h3>{{$post->title}}</h3>
                    <div class="box-meta">
                        <span class="item date"><a
                                    href="#">{{\Carbon\Carbon::parse($post->create_at)->format('D j\\, Y')}}</a></span>
                        <span class="item auth">{{$post->author->name}}</span>
                        @if(isset($post->tags) && count($post->tags) !=0)
                            <span class="item tag">
                                @foreach($post->tags as $tag)
                                    <a href="">{{$tag->name}}</a>,
                                @endforeach
                            </span>
                        @endif
                        <span class="item comment"><a href="#">0 Comment(s)</a></span>
                    </div>
                    <figure>
                        <img src="{{$post->image}}" class="w-100">
                    </figure>
                    <div class="content">
                        {!! $post->content !!}
                    </div>
                    <div class="heading"><span>Related</span></div>
                    <div class="row">
                        @foreach($posts_related as $post)
                            <div class="col-12 col-md-4">
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
                        @endforeach
                    </div>
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