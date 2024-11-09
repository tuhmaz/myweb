<?php
use Illuminate\Support\Facades\Storage;
?>
@extends('layouts/layoutMaster')

@section('title', __('File Details'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="ri-file-info-line me-2"></i>{{ __('File Details') }}</h4>
                        <!-- زر الرجوع -->
                        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                        <i class="ri-arrow-go-back-line me-1"></i> {{ __('Back') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <h5>{{ $file->article->title ?? __('No Associated Article') }}</h5>
                        <p><strong>{{ __('File Name') }}:</strong> {{ $file->file_name }}</p>
                        <p><strong>{{ __('File Type') }}:</strong> {{ $file->file_type }}</p>

                        <!-- رابط التحميل -->
                        <a href="{{ Storage::url($file->file_path) }}" class="btn btn-success mb-3">
                            <i class="ri-download-cloud-line me-1"></i>{{ __('Download') }}
                        </a>

                        <!-- عرض ملف PDF أو DOC -->
                        @if(in_array($file->file_type, ['pdf', 'doc', 'docx']))
                        <div class="mt-3">
                            <iframe src="{{ Storage::url($file->file_path) }}" width="100%" height="500px">
                                {{ __('Your browser does not support this feature.') }} <a href="{{ Storage::url($file->file_path) }}">{{ __('Download PDF') }}</a>
                            </iframe>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
