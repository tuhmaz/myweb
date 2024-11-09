@php
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Not Authorized - Pages'))

@section('page-style')
<!-- Page -->
@vite(['resources/assets/vendor/scss/pages/page-misc.scss'])
@endsection

@section('content')
<!-- Not Authorized -->
<div class="container-xxl container-p-y">
  <div class="misc-wrapper text-center">
    <h1 class="mb-2 mx-2" style="line-height: 6rem; font-size: 6rem;">403</h1>
    <h4 class="mb-2 mx-2">{{ __('You are not authorized!') }} 🔐</h4>
    <p class="mb-6 mx-2">{{ __('You don’t have permission to access this page. Go Home!') }}</p>
    <a href="{{ url('/') }}" class="btn btn-primary">{{ __('Back to home') }}</a>
    <div class="mt-12">
      <img src="{{ asset('assets/img/illustrations/page-misc-you-are-not-authorized.png') }}" alt="{{ __('page-misc-not-authorized') }}" width="170" class="img-fluid">
    </div>
  </div>
</div>
<div class="container-fluid misc-bg-wrapper">
  <img src="{{ asset('assets/img/illustrations/bg-shape-image-' . $configData['style'] . '.png') }}" height="355" alt="{{ __('page-misc-not-authorized') }}" data-app-light-img="illustrations/bg-shape-image-light.png" data-app-dark-img="illustrations/bg-shape-image-dark.png">
</div>
<!-- /Not Authorized -->
@endsection
