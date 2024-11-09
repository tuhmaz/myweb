@extends('layouts/layoutMaster')

@section('title', __('Semesters'))

@section('content')
<div class="content-body">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="ri-calendar-2-line me-2"></i>{{ __('Semesters') }}</h5>

        <!-- قائمة منسدلة لاختيار الدولة -->
        <form method="GET" action="{{ route('semesters.index') }}" class="d-flex">
          <select name="country" class="form-select me-2" onchange="this.form.submit()">
            <option value="jordan" {{ $country == 'jordan' ? 'selected' : '' }}>Jordan</option>
            <option value="saudi" {{ $country == 'saudi' ? 'selected' : '' }}>Saudi Arabia</option>
            <option value="egypt" {{ $country == 'egypt' ? 'selected' : '' }}>Egypt</option>
            <option value="palestine" {{ $country == 'palestine' ? 'selected' : '' }}>Palestine</option>
          </select>
          <a href="{{ route('semesters.create', ['country' => $country]) }}" class="btn btn-success">
            <i class="ri-add-line me-1"></i>{{ __('Add New Semester') }}
          </a>
        </form>
      </div>

      <div class="card-body">
        <div class="accordion" id="gradeSemestersAccordion">
          @foreach ($groupedSemesters as $gradeName => $semesters)
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ $loop->index }}">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}" aria-expanded="false" aria-controls="collapse{{ $loop->index }}">
                {{ $gradeName }}
              </button>
            </h2>
            <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#gradeSemestersAccordion">
              <div class="accordion-body">
                <table class="table table-striped">
                  <thead class="bg-light text-white">
                    <tr>
                      <th><i class="ri-calendar-2-line me-1"></i>{{ __('Semester Name') }}</th>
                      <th class="text-center"><i class="ri-tools-line me-1"></i>{{ __('Actions') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($semesters as $semester)
                    <tr>
                      <td>{{ $semester->semester_name }}</td>
                      <td class="text-center">
                        <a href="{{ route('semesters.show', ['semester' => $semester->id, 'country' => $country]) }}" class="btn btn-sm btn-outline-info">
                          <i class="ri-eye-line me-1"></i>{{ __('View') }}
                        </a>
                        <a href="{{ route('semesters.edit', ['semester' => $semester->id, 'country' => $country]) }}" class="btn btn-sm btn-outline-warning">
                          <i class="ri-pencil-line me-1"></i>{{ __('Edit') }}
                        </a>

                        <form action="{{ route('semesters.destroy', ['semester' => $semester->id, 'country' => $country]) }}" method="POST" style="display:inline;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('Are you sure?') }}');">
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
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
