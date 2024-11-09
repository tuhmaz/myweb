@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Edit Permission'))

@section('content')
<div class="container mt-4">
    <div class="card">
        <h5 class="card-header">{{ __('Edit Permission') }}</h5>
        <div class="card-body">
            <!-- نموذج تعديل الصلاحية -->
            <form action="{{ route('permissions.update', $permission) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- حقل اسم الصلاحية -->
                <div class="mb-3">
                    <label for="permissionName" class="form-label">{{ __('Permission Name') }}</label>
                    <input type="text" id="permissionName" name="name" class="form-control" value="{{ $permission->name }}" required>
                </div>

                <!-- زر التحديث وزر الرجوع -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
