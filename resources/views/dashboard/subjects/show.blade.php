@extends('layouts/layoutMaster')

@section('title', __('view_subject'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h5 class="mb-2 mb-md-0">
                    <i class="ri-eye-line me-2"></i>{{ __('view_subject') }}
                </h5>
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
                    <a href="{{ route('subjects.index') }}" class="btn btn-light btn-sm mb-2 mb-md-0 me-0 me-md-2">
                        <i class="ri-arrow-go-back-line me-1"></i>{{ __('back_to_list') }}
                    </a>
                    <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-warning btn-sm mb-2 mb-md-0 me-0 me-md-2">
                        <i class="ri-pencil-line me-1"></i>{{ __('edit_subject') }}
                    </a>
                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('{{ __('are_you_sure') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="ri-delete-bin-7-line me-1"></i>{{ __('delete_subject') }}
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('subject_name') }}:</label>
                            <p class="form-control-plaintext border p-2 rounded">{{ $subject->subject_name }}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('grade_level') }}:</label>
                            <p class="form-control-plaintext border p-2 rounded">{{ $subject->schoolClass->grade_name }}</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center justify-content-md-end">
                    <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                        <i class="ri-arrow-go-back-line me-1"></i>{{ __('back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
