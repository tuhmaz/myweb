<?php
use Illuminate\Support\Facades\Storage;
?>
@extends('layouts/layoutMaster')

@section('content')

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">{{ $article->title }}</h4>
                        <!-- زر الرجوع -->
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                    <div class="card-body">

                        <!-- عرض meta_description للتأكد -->
                        <div class="mb-4">
                            <h5><strong>{{ __('Meta Description') }}:</strong></h5>
                            <p>{{ $article->meta_description ?? 'لا يوجد وصف ميتا' }}</p>
                        </div>

                        <div class="mb-4">
                            <h5><strong>{{ __('content') }}:</strong></h5>
                            <div class="card-text">{!! $article->content !!}  </div> <!-- تمكين HTML -->
                        </div>
                        

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>{{ __('keywords') }}:</strong> {{ implode(',', $article->keywords->pluck('keyword')->toArray()) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>{{ __('class') }}:</strong> {{ $article->subject->schoolClass->grade_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>{{ __('subject') }}:</strong> {{ $article->subject->subject_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>{{ __('semester') }}:</strong> {{ $article->semester->semester_name }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5><strong>{{ __('files') }}:</strong></h5>
                            @forelse ($article->files as $file)
                                <div class="mb-2 d-flex align-items-center">
                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-success btn-sm me-2">
                                        <i class="fas fa-eye"></i> {{ __('view') }}
                                    </a>
                                    <a href="{{ Storage::url($file->file_path) }}" download class="btn btn-secondary btn-sm">
                                        <i class="fas fa-download"></i> {{ __('download') }}
                                    </a>
                                </div>
                            @empty
                                <span>{{ __('no_file_available') }}</span>
                            @endforelse
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-warning flex-fill me-2">
                                <i class="fas fa-edit"></i> {{ __('edit') }}
                            </a>
                            <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="d-inline flex-fill ms-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('هل أنت متأكد من الحذف؟');">
                                    <i class="fas fa-trash-alt"></i> {{ __('delete') }}
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
