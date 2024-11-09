@extends('layouts/layoutMaster')

@section('title', __('Add New Category'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="card overflow-hidden">
            <div class="card-header d-flex justify-content-between align-items-center flex-column flex-md-row">
                <h5>
                    <i class="ri-folder-add-line me-2"></i>{{ __('Add New Category') }}
                </h5>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-primary mt-3 mt-md-0">
                    <i class="ri-arrow-left-line me-1"></i>{{ __('Back to Categories') }}
                </a>
            </div>

            <div class="card-body">
                <!-- عرض أي رسائل أخطاء -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- نموذج إضافة فئة جديدة -->
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <!-- اسم الفئة -->
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Category Name') }}</label>
                        <input type="text" name="name" class="form-control" placeholder="{{ __('Enter Category Name') }}" value="{{ old('name') }}" required>
                    </div>

                    <!-- اختيار الدولة -->
                    <div class="mb-3">
                        <label for="country" class="form-label">{{ __('Select Country') }}</label>
                        <select name="country" class="form-control" required>
                            <option value="">{{ __('Select Country') }}</option>
                            <option value="jo" {{ old('country') == 'jordan' ? 'selected' : '' }}>Jordan</option>
                            <option value="sa" {{ old('country') == 'saudi' ? 'selected' : '' }}>Saudi Arabia</option>
                            <option value="eg" {{ old('country') == 'egypt' ? 'selected' : '' }}>Egypt</option>
                            <option value="ps" {{ old('country') == 'palestine' ? 'selected' : '' }}>Palestine</option>
                        </select>
                    </div>

                    <!-- زر الإرسال -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <i class="ri-save-line me-1"></i>{{ __('Save Category') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
