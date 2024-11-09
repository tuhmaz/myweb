<!DOCTYPE html>
@php
$menuFixed = ($configData['layout'] === 'vertical') ? ($menuFixed ?? '') : (($configData['layout'] === 'front') ? '' : $configData['headerType']);
$navbarType = ($configData['layout'] === 'vertical') ? ($configData['navbarType'] ?? '') : (($configData['layout'] === 'front') ? 'layout-navbar-fixed': '');
$isFront = ($isFront ?? '') == true ? 'Front' : '';
$contentLayout = (isset($container) ? (($container === 'container-xxl') ? "layout-compact" : "layout-wide") : "");
@endphp

<html lang="{{ session()->get('locale') ?? app()->getLocale() }}" class="{{ $configData['style'] }}-style {{($contentLayout ?? '')}} {{ ($navbarType ?? '') }} {{ ($menuFixed ?? '') }} {{ $menuCollapsed ?? '' }} {{ $menuFlipped ?? '' }} {{ $menuOffcanvas ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}" dir="{{ $configData['textDirection'] }}" data-theme="{{ $configData['theme'] }}" data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{url('/')}}" data-framework="laravel" data-template="{{ $configData['layout'] . '-menu-' . $configData['themeOpt'] . '-' . $configData['styleOpt'] }}" data-style="{{$configData['styleOptVal']}}">
<title>@yield('title') |
    {{ config('settings.site_name') ? config('settings.site_name') : 'site_name' }} -
    {{ config('settings.meta_title') ? config('settings.meta_title') : 'meta_title' }}
  </title>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0">
  <meta http-equiv="Cache-Control" content="public, max-age=31536000">

  @hasSection('meta')
  @yield('meta')
  @else
  @if(request()->is('/'))
  <meta name="description" content="{{ config('settings.meta_description') ? config('settings.meta_description') : '' }}" />
  <meta name="keywords" content="{{ config('settings.meta_keywords') ? config('settings.meta_keywords') : '' }}">
  <link rel="canonical" href="{{ config('settings.canonical_url') ? config('settings.canonical_url')  : '' }}">
  @endif
  @endif
  <meta name="google_analytics" content="{{ config('settings.google_analytics_id') ? config('settings.google_analytics_id') : ''  }}">
  <meta name="facebook_pixel" content="{{ config('settings.facebook_pixel_id') ? config('settings.facebook_pixel_id') : ''  }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . config('settings.site_favicon')) }}" />
  @include('cookie-consent::index')
  @include('layouts/sections/styles' . $isFront)

  @include('layouts/sections/scriptsIncludes' . $isFront)

 
</head>

<body>


  @yield('layoutContent')



  {{-- remove while creating package --}}
  {{-- remove while creating package end --}}
  @include('layouts/sections/scripts' . $isFront)

</body>


</html>
