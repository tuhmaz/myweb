@php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
$currentRouteName = Route::currentRouteName();
@endphp

<nav class="layout-navbar shadow-none py-0">
  <div class="container">
    <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-8">
      <div class="navbar-brand app-brand  d-flex py-0 py-lg-2 me-4 me-xl-8">
        <button class="navbar-toggler border-0 px-0 me-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <i class="ti ti-menu-2 ti-lg align-middle text-heading fw-medium"></i>
        </button>
        <a href="javascript:;" class="app-brand-link">
          <span class="app-brand-logo d-none d-md-inline">
            <img src="{{ asset('storage/' . config('settings.site_logo')) }}" alt="LogoWebsite" style="max-width: 50px; height: auto;" loading="lazy">
          </span>
          <span class="app-brand-text edu menu-text fw-bold ms-2 ps-1">{{config('settings.site_name')}}</span>
        </a>
      </div>

      <ul class="navbar-nav flex-row align-items-center ms-auto">
        <li class="navbar-nav flex-row align-items-center ms-auto me-3">
          <form method="POST" action="{{ route('setDatabase') }}" id="databaseForm">
            @csrf
            <input type="hidden" name="database" id="databaseInput" value="{{ session('database', 'jo') }}">
            <div class="dropdown">
              <button type="button" class="btn btn-outline-warning btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                @php
                $currentCountry = session('database', 'jo');
                $flag = match ($currentCountry) {
                'sa' => 'saudi.svg',
                'eg' => 'egypt.svg',
                'ps' => 'palestine.svg',
                default => 'jordan.svg',
                };
                @endphp
                <img alt="Current Country" src="{{ asset('assets/img/flags/' . $flag) }}" style="width: 20px; height: 20px;" loading="lazy">
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="#" onclick="setDatabase('jo')"><img alt="Jordan" src="{{ asset('assets/img/flags/jordan.svg') }}" style="width: 20px; height: 20px;" loading="lazy"> الأردن</a></li>
                <li><a class="dropdown-item" href="#" onclick="setDatabase('sa')"><img alt="Saudi Arabia" src="{{ asset('assets/img/flags/saudi.svg') }}" style="width: 20px; height: 20px;" loading="lazy"> السعودية</a></li>
                <li><a class="dropdown-item" href="#" onclick="setDatabase('eg')"><img alt="Egypt" src="{{ asset('assets/img/flags/egypt.svg') }}" style="width: 20px; height: 20px;" loading="lazy"> مصر</a></li>
                <li><a class="dropdown-item" href="#" onclick="setDatabase('ps')"><img alt="Palestine" src="{{ asset('assets/img/flags/palestine.svg') }}" style="width: 20px; height: 20px;" loading="lazy"> فلسطين</a></li>
              </ul>
            </div>
          </form>
          <script>
            function setDatabase(database) {
              document.getElementById('databaseInput').value = database;
              document.getElementById('databaseForm').submit();
            }
          </script>
        </li>

        <div class="landing-menu-overlay d-lg-none"></div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
          @if($configData['hasCustomizer'] == true)
          <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-1">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
              <i class='ti ti-lg'></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
              <li><a class="dropdown-item" href="javascript:void(0);" data-theme="light"><span class="align-middle"><i class='ti ti-sun me-3'></i>Light</span></a></li>
              <li><a class="dropdown-item" href="javascript:void(0);" data-theme="dark"><span class="align-middle"><i class="ti ti-moon-stars me-3"></i>Dark</span></a></li>
              <li><a class="dropdown-item" href="javascript:void(0);" data-theme="system"><span class="align-middle"><i class="ti ti-device-desktop-analytics me-3"></i>System</span></a></li>
            </ul>
          </li>
          @endif
          <li>
            @if(Auth::check())
          <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
              @php
              $randomAvatar = 'assets/img/avatars/' . rand(1, 8) . '.png';
              @endphp
              <div class="avatar avatar-online">
                <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset($randomAvatar) }}" alt="Avatar" class="rounded-circle" loading="lazy">
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" style="left: -50;left: -100;left: -70;left: 0px;">
              <li><a class="dropdown-item mt-0" href="{{ route('users.show', Auth::user()->id) }}">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                      <div class="avatar avatar-online"><img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset($randomAvatar) }}" alt="Avatar" class="rounded-circle" loading="lazy"></div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-0">{{ Auth::user()->name }}</h6><small class="text-muted">Admin</small>
                    </div>
                  </div>
                </a></li>
              <li>
                <div class="dropdown-divider my-1 mx-n2"></div>
              </li>
              <li><a class="dropdown-item" href="{{ route('users.show', Auth::user()->id) }}"><i class="ti ti-user me-3 ti-md"></i><span class="align-middle">My Profile</span></a></li>
              @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
              <li><a class="dropdown-item" href="{{ route('api-tokens.index') }}"><i class="ti ti-key ti-md me-3"></i><span class="align-middle">API Tokens</span></a></li>
              @endif
              <li>
                <div class="dropdown-divider my-1 mx-n2"></div>
              </li>
              <li>
                <div class="d-grid px-2 pt-2 pb-1"><a class="btn btn-sm btn-danger d-flex" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><small class="align-middle">Logout</small><i class="ti ti-logout ms-2 ti-14px"></i></a></div>
                <form method="POST" id="logout-form" action="{{ route('logout') }}">@csrf</form>
              </li>
            </ul>
          </li>
          @else
          <li><a href="{{ route('login') }}" class="btn btn-primary"><span class="tf-icons ti ti-login scaleX-n1-rtl me-md-1"></span><span class="d-none d-md-block">Login/Register</span></a></li>
          @endif
        </ul>
      </ul>
    </div>
  </div>
</nav>
