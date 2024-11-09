@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'View Message')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="right-box-padding">
            <div class="read-content">
                <div class="media pt-3 d-sm-flex d-block justify-content-between">
                    <div class="clearfix mb-3 d-flex">
                        <img class="me-3 rounded" width="50" height="50" alt="image" src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset($randomAvatar)}}">
                        <div class="media-body me-2">
                            <h5 class="text-primary mb-0 mt-1">{{ $message->subject }}</h5>
                            <p class="mb-0">{{ $message->created_at }}</p>
                        </div>
                    </div>
                    <div class="clearfix mb-3">
                        <a href="{{ route('messages.index') }}" class="btn btn-primary px-3 my-2 light"><i class="fa fa-reply"></i></a>
                        <form action="{{ route('messages.delete', $message->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-primary px-3 my-2 light" type="submit"><i class="fa fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <hr>

                <div class="read-content-body">
                    {{ $message->body }}
                </div>

                <hr>
                <form action="{{ route('messages.reply', $message->id) }}" method="POST">
                    @csrf
                    <div class="form-group pt-3">
                        <textarea name="reply_body" id="write-email" cols="30" rows="5" class="form-control" placeholder="Write your reply..."></textarea>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-primary" type="submit">Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
