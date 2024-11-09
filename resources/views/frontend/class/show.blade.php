@php
$configData = Helper::appClasses();
use Detection\MobileDetect;

// Create MobileDetect object
$detect = new MobileDetect;

// Array of available colors
$colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];
$colorCount = count($colors);
@endphp

@extends('layouts/layoutFront')

@section('content')
<section class="section-py first-section-pt help-center-header position-relative overflow-hidden" style="background-color: rgb(32, 44, 69); padding-bottom: 20px;">
  <h4 class="text-center text-white fw-semibold">{{ $class->grade_name }}</h4>
</section>

<div class="container px-4 mt-4">
  <ol class="breadcrumb breadcrumb-style2" aria-label="breadcrumbs">
    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="ti ti-home-check"></i>{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('class.index', ['database' => $database ?? session('database', 'default_database')]) }}">{{ __('Classes') }}</a>

    <li class="breadcrumb-item active" aria-current="page">{{ $class->grade_name }}</li>
  </ol>
</div>

<div class="progress mt-2">
  <div class="progress-bar" role="progressbar" style="width: 50%;"></div>
</div>



<section class="section-py bg-body first-section-pt" style="padding-top: 10px;">
<div class="container mt-4 mb-4">
  @if(config('settings.google_ads_desktop_classes') || config('settings.google_ads_mobile_classes'))
  <div class="ads-container text-center">
    @if($detect->isMobile())
    {!! config('settings.google_ads_mobile_classes') !!}
    @else
    {!! config('settings.google_ads_desktop_classes') !!}
    @endif
  </div>
  @endif
</div>
  <div class="container">
    <div class="card px-3 mt-6">
      <div class="row text-center">
        @foreach ($class->subjects as $index => $subject)
        @php
        $color = $colors[$index % $colorCount];
        @endphp
        <div class="col-md-4 mb-3">
          <a href="{{ route('frontend.subjects.show', ['database' => session('database', 'jo'), 'id' => $class->id,'subject' => $subject->id]) }}" class="btn btn-outline-{{ $color }} btn-lg w-100 mt-6">
            {{ $subject->subject_name }}
          </a>
        </div>


        @endforeach
      </div>
    </div>

    <div class="container mt-4 mb-4">
      @if(config('settings.google_ads_desktop_classes_2') || config('settings.google_ads_mobile_classes_2'))
      <div class="ads-container text-center">
        @if($detect->isMobile())
        {!! config('settings.google_ads_mobile_classes_2') !!}
        @else
        {!! config('settings.google_ads_desktop_classes_2') !!}
        @endif
      </div>
      @endif
    </div>

    <div class="content-footer text-center py-4 bg-light mt-4">
      <div class="social-icons">
        <a href="{{ config('settings.facebook') }}" class="me-2"><i class="ti ti-brand-facebook"></i></a>
        <a href="{{ config('settings.twitter') }}" class="me-2"><i class="ti ti-brand-twitter"></i></a>
        <a href="{{ config('settings.tiktok') }}" class="me-2"><i class="ti ti-brand-tiktok"></i></a>
        <a href="{{ config('settings.linkedin') }}" class="me-2"><i class="ti ti-brand-linkedin"></i></a>
        <a href="{{ config('settings.whatsapp') }}"><i class="ti ti-brand-whatsapp"></i></a>
      </div>
    </div>
  </div>
</section>

@endsection
