<?php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
?>

@extends('layouts/layoutMaster')

@section('title', __('View News'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-eye"></i> {{ $news->title }}
                        </h4>
                        <!-- زر الرجوع مع إضافة الدولة -->
                        <a href="{{ route('news.index', ['country' => request('country', 'jordan')]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> {{ __('Back to List') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5><strong>{{ __('Description') }}:</strong></h5>
                            <div class="card-text">{!! $news->description !!}</div> <!-- تمكين HTML -->
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                            <p><strong>{{ __('Category') }}:</strong> {{ $news->category->name }}</p>

                            </div>
                            <div class="col-md-6">
                                <p><strong>{{ __('Meta Description') }}:</strong> {{ $news->meta_description }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>{{ __('Keywords') }}:</strong>
                              {{ implode(',', $news->keywords->pluck('keyword')->toArray()) }}</p>
                            </div>
                        </div>

                        @if ($news->image != 'noimage.svg')
                            <div class="mb-4">
                                <h5><strong>{{ __('Image') }}:</strong></h5>
                                <div class="text-center">
                                    <img src="{{ Storage::url('images/' . $news->image) }}" alt="{{ $news->alt }}" class="img-thumbnail img-fluid rounded" style="max-width: 100%; height: auto;">
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            <!-- تعديل الرابط لإضافة الدولة -->
                            <a href="{{ route('news.edit', ['news' => $news->id, 'country' => request('country', 'jordan')]) }}" class="btn btn-warning flex-fill me-2">
                                <i class="fas fa-edit"></i> {{ __('Edit') }}
                            </a>
                            <form action="{{ route('news.destroy', ['news' => $news->id, 'country' => request('country', 'jordan')]) }}" method="POST" class="d-inline flex-fill ms-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('{{ __('Are you sure you want to delete this news?') }}');">
                                    <i class="fas fa-trash-alt"></i> {{ __('Delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
