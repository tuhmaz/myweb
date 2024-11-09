@php
    $configData = Helper::appClasses();
    use Detection\MobileDetect;
    $detect = new MobileDetect;
@endphp

@extends('layouts.layoutFront')

@section('page-style')
@vite(['resources/assets/vendor/js/waitdon.js'])
@endsection

@section('title', $file->file_Name)

@section('content')
<section class="section-py first-section-pt help-center-header position-relative overflow-hidden" style="background-color: rgb(32, 44, 69); padding-bottom: 20px;">
  <h2 class="text-center text-white fw-semibold">{{ $file->file_Name }}</h2>
</section>

<div class="container px-4 mt-4">
  <ol class="breadcrumb breadcrumb-style2" aria-label="breadcrumbs">
    <li class="breadcrumb-item">
      <a href="{{ route('home') }}">
        <i class="ti ti-home-check"></i> {{ __('Home') }}
      </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Files') }}</li>
  </ol>
  <div class="progress mt-2">
    <div class="progress-bar" role="progressbar" style="width: 75%;"></div>
  </div>
</div>

<section class="section-py bg-body first-section-pt" style="padding-top: 10px;">
  <div class="container">
    <div class="container mt-4 mb-4">
      @if(config('settings.google_ads_desktop_download') || config('settings.google_ads_mobile_download'))
      <div class="ads-container text-center">
        @if($detect->isMobile())
        {!! config('settings.google_ads_mobile_download') !!}
        @else
        {!! config('settings.google_ads_desktop_download') !!}
        @endif
      </div>
      @endif
    </div>

    <div class="my-4">
      <div class="text-center">
        <h1 class="h4" style="font-size: 1.2rem; color: green;">{{ __('Welcome to the waiting page for downloading files. Don\'t forget to support us by sharing articles on social media.') }}</h1>
      </div>
      <hr>
      <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12 col-sm-12">
          <div class="text-center">
            <h1 class="h4">{{ __('please_wait_download_link') }}</h1>
            <p>{{ __('download_available_in') }} <span id="countdown">24</span> {{ __('seconds') }}.</p>
            <p id="downloadText" style="font-size: 1.5rem; color: red; display: none;">
              <a id="downloadLink" href="{{ route('download.wait', $file->id) }}" class="text-danger fw-bold">{{ __('download_now') }}</a>
            </p>
          </div>

          <div class="progress mx-auto my-4" style="width: 80%; max-width: 400px; height: 10px;">
            <div class="progress-bar bg-success" id="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <p class="text-center" id="collectPointsMessage" style="font-size: 1.2rem; color: green;">
            {{ __('collect_points_to_end_countdown_faster') }}
          </p>

          <div id="gameArea" style="position: relative; width: 300px; height: 300px; background-color: #f3f3f3; margin: 20px auto;">
            <div id="clickableDot" style="position: absolute; width: 20px; height: 20px; background-color: red; border-radius: 50%;"></div>
          </div>
          <p class="text-center">{{ __('Score') }}: <span id="score">0</span></p>
        </div>

        <div class="container mt-4 mb-4">
          @if(config('settings.google_ads_desktop_download_2') || config('settings.google_ads_mobile_download_2'))
          <div class="ads-container text-center">
            @if($detect->isMobile())
            {!! config('settings.google_ads_mobile_download_2') !!}
            @else
            {!! config('settings.google_ads_desktop_download_2') !!}
            @endif
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
