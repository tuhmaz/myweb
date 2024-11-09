@extends('layouts/layoutMaster')

@section('title', __('classes'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="card overflow-hidden">
            <div class="card-header d-flex justify-content-between align-items-center flex-column flex-md-row">
                <h5>
                    <i class="ri-classroom-line me-2"></i>{{ __('classes') }}
                </h5>
                <a href="{{ route('classes.create') }}" class="btn btn-success mt-3 mt-md-0">
                    <i class="ri-add-line me-1"></i>{{ __('add_new_class') }}
                </a>
            </div>

            <form method="GET" action="{{ route('classes.index') }}" class="mb-4">
                <div class="form-group">
                    <label for="country">Select Country</label>
                    <select class="form-control" id="country" name="country" onchange="this.form.submit()">
                        <option value="jordan" {{ $country == 'jordan' ? 'selected' : '' }}>Jordan (Main Database)</option>
                        <option value="saudi" {{ $country == 'saudi' ? 'selected' : '' }}>Saudi Arabia</option>
                        <option value="egypt" {{ $country == 'egypt' ? 'selected' : '' }}>Egypt</option>
                        <option value="palestine" {{ $country == 'palestine' ? 'selected' : '' }}>Palestine</option>
                    </select>
                </div>
            </form>

            <div class="table-responsive text-nowrap">
                <table class="table table-striped table-bordered">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th><i class="ri-book-2-line me-1"></i>{{ __('name') }}</th>
                            <th><i class="ri-honour-line me-1"></i>{{ __('grade_level') }}</th>
                            <th class="text-center"><i class="ri-tools-line me-1"></i>{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schoolClasses as $class)
                        <tr>
                            <td>
                                <i class="ri-suitcase-2-line ri-22px text-danger me-2"></i>
                                {{ $class->grade_name }}
                            </td>
                            <td>{{ $class->grade_level }}</td>
                            <td class="text-center">
                              
                                <a href="{{ route('classes.edit', ['class' => $class->id, 'country' => request()->input('country')]) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="ri-pencil-line me-1"></i>{{ __('edit') }}
                                </a>
                                <form action="{{ route('classes.destroy', $class->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this class?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="country" value="{{ request()->input('country') }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="ri-delete-bin-7-line me-1"></i>{{ __('delete') }}
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
