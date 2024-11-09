@extends('layouts/layoutMaster')

@section('title', __('subjects'))

@section('content')
<div class="content-body">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="ri-book-line me-2"></i>{{ __('subjects') }}</h5>

        <!-- قائمة منسدلة لاختيار الدولة -->
        <form method="GET" action="{{ route('subjects.index') }}" class="d-flex">
          <select name="country" class="form-select me-2" onchange="this.form.submit()">
            <option value="jordan" {{ $country == 'jordan' ? 'selected' : '' }}>Jordan</option>
            <option value="saudi" {{ $country == 'saudi' ? 'selected' : '' }}>Saudi Arabia</option>
            <option value="egypt" {{ $country == 'egypt' ? 'selected' : '' }}>Egypt</option>
            <option value="palestine" {{ $country == 'palestine' ? 'selected' : '' }}>Palestine</option>
          </select>
          <a href="{{ route('subjects.create', ['country' => $country]) }}" class="btn btn-success">
            <i class="ri-add-line me-1"></i>{{ __('add_new_subject') }}
          </a>
        </form>
      </div>

      <div class="card-body">
        <div class="accordion" id="gradeSubjectsAccordion">
          @foreach ($groupedSubjects as $gradeName => $subjects)
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ $loop->index }}">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}" aria-expanded="false" aria-controls="collapse{{ $loop->index }}">
                {{ $gradeName }}
              </button>
            </h2>
            <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#gradeSubjectsAccordion">
              <div class="accordion-body">
                <table class="table table-striped">
                  <thead class="bg-light text-white">
                    <tr>
                      <th><i class="ri-book-2-line me-1"></i>{{ __('subject_name') }}</th>
                      <th class="text-center"><i class="ri-tools-line me-1"></i>{{ __('actions') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($subjects as $subject)
                    <tr>
                      <td>{{ $subject->subject_name }}</td>
                      <td class="text-center">
                        <a href="{{ route('subjects.show', $subject->id) }}" class="btn btn-sm btn-outline-info">
                          <i class="ri-eye-line me-1"></i>{{ __('view') }}
                        </a>
                        <a href="{{ route('subjects.edit', ['subject' => $subject->id, 'country' => $country]) }}" class="btn btn-sm btn-outline-warning">
                          <i class="ri-pencil-line me-1"></i>{{ __('edit') }}
                        </a>

                        <form action="{{ route('subjects.destroy', ['subject' => $subject->id, 'country' => $country]) }}" method="POST" style="display:inline;">
                          @csrf
                          @method('DELETE')
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
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
