@extends('layouts.layoutMaster')

@section('title', __('Sitemap Management'))

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <!-- Main Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center flex-column flex-md-row">
                <h5 class="mb-0"><i class="ri-map-pin-line me-2"></i>{{ __('Sitemap Management') }}</h5>
            </div>

            <div class="card-body">
                <!-- Success Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Information Message -->
                <div class="alert alert-info">
                    {{ __('You can generate a new sitemap to update search engines with the latest changes to your website.') }}
                </div>

                <!-- Database Selection Form -->
                <form action="{{ route('sitemap.generate') }}" method="GET" class="mb-4">
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
                                <i class="ri-refresh-line me-1"></i>{{ __('Generate Sitemap') }}
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-4">

                <!-- Available Sitemap Links -->
                <h5><i class="ri-file-list-line me-2"></i>{{ __('Available Sitemap Links') }}:</h5>

                @php
                    $databases = ['jo', 'sa', 'eg', 'ps']; // Available databases
                @endphp

                @foreach($databases as $db)
                    <!-- Table for each database -->
                    <h6 class="mt-4">{{ __('Sitemap for') }} {{ strtoupper($db) }}</h6>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-striped table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th><i class="ri-file-2-line me-1"></i>{{ __('Sitemap') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Articles Sitemap -->
                                <tr>
                                    <td>
                                        <a href="{{ asset('storage/sitemaps/sitemap_articles_' . $db . '.xml') }}" class="list-group-item-action" target="_blank">
                                            <i class="ri-link me-2"></i>{{ __('Articles Sitemap for') }} {{ strtoupper($db) }}
                                        </a>
                                    </td>
                                    <td>
                                        <form action="{{ route('sitemap.delete', ['type' => 'articles', 'database' => $db]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-line"></i> {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- News Sitemap -->
                                <tr>
                                    <td>
                                        <a href="{{ asset('storage/sitemaps/sitemap_news_' . $db . '.xml') }}" class="list-group-item-action" target="_blank">
                                            <i class="ri-link me-2"></i>{{ __('News Sitemap for') }} {{ strtoupper($db) }}
                                        </a>
                                    </td>
                                    <td>
                                        <form action="{{ route('sitemap.delete', ['type' => 'news', 'database' => $db]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-line"></i> {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Static Pages Sitemap -->
                                <tr>
                                    <td>
                                        <a href="{{ asset('storage/sitemaps/sitemap_static_' . $db . '.xml') }}" class="list-group-item-action" target="_blank">
                                            <i class="ri-link me-2"></i>{{ __('Static Pages Sitemap for') }} {{ strtoupper($db) }}
                                        </a>
                                    </td>
                                    <td>
                                        <form action="{{ route('sitemap.delete', ['type' => 'static', 'database' => $db]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-line"></i> {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
