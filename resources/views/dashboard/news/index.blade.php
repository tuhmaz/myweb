<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
?>

@extends('layouts/layoutMaster')

@section('title', __('News Overview'))

@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="content-body">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header bg-light text-white d-flex justify-content-between align-items-center flex-column flex-md-row">
        <h4 class="mb-2 mb-md-0"><i class="ri-newspaper-line me-2"></i>{{ __('News Overview') }}</h4>
        <div class="d-flex">
          <form method="GET" action="{{ route('news.index') }}" class="d-flex">
            <select name="country" class="form-select me-2" onchange="this.form.submit()">
              <option value="jordan" {{ $country == 'jordan' ? 'selected' : '' }}>Jordan</option>
              <option value="saudi" {{ $country == 'saudi' ? 'selected' : '' }}>Saudi Arabia</option>
              <option value="egypt" {{ $country == 'egypt' ? 'selected' : '' }}>Egypt</option>
              <option value="palestine" {{ $country == 'palestine' ? 'selected' : '' }}>Palestine</option>
            </select>

            <select name="category" class="form-select me-2" onchange="this.form.submit()">
              <option value="">{{ __('All Categories') }}</option>
              @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
              @endforeach
            </select>


          </form>

          <!-- زر إضافة خبر جديد -->
          <a href="{{ route('news.create', ['country' => $country]) }}" class="btn btn-success">
            <i class="ri-add-line me-1"></i>{{ __('Add News') }}
          </a>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover table-sm">
            <thead class="bg-light">
              <tr>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Keywords') }}</th>
                <th>{{ __('Meta Description') }}</th>
                <th class="text-center">{{ __('Actions') }}</th>
                <th>{{ __('Image') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($news as $newsItem)
              <tr>
                <td>{{ $newsItem->title }}</td>
                <td>{{ $newsItem->category->name ?? 'N/A' }}</td>
                <td> {{ implode(',', $newsItem->keywords->pluck('keyword')->toArray()) }}</td>

                <td>{{ Str::limit($newsItem->meta_description, 25) }}</td>
                <td class="text-center">
                  <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-2">
                    <a href="{{ route('news.show', ['news' => $newsItem->id, 'country' => $country]) }}" class="btn btn-sm btn-outline-info" title="{{ __('View') }}">
                      <i class="ri-eye-line"></i>
                    </a>
                    <a href="{{ route('news.edit', ['news' => $newsItem->id, 'country' => $country]) }}" class="btn btn-sm btn-outline-warning" title="{{ __('Edit') }}">
                      <i class="ri-pencil-line"></i>
                    </a>
                    <form action="{{ route('news.destroy', ['news' => $newsItem->id, 'country' => $country]) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Delete') }}" onclick="return confirm('{{ __('Are you sure you want to delete this news?') }}');">
                        <i class="ri-delete-bin-line"></i>
                      </button>
                    </form>
                  </div>
                </td>
                <td class="text-center">
                  @if($newsItem->image && Storage::exists('public/images/'.$newsItem->image))
                  <a href="{{ Storage::url('public/images/'.$newsItem->image) }}" target="_blank" class="btn btn-sm btn-outline-success" title="{{ __('View Image') }}">
                    <i class="ri-image-line"></i>
                  </a>
                  <a href="{{ Storage::url('public/images/'.$newsItem->image) }}" download class="btn btn-sm btn-outline-secondary" title="{{ __('Download Image') }}">
                    <i class="ri-download-cloud-line"></i>
                  </a>
                  @else
                  <span><i class="ri-image-line" title="{{ __('No image available') }}"></i></span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="pagination pagination-outline-secondary">
          {{ $news->links('components.pagination.custom') }}
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
