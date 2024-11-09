@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Notifications'))

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="container mt-4">
    <div class="card">
        <h5 class="card-header">{{ __('Notifications') }}</h5>
        <div class="card-body">
            @if ($notifications->isEmpty())
                <div class="alert alert-info text-center">
                    {{ __('No notifications found.') }}
                </div>
            @else
                <form id="notification-actions-form" method="POST" action="{{ route('notifications.handleActions') }}">
                    @csrf

                    <ul class="list-group">
                        @foreach ($notifications as $notification)
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap {{ is_null($notification->read_at) ? 'list-group-item-warning' : '' }}">
                                <div class="d-flex flex-column">
                                    <input type="checkbox" name="selected_notifications[]" value="{{ $notification->id }}" class="form-check-input me-2 mb-2">

                                    {{-- عرض نوع الإشعار بناءً على المحتوى --}}
                                    @if (isset($notification->data['role']) || isset($notification->data['permission']))
                                        <strong>{{ __('Role/Permission Notification:') }}</strong>
                                        @if (isset($notification->data['role']))
                                            {{ __('Role:') }} {{ $notification->data['role'] }}
                                        @endif
                                        @if (isset($notification->data['permission']))
                                            {{ __('Permission:') }} {{ $notification->data['permission'] }}
                                        @endif
                                    @elseif (isset($notification->data['article_id']))
                                        <strong>{{ __('Article Notification:') }}</strong>
                                        <a href="{{ $notification->data['url'] }}">{{ $notification->data['title'] }}</a>
                                    @elseif (isset($notification->data['message_id']))
                                        <strong>{{ __('Message Notification:') }}</strong>
                                        <a href="{{ $notification->data['url'] }}">{{ $notification->data['title'] }}</a>
                                    @endif

                                    <small class="text-muted mt-2">{{ __('Received :time', ['time' => $notification->created_at->diffForHumans()]) }}</small>
                                </div>
                                <div class="text-end mt-2 mt-md-0">
                                    @if (is_null($notification->read_at))
                                    <button type="button" onclick="handleMarkAsRead('{{ route('notifications.markAsRead', $notification->id) }}');" class="btn btn-sm btn-primary">{{ __('Mark as Read') }}</button>

                                    @else
                                        <span class="badge bg-success">{{ __('Read') }}</span>
                                    @endif
                                    <button type="button" onclick="handleAction('{{ route('notifications.delete', $notification->id) }}');" class="btn btn-sm btn-danger ms-2">{{ __('Delete') }}</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-3 d-flex justify-content-between flex-wrap">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" id="select-all" class="form-check-input me-2">
                            <label for="select-all">{{ __('Select All') }}</label>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm">{{ __('Delete Selected') }}</button>
                            <button type="submit" name="action" value="mark-as-read" class="btn btn-sm btn-primary">{{ __('Mark All as Read') }}</button>
                        </div>
                    </div>
                </form>
            @endif

            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination pagination-rounded pagination-outline-primary justify-content-center">
                    {{-- Link to the first page --}}
                    @if ($notifications->onFirstPage())
                        <li class="page-item first disabled">
                            <span class="page-link" aria-hidden="true"><i class="ti ti-chevrons-left ti-sm"></i></span>
                        </li>
                    @else
                        <li class="page-item first">
                            <a class="page-link" href="{{ $notifications->url(1) }}" aria-label="First">
                                <i class="ti ti-chevrons-left ti-sm"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Link to the previous page --}}
                    @if ($notifications->onFirstPage())
                        <li class="page-item prev disabled">
                            <span class="page-link" aria-hidden="true"><i class="ti ti-chevron-left ti-sm"></i></span>
                        </li>
                    @else
                        <li class="page-item prev">
                            <a class="page-link" href="{{ $notifications->previousPageUrl() }}" aria-label="Previous">
                                <i class="ti ti-chevron-left ti-sm"></i></a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
                        @if ($page == $notifications->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Link to the next page --}}
                    @if ($notifications->hasMorePages())
                        <li class="page-item next">
                            <a class="page-link" href="{{ $notifications->nextPageUrl() }}" aria-label="Next">
                                <i class="ti ti-chevron-right ti-sm"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item next disabled">
                            <span class="page-link" aria-hidden="true"><i class="ti ti-chevron-right ti-sm"></i></span>
                        </li>
                    @endif

                    {{-- Link to the last page --}}
                    @if ($notifications->hasMorePages())
                        <li class="page-item last">
                            <a class="page-link" href="{{ $notifications->url($notifications->lastPage()) }}" aria-label="Last">
                                <i class="ti ti-chevrons-right ti-sm"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item last disabled">
                            <span class="page-link" aria-hidden="true"><i class="ti ti-chevrons-right ti-sm"></i></span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
    document.getElementById('select-all').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('input[name="selected_notifications[]"]');
        checkboxes.forEach((checkbox) => {
            checkbox.checked = this.checked;
        });
    });

    function handleAction(url) {
        const form = document.getElementById('notification-actions-form');
        form.action = url;
        form.submit();
    }
    function handleMarkAsRead(url) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;

    const token = document.createElement('input');
    token.type = 'hidden';
    token.name = '_token';
    token.value = '{{ csrf_token() }}'; // توكن CSRF

    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'PATCH'; // استخدام طريقة PATCH

    form.appendChild(token);
    form.appendChild(method);

    document.body.appendChild(form);
    form.submit();
}

</script>
@endsection
