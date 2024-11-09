@extends('layouts/layoutMaster')

@section('title', __('Add New Semester'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="ri-add-line me-2"></i>{{ __('Add New Semester') }}</h4>
                <a href="{{ route('semesters.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-go-back-line me-1"></i>{{ __('Back to List') }}
                </a>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('semesters.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="semester_name" class="form-label">{{ __('Semester Name') }}</label>
                        <input type="text" class="form-control" id="semester_name" name="semester_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="grade_level" class="form-label">{{ __('Grade Level') }}</label>
                        <select class="form-select" id="grade_level" name="grade_level" required>
                            <option value="">{{ __('Select Grade Level') }}</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->grade_level }}">{{ $class->grade_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="ri-save-line me-1"></i>{{ __('Save') }}
                        </button>
                        <a href="{{ route('semesters.index') }}" class="btn btn-secondary">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
