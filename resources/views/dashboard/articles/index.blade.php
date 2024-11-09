<?php

use Illuminate\Support\Facades\Storage;
?>

@extends('layouts/layoutMaster')

@section('title', __('Articles Overview'))

@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">


<!-- عرض رسالة النجاح أو الفشل -->
@if(session('success'))

<div class="alert alert-success alert-dismissible" role="alert">
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
  </button>
</div>

@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible" role="alert">
  {{ session('error') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
  </button>
</div>


@endif


<div class="content-body">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header bg-light text-white d-flex justify-content-between align-items-center flex-column flex-md-row">
        <h4 class="mb-2 mb-md-0"><i class="ri-article-line me-2"></i>{{ __('Articles Overview') }}</h4>
        <div class="d-flex">
          <!-- Country selection dropdown -->
          <form method="GET" action="{{ route('articles.index') }}" class="d-flex">
            <select name="country" class="form-select me-2" onchange="this.form.submit()">
              <option value="jordan" {{ $country == 'jordan' ? 'selected' : '' }}>Jordan</option>
              <option value="saudi" {{ $country == 'saudi' ? 'selected' : '' }}>Saudi Arabia</option>
              <option value="egypt" {{ $country == 'egypt' ? 'selected' : '' }}>Egypt</option>
              <option value="palestine" {{ $country == 'palestine' ? 'selected' : '' }}>Palestine</option>
            </select>
          </form>
          <a href="{{ route('articles.create', ['country' => $country]) }}" class="btn btn-success">
            <i class="ri-add-line me-1"></i>{{ __('Add New Article') }}
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover table-sm">
            <thead class="bg-light">
              <tr>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Class') }}</th>
                <th>{{ __('Subject') }}</th>
                <th>{{ __('Semester') }}</th>
                <th>{{ __('Keywords') }}</th>
                <th class="text-center">{{ __('Actions') }}</th>
                <th>{{ __('File Category') }}</th>
                <th>{{ __('Download/View File') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($articles as $article)
              <tr>
                <td>{{ $article->title }}</td>
                <td>{{ $article->subject->schoolClass->grade_name }}</td>
                <td>{{ $article->subject->subject_name }}</td>
                <td>{{ $article->semester->semester_name }}</td>

                <td>
                  @if($article->keywords && $article->keywords->isNotEmpty())
                  {{ implode(',', $article->keywords->pluck('keyword')->toArray()) }}
                  @else
                  <span>No keywords available</span>
                  @endif
                </td>
                <td class="text-center">
                  <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-2">
                    <a href="{{ route('articles.show', ['article' => $article->id, 'country' => $country]) }}" class="btn btn-sm btn-outline-info" title="{{ __('View') }}">
                      <i class="ri-eye-line"></i>
                    </a>
                    <a href="{{ route('articles.edit', ['article' => $article->id, 'country' => $country]) }}" class="btn btn-sm btn-outline-warning" title="{{ __('Edit') }}">
                      <i class="ri-pencil-line"></i>
                    </a>
                    <form action="{{ route('articles.destroy', ['article' => $article->id, 'country' => $country]) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Delete') }}" onclick="return confirm('{{ __('Are you sure you want to delete this article?') }}');">
                        <i class="ri-delete-bin-line"></i>
                      </button>
                    </form>
                  </div>
                </td>
                <td>
                  @forelse ($article->files as $file)
                  <span class="badge bg-light text-dark">{{ $file->file_category }}</span>
                  @empty
                  <span>{{ __('Not Available') }}</span>
                  @endforelse
                </td>
                <td class="text-center">
                  @forelse ($article->files as $file)
                  <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-success" title="{{ __('View File') }}">
                    <i class="ri-eye-line"></i>
                  </a>
                  <a href="{{ Storage::url($file->file_path) }}" download class="btn btn-sm btn-outline-secondary" title="{{ __('Download File') }}">
                    <i class="ri-download-cloud-line"></i>
                  </a>
                  @empty
                  <span><i class="ri-file-info-line" title="{{ __('No files available') }}"></i></span>
                  @endforelse
                </td>
              </tr>
              @endforeach
            </tbody>
            <div class="pagination pagination-outline-secondary">
  {{ $articles->links('components.pagination.custom') }}
</div>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
