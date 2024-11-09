@extends('layouts/layoutMaster')

@section('title', __('Categories Management'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="card overflow-hidden">
            <div class="card-header d-flex justify-content-between align-items-center flex-column flex-md-row">
                <h5>
                    <i class="ri-folder-line me-2"></i>{{ __('Categories Management') }}
                </h5>
                <a href="{{ route('categories.create') }}" class="btn btn-success mt-3 mt-md-0">
                    <i class="ri-add-line me-1"></i>{{ __('Add New Category') }}
                </a>
            </div>

            <!-- اختيار البلد -->
            <form method="GET" action="{{ route('categories.index') }}" class="mb-4">
                <div class="form-group">
                    <label for="country">{{ __('Select Country') }}</label>
                    <select class="form-control" id="country" name="country" onchange="this.form.submit()">
                        <option value="">{{ __('All Countries') }}</option>
                        <option value="jordan" {{ request()->get('country') == 'jordan' ? 'selected' : '' }}>Jordan</option>
                        <option value="saudi" {{ request()->get('country') == 'saudi' ? 'selected' : '' }}>Saudi Arabia</option>
                        <option value="egypt" {{ request()->get('country') == 'egypt' ? 'selected' : '' }}>Egypt</option>
                        <option value="palestine" {{ request()->get('country') == 'palestine' ? 'selected' : '' }}>Palestine</option>
                    </select>
                </div>
            </form>

            <!-- جدول عرض الفئات -->
            <div class="table-responsive text-nowrap">
                <table class="table table-striped table-bordered">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Country') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ ucfirst($category->country) }}</td>
                            <td class="text-center">
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="ri-pencil-line me-1"></i>{{ __('Edit') }}
                                </a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this category?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="ri-delete-bin-7-line me-1"></i>{{ __('Delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
