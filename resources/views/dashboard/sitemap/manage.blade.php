@php
    $customizerHidden = 'customizer-hide';
    $configData = Helper::appClasses();
@endphp

@extends('layouts.layoutMaster')

@section('title', __('Sitemap Archiving Management'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <!-- Main Card for Sitemap Archiving Management -->
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ri-archive-line me-2"></i>{{ __('Sitemap Archiving Management') }}</h5>
            </div>

            <div class="card-body">
                <!-- Success Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
                    </div>
                @endif

                <!-- Database Selection Form -->
                <form action="{{ route('sitemap.manage') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="database" class="form-label">{{ __('Select Database') }}:</label>
                                <select name="database" id="database" class="form-control">
                                    <option value="jo" {{ $database == 'jo' ? 'selected' : '' }}>{{ __('Jordan') }}</option>
                                    <option value="sa" {{ $database == 'sa' ? 'selected' : '' }}>{{ __('Saudi Arabia') }}</option>
                                    <option value="eg" {{ $database == 'eg' ? 'selected' : '' }}>{{ __('Egypt') }}</option>
                                    <option value="ps" {{ $database == 'ps' ? 'selected' : '' }}>{{ __('Palestine') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-check-line me-1"></i>{{ __('Confirm Database') }}
                            </button>
                        </div>
                    </div>
                </form>

                @if(isset($articles) && isset($classes) && isset($newsItems) && isset($categories) && isset($homepage) && isset($keywords))
                <!-- Available Resources Management -->
                <form action="{{ route('sitemap.updateResourceInclusion') }}" method="POST" class="mt-4">
                    @csrf

                    <h3 class="mb-4">{{ __('Available Resources') }}</h3>

                    <div class="accordion" id="sitemapAccordion">
                        <!-- Homepage -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingHomepage">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHomepage" aria-expanded="false" aria-controls="collapseHomepage">
                                    {{ __('Homepage') }}
                                </button>
                            </h2>
                            <div id="collapseHomepage" class="accordion-collapse collapse" aria-labelledby="headingHomepage" data-bs-parent="#sitemapAccordion">
                                <div class="accordion-body">
                                    <input type="checkbox" class="form-check-input homepage-checkbox" name="homepage" value="1" {{ isset($statuses['homepage']) && $statuses['homepage']->is_included ? 'checked' : '' }}>
                                    {{ __('Include in Sitemap') }}
                                </div>
                            </div>
                        </div>

                        <!-- Keywords -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingKeywords">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKeywords" aria-expanded="false" aria-controls="collapseKeywords">
                                    {{ __('Keywords') }}
                                </button>
                            </h2>
                            <div id="collapseKeywords" class="accordion-collapse collapse" aria-labelledby="headingKeywords" data-bs-parent="#sitemapAccordion">
                                <div class="accordion-body">
                                    <input type="checkbox" id="selectAllKeywords" class="form-check-input me-2">
                                    <label for="selectAllKeywords" class="form-check-label">{{ __('Select All') }}</label>
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Keyword') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($keywords as $keyword)
                                                <tr>
                                                    <td>{{ $keyword->keyword }}</td>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input keyword-checkbox" name="keyword_{{ $keyword->id }}" value="1" {{ isset($statuses['keyword-' . $keyword->id]) && $statuses['keyword-' . $keyword->id]->is_included ? 'checked' : '' }}>
                                                        {{ __('Include in Sitemap') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Articles -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingArticles">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArticles" aria-expanded="true" aria-controls="collapseArticles">
                                    {{ __('Articles') }}
                                </button>
                            </h2>
                            <div id="collapseArticles" class="accordion-collapse collapse show" aria-labelledby="headingArticles" data-bs-parent="#sitemapAccordion">
                                <div class="accordion-body">
                                    <input type="checkbox" id="selectAllArticles" class="form-check-input me-2">
                                    <label for="selectAllArticles" class="form-check-label">{{ __('Select All') }}</label>
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Article Title') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($articles as $article)
                                                <tr>
                                                    <td>{{ $article->title }}</td>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input article-checkbox" name="article_{{ $article->id }}" value="1" {{ isset($statuses['article-' . $article->id]) && $statuses['article-' . $article->id]->is_included ? 'checked' : '' }}>
                                                        {{ __('Include in Sitemap') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Classes -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingClasses">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClasses" aria-expanded="false" aria-controls="collapseClasses">
                                    {{ __('Classes') }}
                                </button>
                            </h2>
                            <div id="collapseClasses" class="accordion-collapse collapse" aria-labelledby="headingClasses" data-bs-parent="#sitemapAccordion">
                                <div class="accordion-body">
                                    <input type="checkbox" id="selectAllClasses" class="form-check-input me-2">
                                    <label for="selectAllClasses" class="form-check-label">{{ __('Select All') }}</label>
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Class Name') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($classes as $class)
                                                <tr>
                                                    <td>{{ $class->grade_name }}</td>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input class-checkbox" name="class_{{ $class->id }}" value="1" {{ isset($statuses['class-' . $class->id]) && $statuses['class-' . $class->id]->is_included ? 'checked' : '' }}>
                                                        {{ __('Include in Sitemap') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- News -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingNews">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNews" aria-expanded="false" aria-controls="collapseNews">
                                    {{ __('News') }}
                                </button>
                            </h2>
                            <div id="collapseNews" class="accordion-collapse collapse" aria-labelledby="headingNews" data-bs-parent="#sitemapAccordion">
                                <div class="accordion-body">
                                    <input type="checkbox" id="selectAllNews" class="form-check-input me-2">
                                    <label for="selectAllNews" class="form-check-label">{{ __('Select All') }}</label>
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th>{{ __('News Title') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($newsItems as $news)
                                                <tr>
                                                    <td>{{ $news->title }}</td>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input news-checkbox" name="news_{{ $news->id }}" value="1" {{ isset($statuses['news-' . $news->id]) && $statuses['news-' . $news->id]->is_included ? 'checked' : '' }}>
                                                        {{ __('Include in Sitemap') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">
                        <i class="ri-save-line me-1"></i>{{ __('Update Archiving Status') }}
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Handle select all functionality for each section
        document.getElementById("selectAllArticles").addEventListener("change", function() {
            const isChecked = this.checked;
            document.querySelectorAll(".article-checkbox").forEach(checkbox => checkbox.checked = isChecked);
        });

        document.getElementById("selectAllClasses").addEventListener("change", function() {
            const isChecked = this.checked;
            document.querySelectorAll(".class-checkbox").forEach(checkbox => checkbox.checked = isChecked);
        });

        document.getElementById("selectAllNews").addEventListener("change", function() {
            const isChecked = this.checked;
            document.querySelectorAll(".news-checkbox").forEach(checkbox => checkbox.checked = isChecked);
        });

        document.getElementById("selectAllCategories").addEventListener("change", function() {
            const isChecked = this.checked;
            document.querySelectorAll(".category-checkbox").forEach(checkbox => checkbox.checked = isChecked);
        });

        document.getElementById("selectAllKeywords").addEventListener("change", function() {
            const isChecked = this.checked;
            document.querySelectorAll(".keyword-checkbox").forEach(checkbox => checkbox.checked = isChecked);
        });
    });
</script>
@endsection
