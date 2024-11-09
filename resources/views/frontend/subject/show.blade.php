@php
$configData = Helper::appClasses();
use Detection\MobileDetect;

$detect = new MobileDetect;

@endphp

@extends('layouts/layoutFront')

@section('title', $subject->subject_name)

@section('content')

<section class="section-py first-section-pt help-center-header position-relative overflow-hidden" style="background-color: rgb(32, 44, 69); padding-bottom: 20px;">
  <img class="banner-bg-img z-n1" src="{{asset('assets/img/pages/header-'.$configData['style'].'.png')}}" alt="Help center header" data-app-light-img="pages/header-edu.png" data-app-dark-img="pages/header-edu.png">
  <h4 class="text-center text-white fw-semibold">{{ $subject->subject_name }}</h4>
</section>

<div class="container px-4 mt-4">
  <ol class="breadcrumb breadcrumb-style2" aria-label="breadcrumbs">
    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="ti ti-home-check"></i>{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('class.index', ['database' => $database ?? session('database', 'default_database')]) }}">{{ __('Classes') }}</a>

    <li class="breadcrumb-item">
      <a href="{{ route('frontend.class.show', ['database' => $database ?? session('database'),'id' => $subject->schoolClass->id]) }}">
        {{ $subject->schoolClass->grade_name }}
      </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ $subject->subject_name }}</li>
  </ol>
  <div class="progress mt-2">
    <div class="progress-bar" role="progressbar" style="width: 50%;"></div>
  </div>
</div>

</div>
<div class="container mt-4 mb-4">
  @if(config('settings.google_ads_desktop_subject') || config('settings.google_ads_mobile_subject'))
  <div class="ads-container text-center">
    @if($detect->isMobile())
    {!! config('settings.google_ads_mobile_subject') !!}
    @else
    {!! config('settings.google_ads_desktop_subject') !!}
    @endif
  </div>
  @endif
  <section class="section-py bg-body first-section-pt" style="padding-top: 10px;">
    <div class="container">

      <div class="my-4">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="p-4 pb-0 pe-lg-0 pt-lg-4 align-items-center rounded-3 border shadow-lg">
              <div class="text-center p-3 p-lg-4 pt-lg-3">
                <h5 class=" fw-bold lh-3 text-body-emphasis">{{ __('Semester One') }} - {{ $subject->subject_name }}</h5>
                <div class="d-grid gap-2 d-md-flex justify-content-center mb-4 mb-lg-3 mt-6">
                  @foreach($semesters->where('semester_name', __('Semester One')) as $semester)
                  <a href="{{ route('frontend.subject.articles', ['database' => $database ?? session('database'),'subject' => $subject->id, 'semester' => $semester->id, 'category' => 'plans']) }}" class="btn btn-outline-secondary btn-lg px-12 mb-3">{{ __('Study Plans') }}</a>
                  <a href="{{ route('frontend.subject.articles', ['database' => $database ?? session('database'),'subject' => $subject->id,'semester' => $semester->id, 'category' => 'papers']) }}" class="btn btn-outline-success btn-lg px-12 mb-3">{{ __('Worksheets') }}</a>
                  <a href="{{ route('frontend.subject.articles', ['database' => $database ?? session('database'),'subject' => $subject->id,'semester' => $semester->id, 'category' => 'tests']) }}" class="btn btn-outline-danger btn-lg px-12 mb-3">{{ __('Tests') }}</a>
                  <a href="{{ route('frontend.subject.articles', ['database' => $database ?? session('database'),'subject' => $subject->id,'semester' => $semester->id, 'category' => 'books']) }}" class="btn btn-outline-warning btn-lg px-12 mb-3">{{ __('School Books') }}</a>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="my-12">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="p-4 pb-0 pe-lg-0 pt-lg-4 align-items-center rounded-3 border shadow-lg">
              <div class="text-center p-3 p-lg-4 pt-lg-3">
                <h5 class=" fw-bold lh-3 text-body-emphasis">{{ __('Semester Two') }} - {{$subject->subject_name }}</h5>
                <div class="d-grid gap-2 d-md-flex justify-content-center mb-4 mb-lg-3 mt-6">
                  @foreach($semesters->where('semester_name', __('Semester Two')) as $semester)
                  <a href="{{ route('frontend.subject.articles', ['database' => $database ?? session('database'),'subject' => $subject->id,'semester' => $semester->id, 'category' => 'plans']) }}" class="btn btn-outline-secondary btn-lg px-12 mb-3">{{ __('Study Plans') }}</a>
                  <a href="{{ route('frontend.subject.articles', ['database' => $database ?? session('database'),'subject' => $subject->id,'semester' => $semester->id, 'category' => 'papers']) }}" class="btn btn-outline-success btn-lg px-12 mb-3">{{ __('Worksheets') }}</a>
                  <a href="{{ route('frontend.subject.articles', ['database' => $database ?? session('database'),'subject' => $subject->id,'semester' => $semester->id, 'category' => 'tests']) }}" class="btn btn-outline-danger btn-lg px-12 mb-3">{{ __('Tests') }}</a>
                  <a href="{{ route('frontend.subject.articles', ['database' => $database ?? session('database'),'subject' => $subject->id,'semester' => $semester->id, 'category' => 'books']) }}" class="btn btn-outline-warning btn-lg px-12 mb-3">{{ __('School Books') }}</a>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="content-footer text-center py-4 bg-light">
        <div class="social-icons">
          <a href="{{ config('settings.facebook') }}" class="me-2"><i class="ti ti-brand-facebook"></i></a>
          <a href="{{ config('settings.twitter') }}" class="me-2"><i class="ti ti-brand-twitter"></i></a>
          <a href="{{ config('settings.tiktok') }}" class="me-2"><i class="ti ti-brand-tiktok"></i></a>
          <a href="{{ config('settings.linkedin') }}" class="me-2"><i class="ti ti-brand-linkedin"></i></a>
          <a href="{{ config('settings.whatsapp') }}"><i class="ti ti-brand-whatsapp"></i></a>
        </div>
      </div>
      <div class="container mt-4 mb-4">
        @if(config('settings.google_ads_desktop_subject_2') || config('settings.google_ads_mobile_subject_2'))
        <div class="ads-container text-center">
          @if($detect->isMobile())
          {!! config('settings.google_ads_mobile_subject_2') !!}
          @else
          {!! config('settings.google_ads_desktop_subject_2') !!}
          @endif
        </div>
        @endif
      </div>
    </div>
  </section>

  @endsection
