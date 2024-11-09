@php
use Illuminate\Support\Facades\Route;
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', __('register_title'))

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
        <img src="{{ asset('assets/img/illustrations/auth-register-illustration-'.$configData['style'].'.svg') }}" alt="auth-register-cover" class="my-5 auth-illustration" data-app-light-img="illustrations/auth-register-illustration-light.svg" data-app-dark-img="illustrations/auth-register-illustration-dark.svg">
        <img src="{{ asset('assets/img/illustrations/bg-shape-image-'.$configData['style'].'.webp') }}" alt="auth-register-cover" class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.webp" data-app-dark-img="illustrations/bg-shape-image-dark.webp">
      </div>
    </div>
    <!-- /Left Text -->

    <!-- Register -->
    <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-sm-12 p-6">
      <div class="w-px-400 mx-auto mt-12 pt-5">
        <h4 class="mb-1">{{ __('register_start_message') }}</h4>
        <p class="mb-6">{{ __('register_description') }}</p>

        <form id="formAuthentication" class="mb-6" action="{{ route('register') }}" method="POST">
          @csrf
          <div class="mb-6">
            <label for="username" class="form-label">{{ __('username_label') }}</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="username" name="name" placeholder="{{ __('username_placeholder') }}" autofocus value="{{ old('name') }}" />
            @error('name')
              <span class="invalid-feedback" role="alert">
                <span class="fw-medium">{{ $message }}</span>
              </span>
            @enderror
          </div>
          <div class="mb-6">
            <label for="email" class="form-label">{{ __('email_label') }}</label>
            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="{{ __('email_placeholder') }}" value="{{ old('email') }}" />
            @error('email')
              <span class="invalid-feedback" role="alert">
                <span class="fw-medium">{{ $message }}</span>
              </span>
            @enderror
          </div>
          <div class="mb-6 form-password-toggle">
            <label class="form-label" for="password">{{ __('password_label') }}</label>
            <div class="input-group input-group-merge @error('password') is-invalid @enderror">
              <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('password_placeholder') }}" aria-describedby="password" />
              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
            @error('password')
              <span class="invalid-feedback" role="alert">
                <span class="fw-medium">{{ $message }}</span>
              </span>
            @enderror
          </div>

          <div class="mb-6 form-password-toggle">
            <label class="form-label" for="password-confirm">{{ __('confirm_password_label') }}</label>
            <div class="input-group input-group-merge">
              <input type="password" id="password-confirm" class="form-control" name="password_confirmation" placeholder="{{ __('password_placeholder') }}" aria-describedby="password" />
              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
          </div>
          @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="mb-6 mt-8">
              <div class="form-check mb-8 ms-2 @error('terms') is-invalid @enderror">
                <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms" name="terms" />
                <label class="form-check-label" for="terms">
                  {{ __('agree_terms') }}
                  <a href="{{ route('policy.show') }}" target="_blank">{{ __('privacy_policy') }}</a> &
                  <a href="{{ route('terms.show') }}" target="_blank">{{ __('terms_conditions') }}</a>
                </label>
              </div>
              @error('terms')
                <div class="invalid-feedback" role="alert">
                    <span class="fw-medium">{{ $message }}</span>
                </div>
              @enderror
            </div>
          @endif
          <button type="submit" class="btn btn-primary d-grid w-100">{{ __('register_button') }}</button>
        </form>

        <p class="text-center">
          <span>{{ __('already_have_account') }}</span>
          @if (Route::has('login'))
            <a href="{{ route('login') }}">
              <span>{{ __('sign_in_instead') }}</span>
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
    <!-- /Register -->
  </div>
</div>
@endsection
