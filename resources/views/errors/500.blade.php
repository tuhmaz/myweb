@php
\$customizerHidden = 'customizer-hide';
\$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Server Error - Pages'))

@section('page-style')
<!-- Page -->
@vite(['resources/assets/vendor/scss/pages/page-misc.scss'])
@endsection

@section('content')
<!-- Server Error -->
<div class="container-xxl container-p-y">
  <div class="misc-wrapper text-center">
    <h1 class="mb-2 mx-2" style="line-height: 6rem; font-size: 6rem;">500</h1>
    <h4 class="mb-2 mx-2">{{ __('Internal Server Error!') }} ⚠️</h4>
    <p class="mb-6 mx-2">{{ __('Oops! Something went wrong on our server. Please try again later.') }}</p>
    <a href="{{ url('/') }}" class="btn btn-primary">{{ __('Back to home') }}</a>
    <div class="mt-12">
      <img src="{{ asset('assets/img/illustrations/page-misc-server-error.svg') }}" alt="{{ __('page-misc-server-error') }}" width="170" class="img-fluid">
    </div>
  </div>
</div>
<div class="container-fluid misc-bg-wrapper">
  <img src="{{ asset('assets/img/illustrations/bg-shape-image-' . \$configData['style'] . '.png') }}" height="355" alt="{{ __('page-misc-server-error') }}" data-app-light-img="illustrations/bg-shape-image-light.png" data-app-dark-img="illustrations/bg-shape-image-dark.png">
</div>
<!-- /Server Error -->
@endsection
