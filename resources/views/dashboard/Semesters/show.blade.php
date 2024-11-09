@extends('layouts/layoutMaster')

@section('title', __('View Semester'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="ri-eye-line me-2"></i>{{ __('View Semester') }}</h4>
                <a href="{{ route('semesters.index') }}" class="btn btn-light btn-sm">
                    <i class="ri-arrow-go-back-line me-1"></i>{{ __('Back to List') }}
                </a>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('Semester Name') }}:</label>
                    <p class="form-control-plaintext border p-2 rounded">{{ $semester->semester_name }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('Grade Level') }}:</label>
                    <p class="form-control-plaintext border p-2 rounded">{{ $semester->schoolClass->grade_name }}</p>
                </div>
                <div class="d-flex justify-content-start">
                    <a href="{{ route('semesters.edit', $semester->id) }}" class="btn btn-warning btn-sm me-2">
                        <i class="ri-pencil-line me-1"></i>{{ __('Edit Semester') }}
                    </a>
                    <form action="{{ route('semesters.destroy', $semester->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="ri-delete-bin-7-line me-1"></i>{{ __('Delete Semester') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
