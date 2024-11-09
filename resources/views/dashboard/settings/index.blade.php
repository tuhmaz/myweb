@extends('layouts.layoutMaster')

@section('title', __('Settings'))

@section('content')
<div class="container mt-4">
  <div class="card">
    <div class="card-header">
      <h4>{{ __('Settings') }}</h4>
    </div>
    <div class="card-body">
      <ul class="nav nav-tabs" id="settingsTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="general-tab" data-bs-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">{{ __('General') }}</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="security-tab" data-bs-toggle="tab" href="#security" role="tab" aria-controls="security" aria-selected="false">{{ __('Security') }}</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="email-tab" data-bs-toggle="tab" href="#email" role="tab" aria-controls="email" aria-selected="false">{{ __('Email') }}</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="notification-tab" data-bs-toggle="tab" href="#notification" role="tab" aria-controls="notification" aria-selected="false">{{ __('Notifications') }}</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="seo-tab" data-bs-toggle="tab" href="#seo" role="tab" aria-controls="seo" aria-selected="false">SEO</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="social-media-tab" data-bs-toggle="tab" href="#social-media" role="tab" aria-controls="social-media" aria-selected="false">{{ __('Social Media') }}</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="adsdt-tab" data-bs-toggle="tab" href="#adsdt" role="tab" aria-controls="adsdt" aria-selected="false">{{ __('Google Ads Desktop') }}</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="adsmo-tab" data-bs-toggle="tab" href="#adsmo" role="tab" aria-controls="adsmo" aria-selected="false">{{ __('Google Ads Mobile') }}</a>
        </li>



      </ul>
      <div class="tab-content mt-4" id="settingsTabContent">
        <!-- General Tab -->
        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
          @include('dashboard.settings.partials.general')
        </div>
        <!-- Security Tab -->
        <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
          @include('dashboard.settings.partials.security')
        </div>
        <!-- Email Tab -->
        <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
          @include('dashboard.settings.partials.email')
        </div>
        <!-- Notification Tab -->
        <div class="tab-pane fade" id="notification" role="tabpanel" aria-labelledby="notification-tab">
          @include('dashboard.settings.partials.notification')
        </div>
        <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
          @include('dashboard.settings.partials.seo')
        </div>
        <div class="tab-pane fade" id="social-media" role="tabpanel" aria-labelledby="social-media-tab">
          @include('dashboard.settings.partials.social-media')
        </div>
        <div class="tab-pane fade" id="adsdt" role="tabpanel" aria-labelledby="adsdt-tab">
          @include('dashboard.settings.partials.adsdt')
        </div>
        <div class="tab-pane fade" id="adsmo" role="tabpanel" aria-labelledby="adsmo-tab">
          @include('dashboard.settings.partials.adsmo')
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
