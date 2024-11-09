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
        <img src="{{ asset('assets/img/illustrations/auth-login-illustration-'.$configData['style'].'.svg') }}" alt="auth-login-cover" class="my-5 auth-illustration" data-app-light-img="illustrations/auth-login-illustration-light.svg" data-app-dark-img="illustrations/auth-login-illustration-dark.svg">
        <img src="{{ asset('assets/img/illustrations/bg-shape-image-'.$configData['style'].'.webp') }}" alt="auth-login-cover" class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.webp" data-app-dark-img="illustrations/bg-shape-image-dark.webp">
      </div>
    </div>
    <!-- /Left Text -->

    <!-- Login -->
    <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-sm-12 p-6">
      <div class="w-px-400 mx-auto mt-12 pt-5">
        <h4 class="mb-1">{{ __('welcome_message') }}</h4>
        <p class="mb-6">{{ __('sign_in_message') }}</p>

        @if (session('status'))
          <div class="alert alert-success mb-1 rounded-0" role="alert">
            <div class="alert-body">
              {{ __('alert_success') }}
            </div>
          </div>
        @endif

        <form id="formAuthentication" class="mb-6" action="{{ route('login') }}" method="POST">
          @csrf
          <div class="mb-6">
            <label for="login-email" class="form-label">{{ __('email_label') }}</label>
            <input type="text" class="form-control @error('email') is-invalid @enderror" id="login-email" name="email" placeholder="{{ __('email_placeholder') }}" autofocus value="{{ old('email') }}">
            @error('email')
            <span class="invalid-feedback" role="alert">
              <span class="fw-medium">{{ $message }}</span>
            </span>
            @enderror
          </div>
          <div class="mb-6 form-password-toggle">
            <label class="form-label" for="login-password">{{ __('password_label') }}</label>
            <div class="input-group input-group-merge @error('password') is-invalid @enderror">
              <input type="password" id="login-password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('password_placeholder') }}" aria-describedby="password" />
              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
            @error('password')
              <span class="invalid-feedback" role="alert">
                <span class="fw-medium">{{ $message }}</span>
              </span>
            @enderror
          </div>
          <div class="my-8">
            <div class="d-flex justify-content-between">
              <div class="form-check mb-0 ms-2">
                <input class="form-check-input" type="checkbox" id="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember-me">
                  {{ __('remember_me') }}
                </label>
              </div>
              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                  <p class="mb-0">{{ __('forgot_password') }}</p>
                </a>
              @endif
            </div>
          </div>
          <button class="btn btn-primary d-grid w-100" type="submit">{{ __('sign_in_button') }}</button>
        </form>

        <p class="text-center">
          <span>{{ __('new_on_platform') }}</span>
          @if (Route::has('register'))
            <a href="{{ route('register') }}">
              <span>{{ __('create_account') }}</span>
            </a>
          @endif
        </p>

        <div class="divider my-6">
          <div class="divider-text">{{ __('divider_text') }}</div>
        </div>

         <div class="d-flex justify-content-center">
        <a href="{{ route('auth.google') }}" class="btn btn-sm btn-icon rounded-pill btn-text-google-plus" title="{{ __('social_google') }}">
       <img src="{{ asset('/assets/img/icon/google.webp') }}" alt="{{ __('social_google') }}" class="google-icon" style="width: 80px; height: 80px;"  />
</a>

        </div>
      </div>
    </div>
    <!-- /Login -->
  </div>
</div>
@endsection
