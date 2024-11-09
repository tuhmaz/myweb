@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
$containerNav = ($configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');
@endphp

<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
  @endif
  @if(isset($navbarDetached) && $navbarDetached == '')
  <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="{{$containerNav}}">
      @endif

      <!--  Brand edu (display only for navbar-full and hide on below xl) -->
      @if(isset($navbarFull))
        <div class="navbar-brand app-brand edu d-none d-xl-flex py-0 me-4">
          <a href="{{url('/')}}" class="app-brand-link">
            <span class="app-brand-logo edu"> <img src="{{ asset('storage/' . config('settings.site_logo')) }}" alt="LogoWebsite" style="max-width: 20px; height: auto;"></span>
            <span class="app-brand-text edu menu-text fw-bold">{{config('settings.site_name')}}</span>
          </a>
          @if(isset($menuHorizontal))
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
              <i class="ti ti-x ti-md align-middle"></i>
            </a>
          @endif
        </div>
      @endif

      <!-- ! Not required for layout-without-menu -->
      @if(!isset($navbarHideToggle))
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
          <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-md"></i>
          </a>
        </div>
      @endif

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">


       <ul class="navbar-nav flex-row align-items-center ms-auto">


          <!-- Language -->
          <li class="nav-item dropdown-language dropdown">
            <a class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
              <i class='ti ti-language rounded-circle ti-md'></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item {{ app()->getLocale() === 'ar' ? 'active' : '' }}" href="{{url('lang/ar')}}" data-language="ar" data-text-direction="rtl">
                  <span>Arabic</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{url('lang/en')}}" data-language="en" data-text-direction="ltr">
                  <span>English</span>
                </a>
              </li>
            </ul>
          </li>
          <!--/ Language -->

          @if($configData['hasCustomizer'] == true)
            <!-- Style Switcher -->
            <li class="nav-item dropdown-style-switcher dropdown">
              <a class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                <i class='ti ti-md'></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                <li>
                  <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                    <span class="align-middle"><i class='ti ti-sun ti-md me-3'></i>Light</span>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                    <span class="align-middle"><i class="ti ti-moon-stars ti-md me-3"></i>Dark</span>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                    <span class="align-middle"><i class="ti ti-device-desktop-analytics ti-md me-3"></i>System</span>
                  </a>
                </li>
              </ul>
            </li>
            <!-- / Style Switcher -->
          @endif

          <!-- Quick links  -->
          <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown">
            <a class="nav-link btn btn-text-secondary btn-icon rounded-pill btn-icon dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
              <i class='ti ti-layout-grid-add ti-md'></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end p-0" style="left: 0px;">
              <div class="dropdown-menu-header border-bottom">
                <div class="dropdown-header d-flex align-items-center py-3">
                  <h6 class="mb-0 me-auto">Shortcuts</h6>
                  <a href="javascript:void(0)" class="btn btn-text-secondary rounded-pill btn-icon dropdown-shortcuts-add" data-bs-toggle="tooltip" data-bs-placement="top" title="Add shortcuts"><i class="ti ti-plus text-heading"></i></a>
                </div>
              </div>
              <div class="dropdown-shortcuts-list scrollable-container">
                <div class="row row-bordered overflow-visible g-0">
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                      <i class="ti ti-calendar ti-26px text-heading"></i>
                    </span>
                    <a href="{{url('dashboard/calendar')}}" class="stretched-link"> {{ __('Calendar') }}</a>
                    <small>Appointments</small>
                  </div>
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                      <i class="ti ti-home ti-26px text-heading"></i>
                    </span>
                    <a href="{{url('/')}}" class="stretched-link">{{ __('home') }}</a>
                    <small>Manage Accounts</small>
                  </div>
                </div>
                <div class="row row-bordered overflow-visible g-0">
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                      <i class="ti ti-user ti-26px text-heading"></i>
                    </span>
                    <a href="{{url('dashboard/users')}}" class="stretched-link">{{ __('User') }}</a>
                    <small>Manage Users</small>
                  </div>
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                      <i class="ti ti-users ti-26px text-heading"></i>
                    </span>
                    <a href="{{url('dashboard/roles')}}" class="stretched-link">{{ __('Roles List') }}</a>
                    <small>Permission</small>
                  </div>
                </div>
                <div class="row row-bordered overflow-visible g-0">
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                      <i class="ti ti-device-desktop-analytics ti-26px text-heading"></i>
                    </span>
                    <a href="{{url('dashboard')}}" class="stretched-link">{{__('Dashboard')}}</a>
                    <small>User Dashboard</small>
                  </div>
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                      <i class="ti ti-settings ti-26px text-heading"></i>
                    </span>
                    <a href="{{url('dashboard/settings')}}" class="stretched-link">{{ __('Settings') }}</a>
                    <small>Account Settings</small>
                  </div>
                </div>


              </div>
            </div>
          </li>
          <!-- Quick links -->

<!-- Notification -->
<li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
    <a class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <span class="position-relative">
            <i class="ti ti-bell ti-md"></i>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
            @endif
        </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end p-0" style="left: -100; left: 0px;">
        <li class="dropdown-menu-header border-bottom">
            <div class="dropdown-header d-flex align-items-center py-3">
                <h6 class="mb-0 me-auto">Notifications</h6>
                <div class="d-flex align-items-center h6 mb-0">
                    <span class="badge bg-label-primary me-2">{{ auth()->user()->unreadNotifications->count() }} New</span>
                    <a href="javascript:void(0)" class="btn btn-text-secondary rounded-pill btn-icon dropdown-notifications-all" data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read" onclick="event.preventDefault(); document.getElementById('mark-all-as-read-form').submit();">
                        <i class="ti ti-mail-opened text-heading"></i>
                    </a>
                    <form id="mark-all-as-read-form" action="{{ route('notifications.markAllAsRead') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </li>
        <li class="dropdown-notifications-list scrollable-container">
            <ul class="list-group list-group-flush">
                @foreach(auth()->user()->unreadNotifications as $notification)
                    <li class="list-group-item {{ is_null($notification->read_at) ? 'list-group-item-warning' : '' }}">
                        @if (isset($notification->data['role']) || isset($notification->data['permission']))
                            <strong>Role/Permission:</strong>
                            @if (isset($notification->data['role']))
                                Role: {{ $notification->data['role'] }}
                            @endif
                            @if (isset($notification->data['permission']))
                                Permission: {{ $notification->data['permission'] }}
                            @endif
                        @elseif (isset($notification->data['article_id']))
                            <strong>New Article:</strong>
                            <a href="{{ $notification->data['url'] }}">{{ $notification->data['title'] }}</a>
                        @elseif (isset($notification->data['message_id']))
                            <strong>New Message:</strong>
                            <a href="{{ $notification->data['url'] }}">{{ $notification->data['title'] }}</a>
                        @endif
                        <br>
                        <small class="text-muted">Received {{ $notification->created_at->diffForHumans() }}</small>

                        <div class="float-end">
                            @if (is_null($notification->read_at))
                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-primary">Mark as Read</button>
                                </form>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </li>
        <li class="border-top">
            <div class="d-grid p-4">
                <a class="btn btn-primary btn-sm d-flex" href="{{ route('notifications.index') }}">
                    <small class="align-middle">View all notifications</small>
                </a>
            </div>
        </li>
    </ul>
</li>
<!--/ Notification -->



         <!-- User -->
<li class="nav-item navbar-dropdown dropdown-user dropdown">
    <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
    @php
    $randomAvatar = 'assets/img/avatars/' . rand(1, 8) . '.png';
@endphp

<div class="avatar avatar-online">
    <img src="{{ Auth::user() && Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset($randomAvatar) }}" alt="Avatar" class="rounded-circle">
</div>
</a>
<ul class="dropdown-menu dropdown-menu-end" style="left: 0px;">
    <li>
        <a class="dropdown-item mt-0" href="{{ route('users.show', Auth::user()->id) }}">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-2">
                    <div class="avatar avatar-online">
                        <img src="{{ Auth::user() && Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset($randomAvatar) }}" alt="Avatar" class="rounded-circle">
                    </div>
                </div>

                    <div class="flex-grow-1">
                        <h6 class="mb-0">
                            {{ Auth::user() ? Auth::user()->name : 'Guest' }}
                        </h6>
                        <small class="text-muted">{{ Auth::user() ? 'Admin' : '' }}</small>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <div class="dropdown-divider my-1 mx-n2"></div>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('users.show', Auth::user()->id) }}">
                <i class="ti ti-user me-3 ti-md"></i><span class="align-middle">My Profile</span>
            </a>
        </li>

        <!-- If Jetstream API features are enabled -->
        @if (Auth::check() && Laravel\Jetstream\Jetstream::hasApiFeatures())
            <li>
                <a class="dropdown-item" href="{{ route('api-tokens.index') }}">
                    <i class="ti ti-key ti-md me-3"></i><span class="align-middle">API Tokens</span>
                </a>
            </li>
        @endif

        <li>
            <div class="dropdown-divider my-1 mx-n2"></div>
        </li>

        <!-- Logout -->
        @if (Auth::check())
            <li>
                <div class="d-grid px-2 pt-2 pb-1">
                    <a class="btn btn-sm btn-danger d-flex" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <small class="align-middle">Logout</small>
                        <i class="ti ti-logout ms-2 ti-14px"></i>
                    </a>
                </div>
                <form method="POST" id="logout-form" action="{{ route('logout') }}">
                    @csrf
                </form>
            </li>
        @else
            <li>
                <div class="d-grid px-2 pt-2 pb-1">
                    <a class="btn btn-sm btn-primary d-flex" href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
                        <small class="align-middle">Login</small>
                        <i class="ti ti-login ms-2 ti-14px"></i>
                    </a>
                </div>
            </li>
        @endif
    </ul>
</li>
<!--/ User -->

        </ul>
      </div>

      <!-- Search Small Screens -->
      <div class="navbar-search-wrapper search-input-wrapper {{ isset($menuHorizontal) ? $containerNav : '' }} d-none">
        <input type="text" class="form-control search-input {{ isset($menuHorizontal) ? '' : $containerNav }} border-0" placeholder="Search..." aria-label="Search...">
        <i class="ti ti-x search-toggler cursor-pointer"></i>
      </div>
      <!--/ Search Small Screens -->
      @if(isset($navbarDetached) && $navbarDetached == '')
    </div>
    @endif
  </nav>
  <!-- / Navbar -->
