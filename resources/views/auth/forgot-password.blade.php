@php
use Illuminate\Support\Facades\Route;
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', __('title'))

@section('page-style')
<!-- Page -->
@vite('resources/assets/vendor/scss/pages/page-auth.scss')
@endsection

@section('content')
<div class="authentication-wrapper authentication-cover">
  <!-- Logo -->
  <a href="{{url('/')}}" class="app-brand auth-cover-brand">
    <span class="app-brand-logo edu"><img src="{{ asset('storage/' . config('settings.site_logo')) }}" alt="LogoWebsite" style="max-width: 20px; height: auto;"></span>
    <span class="app-brand-text edu text-heading fw-bold">{{config('settings.site_name')}}</span>
  </a>
  <!-- /Logo -->
  <div class="authentication-inner row m-0">

    <!-- /Left Text -->
    <div class="d-none d-lg-flex col-lg-8 p-0">
      <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
        <img src="{{ asset('assets/img/illustrations/auth-forgot-password-illustration-'.$configData['style'].'.webp') }}" alt="auth-forgot-password-cover" class="my-5 auth-illustration d-lg-block d-none" data-app-light-img="illustrations/auth-forgot-password-illustration-light.webp" data-app-dark-img="illustrations/auth-forgot-password-illustration-dark.webp">
        <img src="{{ asset('assets/img/illustrations/bg-shape-image-'.$configData['style'].'.webp') }}" alt="auth-forgot-password-cover" class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.webp" data-app-dark-img="illustrations/bg-shape-image-dark.webp">
      </div>
    </div>
    <!-- /Left Text -->

    <!-- Forgot Password -->
    <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-sm-12 p-6">
      <div class="w-px-400 mx-auto mt-12 mt-5">
        <h4 class="mb-1">{{ __('forgot_password_title') }}</h4>
        <p class="mb-6">{{ __('forgot_password_description') }}</p>

        @if (session('status'))
          <div class="mb-1 text-success">
            {{ __('status_success') }}
          </div>
        @endif

        <form id="formAuthentication" class="mb-6" action="{{ route('password.email') }}" method="POST">
          @csrf
          <div class="mb-6">
            <label for="email" class="form-label">{{ __('email_label') }}</label>
            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="{{ __('email_placeholder') }}" autofocus>
            @error('email')
              <span class="invalid-feedback" role="alert">
                <span class="fw-medium">{{ $message }}</span>
              </span>
            @enderror
          </div>
          <button type="submit" class="btn btn-primary d-grid w-100">{{ __('send_reset_link') }}</button>
        </form>

        <div class="text-center">
          @if (Route::has('login'))
          <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
            <i class="ti ti-chevron-left scaleX-n1-rtl me-1_5"></i>
            {{ __('back_to_login') }}
          </a>
          @endif
        </div>
      </div>
    </div>
    <!-- /Forgot Password -->
  </div>
</div>
@endsection
