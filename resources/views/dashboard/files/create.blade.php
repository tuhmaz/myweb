@extends('layouts/layoutMaster')

@section('title', __('Create File'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light text-white">
                        <h4 class="mb-0"><i class="ri-file-add-line me-2"></i>{{ __('Create File') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- قائمة اختيار الدولة -->
                            <div class="mb-3">
                                <label for="country" class="form-label">{{ __('Country') }}</label>
                                <select class="form-control" id="country" name="country" onchange="this.form.submit()">
                                    <option value="jordan" {{ request('country') == 'jordan' ? 'selected' : '' }}>Jordan</option>
                                    <option value="saudi" {{ request('country') == 'saudi' ? 'selected' : '' }}>Saudi Arabia</option>
                                    <option value="egypt" {{ request('country') == 'egypt' ? 'selected' : '' }}>Egypt</option>
                                    <option value="palestine" {{ request('country') == 'palestine' ? 'selected' : '' }}>Palestine</option>
                                </select>
                            </div>

                            <!-- قائمة المقالات المرتبطة بالدولة المختارة -->
                            <div class="mb-3">
                                <label for="article_id" class="form-label">{{ __('Article') }}</label>
                                <select class="form-control" id="article_id" name="article_id">
                                    @foreach ($articles as $article)
                                        <option value="{{ $article->id }}">{{ $article->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- رفع الملف -->
                            <div class="mb-3">
                                <label for="file" class="form-label">{{ __('Upload File') }}</label>
                                <input type="file" class="form-control" id="file" name="file" required>
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="ri-upload-line me-1"></i>{{ __('Submit') }}
                            </button>
                            <a href="{{ route('files.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
