{{-- resources/views/frontend/keyword/keyword.blade.php --}}
@extends('layouts/layoutFront')

@php
$configData = Helper::appClasses();
$pageTitle = __('content_related_to', ['keyword' => $keyword->keyword]);
use Illuminate\Support\Str;

@endphp

@section('title', $pageTitle)

@section('page-style')
@vite('resources/assets/vendor/scss/pages/front-page-help-center.scss')
@endsection

@section('meta')
<meta name="keywords" content="{{ $keyword->keyword }}">

<meta name="description" content="{{ __('find_articles_news_related_to', ['keyword' => $keyword->keyword]) }}">

<link rel="canonical" href="{{ url()->current() }}">

<meta property="og:title" content="{{ __('content_related_to', ['keyword' => $keyword->keyword]) }}" />
<meta property="og:description" content="{{ __('find_articles_news_related_to', ['keyword' => $keyword->keyword]) }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:image" content="{{ $articles->first()->image_url ?? asset('assets/img/front-pages/icons/articles_default_image.jpg') }}" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ __('content_related_to', ['keyword' => $keyword->keyword]) }}" />
<meta name="twitter:description" content="{{ __('find_articles_news_related_to', ['keyword' => $keyword->keyword]) }}" />
<meta name="twitter:image" content="{{ $articles->first()->image_url ?? asset('assets/img/front-pages/icons/articles_default_image.jpg') }}" />
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

<section class="section-py first-section-pt help-center-header position-relative overflow-hidden" style="background-color: rgb(32, 44, 69); padding-bottom: 20px;">
  <h1 class="text-center text-white fw-semibold">{{ __('content_related_to') }} {{ $keyword->keyword }}</h1>
</section>

<div class="container px-4 mt-4">
  <ol class="breadcrumb breadcrumb-style2" aria-label="breadcrumbs">
    <li class="breadcrumb-item">
      <a href="{{ route('home') }}">
        <i class="ti ti-home-check"></i>{{ __('home') }}
      </a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('frontend.keywords.index', ['database' => $database ?? session('database', 'jo')]) }}">{{ __('keywords') }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ $keyword->keyword }}</li>
  </ol>
</div>

<div class="container mt-4">
  @if($articles->isEmpty() && $news->isEmpty())
    <p class="text-center">{{ __('no_content_for_keyword') }}</p>
  @else
  <h3>{{ __('articles') }}</h3>
  <div class="row">
    @foreach($articles as $article)
    <div class="col-md-4 mb-4">
      <div class="card h-100 d-flex flex-column">
        <img src="{{ $article->image_url ?? asset('assets/img/front-pages/icons/articles_default_image.jpg') }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">{{ $article->title }}</h5>
          <p class="card-text">{{ Str::limit(strip_tags($article->content), 100) }}</p>
          <div class="mt-auto">
            <a href="{{ route('frontend.articles.show', ['database' => $database ?? session('database', 'jo'), 'article' => $article->id]) }}" class="btn btn-primary">{{ __('read_more') }}</a>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  <h3>{{ __('news') }}</h3>
  <div class="row">
    @foreach($news as $newsItem)
    <div class="col-md-4 mb-4">
      <div class="card h-100 d-flex flex-column">
        @php
          $imagePath = $newsItem->image ? asset('storage/images/' . $newsItem->image) : asset('assets/img/pages/news-default.jpg');
        @endphp

        <img src="{{ $imagePath }}" class="card-img-top" alt="{{ $newsItem->title }}" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">{{ $newsItem->title }}</h5>
          <p class="card-text">{{ Str::limit(strip_tags($newsItem->description), 100) }}</p>
          <div class="mt-auto">
            <a href="{{ route('frontend.news.show', ['database' => $database ?? session('database', 'jo'), 'id' => $newsItem->id]) }}" class="btn btn-primary">{{ __('read_more') }}</a>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif
</div>
@endsection
