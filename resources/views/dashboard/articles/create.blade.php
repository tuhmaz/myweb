@extends('layouts/layoutMaster')
@section('title', __('Create New Article'))

@section('page-script')
@vite(['resources/assets/js/forms-editors.js', 'resources/assets/vendor/js/filter.js'])
@endsection

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base-url" content="{{ url('/') }}">
<meta name="upload-url" content="{{ route('upload.image') }}">



@if(session('success'))
<div class="alert alert-solid-success d-flex align-items-center" role="alert">
  <span class="alert-icon rounded">
    <i class="ti ti-check"></i>
  </span>
  {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  {{ session('error') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="content-body">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header bg-light text-white">
        <h4 class="mb-0"><i class="ri-article-line me-2"></i>{{ __('Create New Article') }}</h4>
      </div>
      <div class="card-body">
        <form id="articleForm" action="{{ route('articles.store', ['country' => $country]) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <!-- Title -->
          <div class="mb-3">
            <label for="title" class="form-label">{{ __('Title') }}</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required maxlength="60">
          </div>
          <!-- Content -->
          <div class="mb-3">
            <label for="content" class="form-label">{{ __('Content') }}</label>
            <textarea id="summernote" name="content" rows="15" class="form-control">{{ old('content') }}</textarea>
          </div>
          <!-- Keywords -->
          <div class="col-md-6 mb-6">
            <label for="keywords" class="form-label">{{ __('Keywords') }}</label>
            <input id="keywords" class="form-control" name="keywords" value="{{ old('keywords') }}" placeholder="{{ __('Enter keywords separated by commas') }}" />
            <small class="form-text text-muted">{{ __('Keywords will be converted to internal links if found in the content.') }}</small>
          </div>

          <!-- Meta Description (يمكن للمستخدم إدخالها أو سيتم توليدها تلقائيًا) -->
          <div class="mb-3">
            <label for="meta_description" class="form-label">{{ __('Meta Description') }}</label>
            <textarea class="form-control" id="meta_description" name="meta_description" maxlength="120">{{ old('meta_description') }}</textarea>
            <small class="form-text text-muted">{{ __('Leave empty to auto-generate from content or title/keywords.') }}</small>
          </div>

          <!-- خيار استخدام العنوان في Meta Description -->
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="use_title_for_meta" name="use_title_for_meta" value="1" {{ old('use_title_for_meta') ? 'checked' : '' }}>
            <label class="form-check-label" for="use_title_for_meta">
              {{ __('Use title as Meta Description') }}
            </label>
          </div>

          <!-- خيار استخدام الكلمات المفتاحية في Meta Description -->
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="use_keywords_for_meta" name="use_keywords_for_meta" value="1" {{ old('use_keywords_for_meta') ? 'checked' : '' }}>
            <label class="form-check-label" for="use_keywords_for_meta">
              {{ __('Use keywords as Meta Description') }}
            </label>
          </div>

          <!-- Class -->
          <div class="mb-3">
            <label for="class_id" class="form-label">{{ __('Class') }}</label>
            <select class="form-control" id="class_id" name="class_id">
              <option value="">{{ __('Select Class') }}</option>
              @foreach ($classes as $class)
              <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                {{ $class->grade_name }}
              </option>
              @endforeach
            </select>
          </div>
          <!-- Subject -->
          <div class="mb-3">
            <label for="subject_id" class="form-label">{{ __('Subject') }}</label>
            <select class="form-control" id="subject_id" name="subject_id">
              <option value="">{{ __('Select Subject') }}</option>
              @foreach ($subjects as $subject)
              <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }} class="subject-option" data-class="{{ $subject->schoolClass->id }}">
                {{ $subject->subject_name }}
              </option>
              @endforeach
            </select>
          </div>
          <!-- Semester -->
          <div class="mb-3">
            <label for="semester_id" class="form-label">{{ __('Semester') }}</label>
            <select class="form-control" id="semester_id" name="semester_id">
              <option value="">{{ __('Select Semester') }}</option>
              @foreach ($semesters as $semester)
              <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }} class="semester-option" data-class="{{ $semester->schoolClass->id }}">
                {{ $semester->semester_name }}
              </option>
              @endforeach
            </select>
          </div>
          <!-- File Upload -->
          <div class="mb-3">
            <label for="file" class="form-label">{{ __('File Upload') }}</label>
            <input type="file" class="form-control" id="file" name="file">
          </div>
          <!-- File Category -->
          <div class="mb-3">
            <label for="file_category" class="form-label">{{ __('File Category') }}</label>
            <select class="form-control" id="file_category" name="file_category" required>
              <option value="">{{ __('Select Category') }}</option>
              <option value="plans">{{ __('Plans') }}</option>
              <option value="papers">{{ __('Papers') }}</option>
              <option value="tests">{{ __('Tests') }}</option>
              <option value="books">{{ __('Books') }}</option>
            </select>
          </div>
          <!-- Submit -->
          <button type="submit" class="btn btn-success"><i class="ri-save-line me-1"></i>{{ __('Submit') }}</button>
          <a href="{{ route('articles.index', ['country' => $country]) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('#keywords').tagsinput({
      confirmKeys: [13, 44] // Enter or comma to confirm the tag
    });
  });
</script>
@endsection
