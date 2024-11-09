@extends('layouts/layoutMaster')

@section('title', __('add_new_class'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>
                    <i class="ri-add-line me-2"></i>{{ __('add_new_class') }}
                </h5>
                <a href="{{ route('classes.index') }}" class="btn btn-primary">
                    <i class="ri-arrow-go-back-line me-1"></i>{{ __('back_to_list') }}
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

                <form action="{{ route('classes.store') }}" method="POST">
                    @csrf
                    <!-- قائمة منسدلة لاختيار الدولة -->
<div class="form-group mb-3">
    <label for="country">Select Country</label>
    <select class="form-control" id="country" name="country" required>
        <option value="jordan">Jordan (Main Database)</option>  
        <option value="saudi">Saudi Arabia</option>
        <option value="egypt">Egypt</option>
        <option value="palestine">Palestine</option>
    </select>
</div>

                    <div class="mb-3">
                        <label for="grade_name" class="form-label">{{ __('name') }}</label>
                        <input type="text" class="form-control" id="grade_name" name="grade_name" value="{{ old('grade_name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="grade_level" class="form-label">{{ __('grade_level') }}</label>
                        <input type="text" class="form-control" id="grade_level" name="grade_level" value="{{ old('grade_level') }}" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <i class="ri-save-line me-1"></i>{{ __('save_class') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
