@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Detection\MobileDetect;

$detect = new MobileDetect;
@endphp

@extends('layouts/layoutFront')

@section('content')
<section class="section-py first-section-pt help-center-header position-relative overflow-hidden" style="background-color: rgb(32, 44, 69); padding-bottom: 20px;">
  <h4 class="text-center text-white fw-semibold">{{ __('Filter Files') }}</h4>
  <p class="text-center text-white px-4 mb-0">{{ __('Find the files you need below') }}</p>
</section>

<div class="container px-4 mt-4">
  <ol class="breadcrumb breadcrumb-style2" aria-label="breadcrumbs">
    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="ti ti-home-check"></i>{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Filter Files') }}</li>
  </ol>
  <div class="container mt-4 mb-4">
    @if(config('settings.google_ads_desktop_article_2') || config('settings.google_ads_mobile_article_2'))
    <div class="ads-container text-center">
      @if($detect->isMobile())
      {!! config('settings.google_ads_mobile_article_2') !!}
      @else
      {!! config('settings.google_ads_desktop_article_2') !!}
      @endif
    </div>
    @endif
  </div>
</div>

<div class="container mt-4">
  @if($files->isEmpty())
  <p>{{ __('No files found.') }}</p>
  @else
  <div class="card px-3 mt-6">
    <div class="row">
      <div class="content-header text-white text-center bg-primary py-4">
        <h3 class="text-white">{{ __('Filtered Files') }}</h3>
      </div>

      <div class="content-body text-center">
        <table class="table table-bordered mt-3">
          <thead>
            <tr>

              <th class="text-black">{{ __('Category') }}</th>
              <th class="text-black">{{ __('Article Title') }}</th>


            </tr>
          </thead>
          <tbody>
            @foreach($files as $file)
            <tr>
              <td>{{ ucfirst($file->file_category) }}</td>
              <td>
                <a href="{{ route('frontend.articles.show', ['database' => $database ?? session('database', 'jo'), 'article' => $file->article->id]) }}">
                  {{ $file->article->title ?? __('No title') }}
                </a>
              </td>


            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endif
</div>

<div class="container mt-4 mb-4">
  @if(config('settings.google_ads_desktop_article') || config('settings.google_ads_mobile_article'))
  <div class="ads-container text-center">
    @if($detect->isMobile())
    {!! config('settings.google_ads_mobile_article') !!}
    @else
    {!! config('settings.google_ads_desktop_article') !!}
    @endif
  </div>
  @endif
</div>

@endsection
