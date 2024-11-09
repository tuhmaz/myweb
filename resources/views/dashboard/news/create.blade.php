@extends('layouts/layoutMaster')

@section('title', __('Create News'))
@section('page-script')
@vite(['resources/assets/js/forms-editors.js'])
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base-url" content="{{ url('/') }}">
<meta name="upload-url" content="{{ route('upload.image') }}">

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">


<div class="content-body">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header bg-light text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="ri-pencil-line me-2"></i>{{ __('Create News') }}
        </h5>
        <a href="{{ route('news.index', ['country' => request('country', 'jordan')]) }}" class="btn btn-light btn-sm">
          <i class="ri-arrow-go-back-line me-1"></i>{{ __('Back to List') }}
        </a>
      </div>
      <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <form action="{{ route('news.store', ['country' => request('country', 'jordan')]) }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="mb-3">
            <label for="country" class="form-label">{{ __('Country') }}</label>
            <select name="country" class="form-control">
              <option value="jordan" {{ request('country') == 'jordan' ? 'selected' : '' }}>Jordan</option>
              <option value="saudi" {{ request('country') == 'saudi' ? 'selected' : '' }}>Saudi Arabia</option>
              <option value="egypt" {{ request('country') == 'egypt' ? 'selected' : '' }}>Egypt</option>
              <option value="palestine" {{ request('country') == 'palestine' ? 'selected' : '' }}>Palestine</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="title" class="form-label">{{ __('Title') }}</label>
            <input type="text" name="title" class="form-control" placeholder="{{ __('Title') }}" required>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <textarea id="summernote" name="description" rows="15" class="form-control"></textarea>
          </div>

          <div class="mb-3">
            <label for="category_id" class="form-label">{{ __('Category') }}</label>
            <select name="category_id" class="form-control" required>
              @foreach ($categories as $id => $name)
              <option value="{{ $id }}">{{ $name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="image" class="form-label">{{ __('Image') }}</label>
            <input type="file" name="image" class="form-control">
          </div>

          <div class="mb-3">
            <label for="meta_description" class="form-label">{{ __('Meta Description') }}</label>
            <input type="text" name="meta_description" class="form-control" id="meta_description" maxlength="60" value="{{ old('meta_description') }}">
            <small class="form-text text-muted">{{ __('Leave empty to auto-generate from content or title/keywords.') }}</small>
          </div>

          <div class="col-md-6 mb-6">
            <label for="keywords" class="form-label">{{ __('Keywords') }}</label>
            <input id="keywords" class="form-control" name="keywords" value="{{ old('keywords') }}" placeholder="{{ __('Enter keywords separated by commas') }}" />
            <small class="form-text text-muted">{{ __('Keywords will be converted to internal links if found in the content.') }}</small>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="use_title_for_meta" name="use_title_for_meta" value="1" {{ old('use_title_for_meta') ? 'checked' : '' }}>
            <label class="form-check-label" for="use_title_for_meta">
              {{ __('Use title as Meta Description') }}
            </label>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="use_keywords_for_meta" name="use_keywords_for_meta" value="1" {{ old('use_keywords_for_meta') ? 'checked' : '' }}>
            <label class="form-check-label" for="use_keywords_for_meta">
              {{ __('Use keywords as Meta Description') }}
            </label>
          </div>
          <button type="submit" class="btn btn-success">
            <i class="ri-save-line me-1"></i>{{ __('Submit') }}
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
 document.addEventListener('DOMContentLoaded', function() {
  const inputElement = document.getElementById('keywords');

  inputElement.addEventListener('keydown', function(event) {
    // Check if Enter (13) or Comma (44) is pressed
    if (event.keyCode === 13 || event.keyCode === 44) {
      const value = inputElement.value.trim();
      if (value) {
        // Logic to add tag goes here
        console.log("Tag confirmed:", value);
        inputElement.value = '';  // Clear the input
      }
      event.preventDefault(); // Prevent the default behavior (e.g., form submission)
    }
  });
});

</script>
@endsection
