@extends('layouts/layoutMaster')

@section('title', 'Compose Message')

@section('content')
<div class="email-right-box ms-0 ms-sm-4 ms-sm-0">

    <div class="compose-content">
        <form action="{{ route('messages.send') }}" method="POST">
            @csrf
            <div class="mb-4">
                <select id="single-select" name="recipient" class="form-control">
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="subject" class="form-control bg-transparent" placeholder="Subject">
            </div>
            <div class="form-group">
                <textarea id="email-compose-editor" name="message" class="textarea_editor form-control bg-transparent" rows="15" placeholder="Enter text ..."></textarea>
            </div>
            <div class="text-start mt-4 mb-2">
                <button class="btn btn-primary btn-sl-sm me-2" type="submit"><span class="me-2"><i class="fa fa-paper-plane"></i></span>Send</button>
                <button class="btn btn-danger light btn-sl-sm" type="reset"><span class="me-2"><i class="fa fa-times" aria-hidden="true"></i></span>Discard</button>
            </div>
        </form>
    </div>
</div>

@endsection
