@extends('layouts.layoutFront')

@section('title', __('all_keywords'))



@section('content')
<section class="section-py first-section-pt help-center-header position-relative overflow-hidden" style="background-color: rgb(32, 44, 69); padding-bottom: 20px;">
  <h1 class="text-center text-white fw-semibold">{{ __('all_keywords') }}</h1>
  <p class="text-center text-white px-4 mb-0">{{ __('explore_keywords') }}</p>
</section>

<div class="container px-4 mt-4">
  <ol class="breadcrumb breadcrumb-style2" aria-label="breadcrumbs">
    <li class="breadcrumb-item">
      <a href="{{ route('home') }}">
        <i class="ti ti-home-check"></i>{{ __('home') }}
      </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('keywords') }}</li>
  </ol>
  <div class="progress mt-2">
    <div class="progress-bar" role="progressbar" style="width: 100%;"></div>
  </div>
</div>

<section class="section-py bg-body first-section-pt" style="padding-top: 10px;">
  <div class="container">
    <div class="card px-3 mt-6 shadow-sm">
      <div class="row">
        <div class="content-header text-center bg-primary py-3">
          <h2 class="text-white">{{ __('article_keywords') }}</h2>
        </div>
        <div class="content-body text-center mt-3">
          @if($articleKeywords->count())
          <div class="keywords-cloud">
            @foreach($articleKeywords as $keyword)
            <a href="{{ route('keywords.indexByKeyword', ['database' => $database ?? session('database', 'jo'), 'keywords' => $keyword->keyword]) }}" class="keyword-item btn btn-outline-secondary m-1">
              {{ $keyword->keyword }}
            </a>
            @endforeach
          </div>
          @else
          <p>{{ __('no_article_keywords') }}</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-py bg-body first-section-pt" style="padding-top: 10px;">
  <div class="container">
    <div class="card px-3 mt-6 shadow-sm">
      <div class="row">
        <div class="content-header text-center bg-primary py-3">
          <h2 class="text-white">{{ __('news_keywords') }}</h2>
        </div>
        <div class="content-body text-center mt-3">
          @if($newsKeywords->count())
          <div class="keywords-cloud">
            @foreach($newsKeywords as $keyword)
            <a href="{{ route('keywords.indexByKeyword', ['database' => $database ?? session('database', 'jo'), 'keywords' => $keyword->keyword]) }}" class="keyword-item btn btn-outline-secondary m-1">
              {{ $keyword->keyword }}
            </a>
            @endforeach
          </div>
          @else
          <p>{{ __('no_news_keywords') }}</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
