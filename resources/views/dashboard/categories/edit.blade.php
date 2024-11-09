@extends('layouts/layoutMaster')

@section('title', __('Edit Category'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="card overflow-hidden">
            <div class="card-header d-flex justify-content-between align-items-center flex-column flex-md-row">
                <h5>
                    <i class="ri-folder-edit-line me-2"></i>{{ __('Edit Category') }}
                </h5>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-primary mt-3 mt-md-0">
                    <i class="ri-arrow-left-line me-1"></i>{{ __('Back to Categories') }}
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

                 <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                     <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Category Name') }}</label>
                        <input type="text" name="name" class="form-control" placeholder="{{ __('Enter Category Name') }}" value="{{ old('name', $category->name) }}" required>
                    </div>

                     <div class="mb-3">
                        <label for="country" class="form-label">{{ __('Select Country') }}</label>
                        <select name="country" class="form-control" required>
                            <option value="">{{ __('Select Country') }}</option>
                            <option value="jo" {{ old('country', $category->country) == 'jordan' ? 'selected' : '' }}>Jordan</option>
                            <option value="sa" {{ old('country', $category->country) == 'saudi' ? 'selected' : '' }}>Saudi Arabia</option>
                            <option value="eg" {{ old('country', $category->country) == 'egypt' ? 'selected' : '' }}>Egypt</option>
                            <option value="ps" {{ old('country', $category->country) == 'palestine' ? 'selected' : '' }}>Palestine</option>
                        </select>
                    </div>

                     <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>{{ __('Update Category') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
