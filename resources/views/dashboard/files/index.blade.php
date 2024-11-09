<?php

use Illuminate\Support\Facades\Storage;
?>

@extends('layouts/layoutMaster')

@section('title', __('Files Management'))

@section('content')

<div class="content-body">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h4 class="mb-0">
          <i class="ri-folder-line me-2"></i>{{ __('Files Management') }}
        </h4>
        <form method="GET" action="{{ route('files.index') }}" class="d-flex align-items-center mt-2 mt-md-0">
          <select name="country" class="form-select me-2" onchange="this.form.submit()">
            <option value="jordan" {{ request('country') == 'jordan' ? 'selected' : '' }}>Jordan</option>
            <option value="saudi" {{ request('country') == 'saudi' ? 'selected' : '' }}>Saudi Arabia</option>
            <option value="egypt" {{ request('country') == 'egypt' ? 'selected' : '' }}>Egypt</option>
            <option value="palestine" {{ request('country') == 'palestine' ? 'selected' : '' }}>Palestine</option>
          </select>
        </form>
        <a href="{{ route('files.create', ['country' => request('country', 'jordan')]) }}" class="btn btn-success mt-2 mt-md-0">
          <i class="ri-upload-line me-1"></i>{{ __('Upload New File') }}
        </a>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead class="bg-light text-white">
              <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Associated Article') }}</th>
                <th>{{ __('File Name') }}</th>
                <th>{{ __('File Type') }}</th>
                <th class="text-center">{{ __('Actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($files as $file)
              <tr>
                <td>{{ $file->id }}</td>
                <td>{{ $file->article->title ?? __('No Associated Article') }}</td>
                <td>{{ basename($file->file_Name) }}</td>
                <td>{{ $file->file_type }}</td>
                <td class="text-center">
                  <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="{{ route('files.show', $file->id) }}" class="btn btn-sm btn-outline-info">
                      <i class="ri-eye-line me-1"></i>{{ __('View') }}
                    </a>
                    <a href="{{ route('files.edit', ['file' => $file->id, 'country' => request('country')]) }}" class="btn btn-sm btn-outline-warning">
                      <i class="ri-pencil-line me-1"></i>{{ __('Edit') }}
                    </a>
                    <form action="{{ route('files.destroy', $file->id) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('Are you sure?') }}');">
                        <i class="ri-delete-bin-line me-1"></i>{{ __('Delete') }}
                      </button>
                    </form>
                    <a href="{{ Storage::url($file->file_path) }}" class="btn btn-sm btn-outline-success">
                      <i class="ri-download-cloud-line me-1"></i>{{ __('Download') }}
                    </a>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        @if($files->isEmpty())
        <div class="text-center py-4">
          <h5>{{ __('No files found for the selected country.') }}</h5>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
