@extends('layouts/layoutMaster')

@section('title', __('edit_class'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-light text-white d-flex justify-content-between align-items-center">
                <h5>
                    <i class="ri-pencil-line me-2"></i>{{ __('edit_class') }}
                </h5>
                <a href="{{ route('classes.index') }}" class="btn btn-primary">
                    <i class="ri-arrow-go-back-line me-1"></i>{{ __('back_to_list') }}
                </a>
            </div>
            <div class="card-body">
                <!-- عرض الأخطاء -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- نموذج تعديل الصف -->
                <form action="{{ route('classes.update', $class->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- إدخال اسم الصف -->
                    <div class="mb-3">
                        <label for="grade_name" class="form-label">{{ __('name') }}</label>
                        <input type="text" class="form-control" id="grade_name" name="grade_name" value="{{ old('grade_name', $class->grade_name) }}" required>
                    </div>

                    <!-- إدخال مستوى الصف -->
                    <div class="mb-3">
                        <label for="grade_level" class="form-label">{{ __('grade_level') }}</label>
                        <input type="text" class="form-control" id="grade_level" name="grade_level" value="{{ old('grade_level', $class->grade_level) }}" required>
                    </div>

                    <!-- اختيار الدولة -->
                    <div class="mb-3">
                        <label for="country" class="form-label">{{ __('Select Country') }}</label>
                        <select class="form-control" id="country" name="country" required>
                            <option value="jordan" {{ old('country', $country) == 'jordan' ? 'selected' : '' }}>Jordan</option>
                            <option value="saudi" {{ old('country', $country) == 'saudi' ? 'selected' : '' }}>Saudi Arabia</option>
                            <option value="egypt" {{ old('country', $country) == 'egypt' ? 'selected' : '' }}>Egypt</option>
                            <option value="palestine" {{ old('country', $country) == 'palestine' ? 'selected' : '' }}>Palestine</option>
                        </select>
                    </div>

                    <!-- زر الحفظ -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <i class="ri-save-line me-1"></i>{{ __('update_class') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
