@extends('layouts/layoutMaster')

@section('title', __('view_class'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5>
                    <i class="ri-eye-line me-2"></i>{{ __('view_class') }}: {{ $class->grade_name }}
                </h5>
                <a href="{{ route('classes.index') }}" class="btn btn-light">
                    <i class="ri-arrow-go-back-line me-1"></i>{{ __('back_to_list') }}
                </a>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('name') }}</label>
                    <p>{{ $class->grade_name }}</p>
                    <hr>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('grade_level') }}</label>
                    <p>{{ $class->grade_level }}</p>
                    <hr>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-warning me-2">
                        <i class="ri-pencil-line me-1"></i>{{ __('edit') }}
                    </a>
                    <form action="{{ route('classes.destroy', $class->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="ri-delete-bin-7-line me-1"></i>{{ __('delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
