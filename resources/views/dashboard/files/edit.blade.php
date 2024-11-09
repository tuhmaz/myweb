@extends('layouts/layoutMaster')

@section('title', __('Edit File'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="ri-edit-line me-2"></i>{{ __('Edit File') }}
                        </h4>
                        <!-- زر الرجوع -->
                        <a href="{{ route('files.index', ['country' => request('country')]) }}" class="btn btn-light btn-sm">
                            <i class="ri-arrow-go-back-line me-1"></i>{{ __('back_to_list') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- تعديل الفورم لتمرير الدولة -->
                        <form method="POST" action="{{ route('files.update', ['file' => $file->id, 'country' => request('country')]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- حقل مخفي لتمرير الدولة -->
                            <input type="hidden" name="country" value="{{ request('country') }}">

                            <div class="mb-3">
                                <label for="article_id" class="form-label">{{ __('Article') }}</label>
                                <select class="form-control" id="article_id" name="article_id">
                                    @foreach ($articles as $article)
                                    <option value="{{ $article->id }}" {{ $file->article_id == $article->id ? 'selected' : '' }}>{{ $article->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="file" class="form-label">{{ __('Upload New File (Optional)') }}</label>
                                <input type="file" class="form-control" id="file" name="file">
                                <small class="form-text text-muted">{{ __('Leave blank if no change') }}</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    <i class="ri-save-line me-1"></i>{{ __('Update') }}
                                </button>
                                <a href="{{ route('files.index', ['country' => request('country')]) }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
