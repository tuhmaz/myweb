@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Important Messages')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="ri-star-line me-2"></i>Important Messages</h4>
        <div class="input-group w-50">
            <input type="text" class="form-control" placeholder="Search important messages">
            <button class="btn btn-outline-secondary" type="button"><i class="ri-search-line"></i></button>
        </div>
    </div>

    <!-- Toolbar Section -->
    <div class="d-flex justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <div class="form-check me-3">
                <input type="checkbox" class="form-check-input" id="checkAll">
                <label class="form-check-label" for="checkAll"></label>
            </div>
            <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line me-1"></i>Delete Selected</button>
        </div>
    </div>

    <!-- Important Messages List -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <tbody>
                @forelse ($messages as $message)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            @php
                                $senderAvatar = $message->sender->avatar && Storage::exists('public/' . $message->sender->avatar)
                                    ? asset('storage/' . $message->sender->avatar)
                                    : asset('assets/img/avatars/default-avatar.png');
                            @endphp
                            <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset($randomAvatar)}}" alt="Avatar" class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-0"><a href="{{ route('messages.show', $message->id) }}">{{ $message->subject }}</a></h6>
                                <small>{{ Str::limit($message->body, 50) }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-end">
                        <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                        <div class="btn-group ms-2">
                            <button class="btn btn-sm btn-outline-primary"><i class="ri-mail-line"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">No important messages found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
