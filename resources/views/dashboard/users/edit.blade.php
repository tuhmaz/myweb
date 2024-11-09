@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Edit User'))

@section('content')
<div class="container mt-4">
    <div class="card">
        <h5 class="card-header">{{ __('Edit User for :name', ['name' => $user->name]) }}</h5>
        <div class="card-body">
            <!-- تبويبات تعديل المستخدم -->
            <div class="nav-align-top nav-tabs-shadow mb-6">
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-basic-info" aria-controls="navs-basic-info" aria-selected="true">
                            <span class="d-none d-sm-block"><i class="tf-icons ti ti-user ti-sm me-1_5"></i> {{ __('Basic Info') }}</span>
                            <i class="ti ti-user ti-sm d-sm-none"></i>
                        </button>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-account-management" aria-controls="navs-account-management" aria-selected="false">
                            <span class="d-none d-sm-block"><i class="tf-icons ti ti-settings ti-sm me-1_5"></i> {{ __('Account Management') }}</span>
                            <i class="ti ti-settings ti-sm d-sm-none"></i>
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- التبويب الأول: المعلومات الأساسية -->
                    <div class="tab-pane fade show active" id="navs-basic-info" role="tabpanel">
                        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                            @csrf
                            @method('PUT')

                            <!-- الحقول الأساسية -->
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>

                            <!-- تغيير الصورة الرمزية -->
                            <div class="mb-3">
                                <label for="profile_photo" class="form-label">{{ __('Profile Photo') }}</label>
                                @if ($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ __('Avatar') }}" class="rounded-circle mb-3" width="100">
                                @endif
                                <input type="file" id="profile_photo" name="profile_photo" class="form-control">
                            </div>

                            <!-- الحقول الإضافية -->
                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('Phone') }}</label>
                                <input type="text" id="phone" name="phone" class="form-control" value="{{ $user->phone }}">
                            </div>

                            <div class="mb-3">
                                <label for="job_title" class="form-label">{{ __('Job Title') }}</label>
                                <input type="text" id="job_title" name="job_title" class="form-control" value="{{ $user->job_title }}">
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">{{ __('Gender') }}</label>
                                <select id="gender" name="gender" class="form-select">
                                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="country" class="form-label">{{ __('Country') }}</label>
                                <select id="country" name="country" class="form-select">
                                    <option value="Saudi Arabia" {{ $user->country == 'Saudi Arabia' ? 'selected' : '' }}>{{ __('Saudi Arabia') }}</option>
                                    <option value="Egypt" {{ $user->country == 'Egypt' ? 'selected' : '' }}>{{ __('Egypt') }}</option>
                                    <option value="UAE" {{ $user->country == 'UAE' ? 'selected' : '' }}>{{ __('UAE') }}</option>
                                    <option value="Algeria" {{ $user->country == 'Algeria' ? 'selected' : '' }}>{{ __('Algeria') }}</option>
                                    <option value="Bahrain" {{ $user->country == 'Bahrain' ? 'selected' : '' }}>{{ __('Bahrain') }}</option>
                                    <option value="Comoros" {{ $user->country == 'Comoros' ? 'selected' : '' }}>{{ __('Comoros') }}</option>
                                    <option value="Djibouti" {{ $user->country == 'Djibouti' ? 'selected' : '' }}>{{ __('Djibouti') }}</option>
                                    <option value="Iraq" {{ $user->country == 'Iraq' ? 'selected' : '' }}>{{ __('Iraq') }}</option>
                                    <option value="Jordan" {{ $user->country == 'Jordan' ? 'selected' : '' }}>{{ __('Jordan') }}</option>
                                    <option value="Kuwait" {{ $user->country == 'Kuwait' ? 'selected' : '' }}>{{ __('Kuwait') }}</option>
                                    <option value="Lebanon" {{ $user->country == 'Lebanon' ? 'selected' : '' }}>{{ __('Lebanon') }}</option>
                                    <option value="Libya" {{ $user->country == 'Libya' ? 'selected' : '' }}>{{ __('Libya') }}</option>
                                    <option value="Mauritania" {{ $user->country == 'Mauritania' ? 'selected' : '' }}>{{ __('Mauritania') }}</option>
                                    <option value="Morocco" {{ $user->country == 'Morocco' ? 'selected' : '' }}>{{ __('Morocco') }}</option>
                                    <option value="Oman" {{ $user->country == 'Oman' ? 'selected' : '' }}>{{ __('Oman') }}</option>
                                    <option value="Palestine" {{ $user->country == 'Palestine' ? 'selected' : '' }}>{{ __('Palestine') }}</option>
                                    <option value="Qatar" {{ $user->country == 'Qatar' ? 'selected' : '' }}>{{ __('Qatar') }}</option>
                                    <option value="Somalia" {{ $user->country == 'Somalia' ? 'selected' : '' }}>{{ __('Somalia') }}</option>
                                    <option value="Sudan" {{ $user->country == 'Sudan' ? 'selected' : '' }}>{{ __('Sudan') }}</option>
                                    <option value="Syria" {{ $user->country == 'Syria' ? 'selected' : '' }}>{{ __('Syria') }}</option>
                                    <option value="Tunisia" {{ $user->country == 'Tunisia' ? 'selected' : '' }}>{{ __('Tunisia') }}</option>
                                    <option value="Yemen" {{ $user->country == 'Yemen' ? 'selected' : '' }}>{{ __('Yemen') }}</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="facebook_username" class="form-label">{{ __('Facebook Username') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">https://facebook.com/</span>
                                    <input type="text" id="facebook_username" name="facebook_username" class="form-control" value="{{ str_replace('https://facebook.com/', '', $user->social_links) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">{{ __('Bio') }}</label>
                                <textarea id="bio" name="bio" class="form-control">{{ $user->bio }}</textarea>
                            </div>

                            <div class="mb-3 form-check">
                                @if($user->status == 'online')
                                    <span class="badge bg-success">{{ __('Online') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Offline') }}</span>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">{{ __('Update Basic Info') }}</button>
                        </form>
                    </div>

                    <!-- التبويب الثاني: إدارة الحساب -->
                    <div class="tab-pane fade" id="navs-account-management" role="tabpanel">
                        <!-- تحديث كلمة المرور -->
                        <h6>{{ __('Update Password') }}</h6>
                        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                            <div class="mb-6">
                                @livewire('profile.update-password-form')
                            </div>
                        @endif

                        <!-- إدارة الجلسات النشطة -->
                        <h6>{{ __('Manage Active Sessions') }}</h6>
                        <div class="mb-6">
                            @livewire('profile.logout-other-browser-sessions-form')
                        </div>
                    </div>
                </div>
            </div>

            <!-- زر التحديث وزر الرجوع -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('users.index') }}" class="btn btn-secondary mt-4">{{ __('Back') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
