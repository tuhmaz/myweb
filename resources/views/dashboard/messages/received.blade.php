@extends('layouts/layoutMaster')

@section('title', 'Email Inbox')

@section('content')
<div class="email-right-box ms-0 ms-sm-4 ms-sm-0">
    <div role="toolbar" class="toolbar ms-1 ms-sm-0">
        <div class="btn-group mb-1">
            <div class="form-check custom-checkbox pl-2">
                <input type="checkbox" class="form-check-input" id="checkAll">
                <label class="form-check-label" for="checkAll"></label>
            </div>
        </div>
        <div class="btn-group mb-1">
            <button class="btn btn-primary light px-3" type="button"><i class="ti-reload"></i></button>
        </div>
        <div class="btn-group mb-1">
            <button aria-expanded="false" data-bs-toggle="dropdown" class="btn btn-primary px-3 light dropdown-toggle" type="button">More <span class="caret"></span>
            </button>
            <div class="dropdown-menu">
                <a href="javascript:void(0);" class="dropdown-item">Mark as Unread</a>
                <a href="javascript:void(0);" class="dropdown-item">Add to Tasks</a>
                <a href="javascript:void(0);" class="dropdown-item">Add Star</a>
                <a href="javascript:void(0);" class="dropdown-item">Mute</a>
            </div>
        </div>
    </div>

    <!-- List of emails will be loaded here -->
    <div class="email-list mt-4">
        @foreach ($messages as $message)
        <div class="email-list-item">
            <div class="d-flex align-items-center">
                <div class="form-check custom-checkbox">
                    <input type="checkbox" class="form-check-input" id="checkbox{{ $message->id }}">
                    <label class="form-check-label" for="checkbox{{ $message->id }}"></label>
                </div>
                <div class="email-list-content flex-grow-1">
                    <a href="{{ route('messages.show', $message->id) }}">
                        <h5 class="mb-0">{{ $message->subject }}</h5>
                        <p class="mb-0">{{ Str::limit($message->body, 50) }}</p>
                    </a>
                </div>
                <div class="email-list-time">
                    {{ $message->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12 pl-3">
            <nav>
                <ul class="pagination pagination-gutter pagination-primary pagination-sm no-bg">
                    <li class="page-item page-indicator"><a class="page-link" href="javascript:void(0);"><i class="la la-angle-left"></i></a></li>
                    <li class="page-item"><a class="page-link" href="javascript:void(0);">1</a></li>
                    <li class="page-item active"><a class="page-link" href="javascript:void(0);">2</a></li>
                    <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                    <li class="page-item"><a class="page-link" href="javascript:void(0);">4</a></li>
                    <li class="page-item page-indicator"><a class="page-link" href="javascript:void(0);"><i class="la la-angle-right"></i></a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
