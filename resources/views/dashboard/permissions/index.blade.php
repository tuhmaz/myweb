@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Permissions'))

@section('content')
<div class="container mt-4">
    <div class="card">
        <h5 class="card-header">{{ __('Permissions') }}</h5>
        <div class="card-body">
            <a href="{{ route('permissions.create') }}" class="btn btn-primary mb-4">{{ __('Create Permission') }}</a>

            @if ($permissions->isEmpty())
                <div class="alert alert-info">
                    {{ __('No permissions found.') }}
                </div>
            @else
                <ul class="list-group">
                    @foreach ($permissions as $permission)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $permission->name }}</span>
                            <span>
                                <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                                </form>
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
