@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Email Inbox')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <button class="btn btn-primary w-100 mb-4" data-bs-toggle="modal" data-bs-target="#emailComposeSidebar">
                        <i class="ri-edit-line"></i> Compose
                    </button>
                    <div class="list-group">
                        <a href="{{ route('messages.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <i class="ri-mail-line me-2"></i> Inbox
                            <span class="badge bg-primary rounded-pill">{{ $unreadMessagesCount }}</span>
                        </a>
                        <a href="{{ route('messages.sent') }}" class="list-group-item list-group-item-action">
                            <i class="ri-send-plane-line me-2"></i> Sent
                        </a>
                        <a href="{{ route('messages.drafts') }}" class="list-group-item list-group-item-action">
                            <i class="ri-edit-2-line me-2"></i> Draft
                        </a>
                        <a href="{{ route('messages.important') }}" class="list-group-item list-group-item-action">
                            <i class="ri-star-line me-2"></i> Important
                        </a>
                        <a href="{{ route('messages.trash') }}" class="list-group-item list-group-item-action">
                            <i class="ri-delete-bin-6-line me-2"></i> Trash
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-4">
                        <h5>Inbox</h5>
                        <div class="input-group w-50">
                            <input type="text" class="form-control" placeholder="Search mail">
                            <button class="btn btn-outline-secondary" type="button"><i class="ri-search-line"></i></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach($messages as $message)
                                    <tr class="{{ $message->read ? '' : 'bg-light' }}">
                                        <td>
                                            <a href="{{ route('messages.show', $message->id) }}" class="d-flex align-items-center text-decoration-none text-dark">
                                                @php
                                                    $senderAvatarPath = $message->sender->avatar && Storage::exists('public/' . $message->sender->avatar)
                                                        ? asset('storage/' . $message->sender->avatar)
                                                        : asset('assets/img/avatars/default-avatar.png');
                                                @endphp
                                                <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset($randomAvatar) }}" alt="Avatar" class="rounded-circle me-3" width="50" height="50">

                                                <div>
                                                    <h6 class="mb-0">{{ $message->sender->name }}</h6>
                                                    <small>{{ $message->subject }}</small>
                                                </div>
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            <small>{{ $message->created_at->format('h:i A') }}</small>
                                            <div class="btn-group">
                                                <form action="{{ route('messages.markAsRead', $message->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary"><i class="ri-mail-open-line"></i></button>
                                                </form>
                                                <form action="{{ route('messages.toggleImportant', $message->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning"><i class="{{ $message->is_important ? 'ri-star-fill' : 'ri-star-line' }}"></i></button>
                                                </form>
                                                <form action="{{ route('messages.delete', $message->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compose Email Modal -->
    <div class="modal fade" id="emailComposeSidebar" tabindex="-1" aria-labelledby="emailComposeSidebarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailComposeSidebarLabel">Compose Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('messages.send') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient" class="form-label">To:</label>
                            <select class="form-select" id="recipient" name="recipient">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject:</label>
                            <input type="text" class="form-control" id="subject" name="subject">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message:</label>
                            <textarea class="form-control" id="message" rows="5" name="message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="ri-send-plane-line"></i> Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
