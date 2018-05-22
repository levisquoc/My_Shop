@extends('site.layouts.main')
@section('meta-title', $page->seo_title ? $page->seo_title : Creeper::setting('site-title'))
@section('meta-description', $page->seo_description ? $page->seo_description :Creeper::setting('site-description'))
@section('meta-keywords', $page->seo_keyword ? $page->seo_keyword : Creeper::setting('site-keyword'))
@section('title', $page->name ? $page->name : Creeper::setting('site-title'))
@section('content')
    <section class="section-news">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </section>
@endsection