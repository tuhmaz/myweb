@extends('layouts/layoutMaster')

@section('title', __('edit_subject'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>
                    <i class="ri-pencil-line me-2"></i>{{ __('edit_subject') }}
                </h5>
                <a href="{{ route('subjects.index', ['country' => $country]) }}" class="btn btn-primary">
                    <i class="ri-arrow-go-back-line me-1"></i>{{ __('back_to_list') }}
                </a>
            </div>

            <div class="card-body">
                <!-- عرض الأخطاء إن وجدت -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- نموذج تعديل المادة -->
                <form method="POST" action="{{ route('subjects.update', ['subject' => $subject->id, 'country' => $country]) }}">
                    @csrf
                    @method('PUT')

                    <!-- حقل اسم المادة -->
                    <div class="form-group mb-3">
                        <label for="subject_name">{{ __('subject_name') }}:</label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" value="{{ old('subject_name', $subject->subject_name) }}" required>
                    </div>

                    <!-- قائمة الصفوف الدراسية المرتبطة بالدولة -->
                    <div class="form-group mb-3">
                        <label for="grade_level">{{ __('grade_level') }}:</label>
                        <select class="form-control" id="grade_level" name="grade_level" required>
                            <option value="">{{ __('Select Grade Level') }}</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->grade_level }}" {{ $class->grade_level == $subject->grade_level ? 'selected' : '' }}>
                                    {{ $class->grade_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- زر التحديث -->
                    <button type="submit" class="btn btn-success">
                        <i class="ri-save-line me-1"></i>{{ __('update_subject') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
