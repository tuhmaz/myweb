<?php
use Illuminate\Support\Facades\Storage;
?>
@extends('layouts/layoutMaster')

@section('page-script')
@vite(['resources/assets/js/forms-editors.js',
'resources/assets/vendor/js/filter.js'])
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base-url" content="{{ url('/') }}">
<meta name="upload-url" content="{{ route('upload.image') }}">
<div class="content-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-pencil-alt"></i> {{ __('edit_article') }}
                </h5>
                <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left"></i> رجوع
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('articles.update', ['article' => $article->id, 'country' => $country])  }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('title') }}</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $article->title }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">{{ __('content') }}</label>
                        <textarea class="form-control" id="summernote" name="content" rows="6" required>{{ $article->content }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="keywords" class="form-label">{{ __('keywords') }}</label>
                        <input type="text" class="form-control" id="keywords" name="keywords" value="{{ implode(',', $article->keywords->pluck('keyword')->toArray()) }}" placeholder="{{ __('enter_keywords') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="class_id" class="form-label">{{ __('class') }}</label>
                            <select class="form-control" id="class_id" name="class_id">
                                <option value="">{{ __('select_class') }}</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" {{ $article->subject->schoolClass->id == $class->id ? 'selected' : '' }}>{{ $class->grade_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="subject_id" class="form-label">{{ __('subject') }}</label>
                            <select class="form-control" id="subject_id" name="subject_id">
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ $article->subject_id == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->subject_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="semester_id" class="form-label">{{ __('semester') }}</label>
                            <select class="form-control" id="semester_id" name="semester_id">
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ $article->semester_id == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->semester_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label">{{ __('current_file') }}</label>
                        @forelse ($article->files as $file)
                            <div class="current-file mb-2">
                                <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ basename($file->file_path) }}</a>
                            </div>
                        @empty
                            <p>{{ __('no_file_uploaded') }}</p>
                        @endforelse
                    </div>

                    <div class="mb-3">
                        <label for="new_file" class="form-label">{{ __('upload_new_file') }}</label>
                        <input type="file" class="form-control form-control-sm" id="new_file" name="new_file">
                    </div>

                    <div class="mb-3">
                        <label for="file_category" class="form-label">{{ __('file_category') }}</label>
                        <select class="form-control" id="file_category" name="file_category" required>
                            <option value="">{{ __('select_category') }}</option>
                            @if ($article->files->isNotEmpty())
                                @php $file = $article->files->first(); @endphp
                                <option value="plans" {{ $file->file_category == 'plans' ? 'selected' : '' }}>{{ __('plans') }}</option>
                                <option value="papers" {{ $file->file_category == 'papers' ? 'selected' : '' }}>{{ __('papers') }}</option>
                                <option value="tests" {{ $file->file_category == 'tests' ? 'selected' : '' }}>{{ __('tests') }}</option>
                                <option value="books" {{ $file->file_category == 'books' ? 'selected' : '' }}>{{ __('books') }}</option>
                            @else
                                <option value="plans">{{ __('plans') }}</option>
                                <option value="papers">{{ __('papers') }}</option>
                                <option value="tests">{{ __('tests') }}</option>
                                <option value="books">{{ __('books') }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
