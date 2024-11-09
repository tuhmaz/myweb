@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Users'))

@section('content')
<!-- Users List Table -->
<div class="card">
  <div class="card-header border-bottom">
    <h5 class="card-title mb-0">{{ __('Filters') }}</h5>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4 pt-4">
      @if(Auth::user()->hasRole('Admin'))
      <div class="col-12 col-md-4">
        <form action="{{ route('users.index') }}" method="GET">
          <select name="role" id="user-role" class="form-select">
            <option value="">{{ __('Select Role') }}</option>
            @foreach ($roles as $role)
              <option value="{{ $role->name }}" {{ request()->get('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
            @endforeach
          </select>
      </div>
      <div class="col-12 col-md-4">
        <input type="text" name="search" class="form-control" placeholder="{{ __('Search by name or email') }}" value="{{ request()->get('search') }}">
      </div>
      <div class="col-12 col-md-4 text-md-end">
        <button type="submit" class="btn btn-primary w-100 w-md-auto">{{ __('Apply Filters') }}</button>
        </form>
      </div>
      @endif
    </div>
  </div>
  <div class="card-datatable table-responsive">
    <table class="table table-hover">
      <thead class="border-top">
        <tr>
          <th>{{ __('User') }}</th>
          <th>{{ __('Role') }}</th>
          <th>{{ __('Status') }}</th>
          <th>{{ __('Actions') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($users as $user)
          @if(Auth::user()->hasRole('Admin') || Auth::user()->id == $user->id)
            <tr>
              <td>
                <strong>{{ $user->name }}</strong><br>
                <small>{{ $user->email }}</small>
              </td>
              <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
              <td>
                @if($user->status == 'online')
                  <span class="badge bg-success">Online</span>
                @else
                  <span class="badge bg-secondary">Offline</span>
                @endif
              </td>
              <td>
                <div class="btn-group d-none d-md-inline-flex" role="group" aria-label="User Actions">
                  <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                  @if(Auth::user()->hasRole('Admin'))
                    <a href="{{ route('users.permissions_roles', $user->id) }}" class="btn btn-sm btn-primary">{{ __('Permissions') }}</a>
                  @endif
                  <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">{{ __('View Profile') }}</a>
                  @if(Auth::user()->hasRole('Admin'))
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                    </form>
                  @endif
                </div>

                <!-- Dropdown for mobile devices -->
                <div class="dropdown d-md-none">
                  <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ __('Actions') }}
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $user->id }}">
                    <li><a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">{{ __('Edit') }}</a></li>
                    @if(Auth::user()->hasRole('admin'))
                      <li><a class="dropdown-item" href="{{ route('users.permissions_roles', $user->id) }}">{{ __('Permissions') }}</a></li>
                    @endif
                    <li><a class="dropdown-item" href="{{ route('users.show', $user->id) }}">{{ __('View Profile') }}</a></li>
                    @if(Auth::user()->hasRole('admin'))
                      <li>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="dropdown-item">{{ __('Delete') }}</button>
                        </form>
                      </li>
                    @endif
                  </ul>
                </div>
              </td>
            </tr>
          @endif
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<!-- End Users List Table -->

<!-- Pagination -->
<div class="pagination pagination-outline-secondary">
  {{ $users->links('components.pagination.custom') }}
</div>

<!-- End Pagination -->
@endsection
