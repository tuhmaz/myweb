@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Assign Roles and Permissions'))

@section('content')
<div class="container mt-4">
    <div class="card">
        <h5 class="card-header">{{ __('Assign Roles and Permissions for :name', ['name' => $user->name]) }}</h5>
        <div class="card-body">
            <!-- عرض الأخطاء إن وجدت -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- نموذج تخصيص الأدوار والصلاحيات -->
            <form action="{{ route('users.updatePermissionsRoles', $user->id) }}" method="POST" class="mt-4">
                @csrf
                @method('PUT')

                <!-- تخصيص الأدوار -->
                <h6>{{ __('Assign Roles') }}</h6>
                <div class="row">
                    @foreach ($roles as $role)
                        <div class="col-md-4 col-sm-6 mb-2">
                            <div class="form-check">
                                <input type="checkbox" id="role-{{ $role->id }}" name="roles[]" value="{{ $role->name }}" class="form-check-input" {{ $user->roles->contains($role) ? 'checked' : '' }}>
                                <label for="role-{{ $role->id }}" class="form-check-label">
                                    {{ $role->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- تخصيص الصلاحيات -->
                <h6>{{ __('Assign Permissions') }}</h6>
                <div class="row">
                    @foreach ($permissions as $permission)
                        <div class="col-md-4 col-sm-6 mb-2">
                            <div class="form-check">
                                <input type="checkbox" id="permission-{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" class="form-check-input" {{ $user->permissions->contains($permission) ? 'checked' : '' }}>
                                <label for="permission-{{ $permission->id }}" class="form-check-label">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- زر التحديث وزر الرجوع -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary mt-4">{{ __('Update Roles and Permissions') }}</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary mt-4">{{ __('Back') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
