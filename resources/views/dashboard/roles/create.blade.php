@php
$configData = Helper::appClasses();
@endphp

@extends('layouts.layoutMaster')

@section('title', __('Create Role'))

@section('content')
<div class="container mt-4">
    <div class="card">
        <h5 class="card-header">{{ __('Create a New Role') }}</h5>
        <div class="card-body">
            <!-- عرض الأخطاء إن وجدت -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- نموذج إنشاء الدور -->
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                <!-- حقل اسم الدور -->
                <div class="mb-3">
                    <label for="roleName" class="form-label">{{ __('Role Name') }}</label>
                    <input type="text" id="roleName" name="name" class="form-control" placeholder="{{ __('Enter role name') }}" value="{{ old('name') }}" required>
                </div>

                <!-- تخصيص الصلاحيات -->
                <h6 class="mt-4">{{ __('Assign Permissions') }}</h6>
                <div class="row">
                    @foreach ($permissions as $permission)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input type="checkbox" id="permission-{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" class="form-check-input">
                                <label for="permission-{{ $permission->id }}" class="form-check-label">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- زر الإرسال -->
                <button type="submit" class="btn btn-primary mt-4">{{ __('Create') }} {{ __('Role') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
