@extends('layouts.layoutMaster')

@section('title', __('User Profile'))

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>{{ __('User Profile') }}: {{ $user->name }}</h5>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                <i class="ti ti-pencil"></i> {{ __('Edit Profile') }}
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('assets/img/avatars/default-avatar.png') }}" alt="{{ __('Avatar') }}" class="img-fluid rounded-circle mb-3" width="150">
                    <h3>{{ $user->name }}</h3>
                    <p class="text-muted">{{ $user->job_title ?? __('N/A') }}</p>
                </div>
                <div class="col-md-8">
                    <div class="row mb-2">
                        <div class="col-6">
                            <p><strong>{{ __('Email') }}:</strong> {{ $user->email }}</p>
                        </div>
                        <div class="col-6">
                            <p><strong>{{ __('Phone') }}:</strong> {{ $user->phone ?? __('N/A') }}</p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <p><strong>{{ __('Gender') }}:</strong> {{ $user->gender ? __('gender.' . $user->gender) : __('N/A') }}</p>
                        </div>
                        <div class="col-6">
                            <p><strong>{{ __('Country') }}:</strong> {{ $user->country ?? __('N/A') }}</p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <p><strong>{{ __('Facebook') }}:</strong> <a href="{{ 'https://www.facebook.com/' . $user->social_links }}" target="_blank">{{ $user->social_links }}</a></p>
                        </div>
                        <div class="col-6">
                            <p><strong>{{ __('Status') }}:</strong>
                                @if($user->status == 'online')
                                    <span class="badge bg-success">{{ __('Online') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Offline') }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <p><strong>{{ __('Bio') }}:</strong> {{ $user->bio ?? __('N/A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    {{ __('Back to Users List') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
