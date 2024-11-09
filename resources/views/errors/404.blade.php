@php
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts.layoutFront')

@section('title', __('Not Found - Pages'))

@section('page-style')
<!-- Page -->
@vite(['resources/assets/vendor/scss/pages/page-misc.scss'])
@endsection

@section('content')
<!-- Not Found -->
<div class="container-xxl container-p-y">
  <div class="misc-wrapper text-center">
    <h1 class="mb-2 mx-2" style="line-height: 6rem; font-size: 6rem;">404</h1>
    <h4 class="mb-2 mx-2">{{ __('Page Not Found!') }} 🔍</h4>
    <p class="mb-6 mx-2">{{ __('Sorry, the page you are looking for doesn’t exist or has been moved.') }}</p>
    <a href="{{ url('/') }}" class="btn btn-primary">{{ __('Back to home') }}</a>
    <div class="mt-12">
      <img src="{{ asset('assets/img/illustrations/page-misc-not-found.svg') }}" alt="{{ __('page-misc-not-found') }}" width="170" class="img-fluid">
    </div>
  </div>
</div>
 
<!-- /Not Found -->
@endsection
