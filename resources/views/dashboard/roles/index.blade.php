@php
$configData = Helper::appClasses();
@endphp

@extends('layouts.layoutMaster')

@section('title', __('Roles List'))

@section('content')
<h4 class="mb-1">{{ __('Roles List') }}</h4>

<p class="mb-6">{{ __('A role provides access to predefined menus and features so that depending on the assigned role, an administrator can have access to what the user needs.') }}</p>

<!-- Role cards -->
<div class="row g-6">
    @foreach ($roles as $role)
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-normal mb-0 text-body">{{ __('Total :count users', ['count' => $role->users_count]) }}</h6>
                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                        @foreach ($role->users->take(4) as $user)
                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $user->name }}" class="avatar pull-up">
                        <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('assets/img/avatars/default-avatar.png') }}" alt="{{ __('Avatar') }}" class="img-fluid rounded-circle mb-3" width="150">
                        </li>
                        @endforeach
                        @if ($role->users->count() > 4)
                        <li class="avatar">
                            <span class="avatar-initial rounded-circle pull-up" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $role->users->count() - 4 }} more">+{{ $role->users->count() - 4 }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div class="role-heading">
                        <h5 class="mb-1">{{ $role->name }}</h5>
                        <a href="{{ route('roles.edit', $role) }}" class="role-edit-modal"><span>{{ __('Edit Role') }}</span></a>
                    </div>
                    <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card h-100">
            <div class="row h-100">
                <div class="col-sm-5">
                    <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-4">
                        <img src="{{ asset('assets/img/illustrations/add-new-roles.png') }}" class="img-fluid mt-sm-4 mt-md-0" alt="add-new-roles" width="83">
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="card-body text-sm-end text-center ps-sm-0">
                        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary mb-4 text-nowrap add-new-role">{{ __('Add New Role') }}</a>
                        <p class="mb-0">{{ __('Add new role, if it doesn\'t exist.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Total users with their roles -->
<div class="col-12 mt-6">
    <h4 class="mt-6 mb-1">{{ __('Total users with their roles') }}</h4>
    <p class="mb-0">{{ __('Find all of your company’s administrator accounts and their associated roles.') }}</p>
</div>

<div class="col-12 mt-4">
    <!-- Role Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                    <tr>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!--/ Role Table -->
</div>
@endsection
