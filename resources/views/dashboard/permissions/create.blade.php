@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Create Permission'))

@section('content')
<div class="container mt-4">
    <div class="card">
        <h5 class="card-header">{{ __('Create a New Permission') }}</h5>
        <div class="card-body">
            <!-- نموذج إنشاء الصلاحية -->
            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf

                <!-- حقل اسم الصلاحية -->
                <div class="mb-3">
                    <label for="permissionName" class="form-label">{{ __('Permission Name') }}</label>
                    <input type="text" id="permissionName" name="name" class="form-control" placeholder="{{ __('Enter permission name') }}" required>
                </div>

                <!-- زر الإرسال وزر الرجوع -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
