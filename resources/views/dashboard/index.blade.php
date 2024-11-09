@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.layoutMaster')

@section('title', __('Dashboard'))

@section('content')
<div class="container mt-4">
    <!-- Welcome Section -->
    <div class="card mb-4">
        <div class="card-body text-center text-md-start">
            <h4 class="card-title">
                <i class="ri-hand-heart-line me-1"></i>
                {{ __('Welcome, :name!', ['name' => Auth::user()->name]) }}
            </h4>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row g-3">
        <!-- Main Database Statistics -->
        <div class="col-12 col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="ri-user-3-line me-1"></i>
                        {{ __('Total Users') }}
                    </h5>
                    <p class="card-text fs-1">{{ $usersCount }}</p>
                </div>
            </div>
        </div>
        <!-- بقية الأعمدة -->
        <div class="col-12 col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="ri-file-list-line me-1"></i>
                        {{ __('Total Articles') }}
                    </h5>
                    <p class="card-text fs-1">{{ $articlesCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="ri-newspaper-line me-1"></i>
                        {{ __('Total News') }}
                    </h5>
                    <p class="card-text fs-1">{{ $newsCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section for Subdomain Statistics -->
    @foreach (['saudi' => 'Saudi Arabia', 'egypt' => 'Egypt', 'palestine' => 'Palestine'] as $key => $countryName)
    <div class="row g-3 mt-4">
        <div class="col-12">
            <div class="accordion" id="accordion-{{ $key }}">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-{{ $key }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $key }}" aria-expanded="false" aria-controls="collapse-{{ $key }}">
                            <i class="ri-global-line me-1"></i> {{ $countryName }} {{ __('Statistics') }}
                        </button>
                    </h2>
                    <div id="collapse-{{ $key }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $key }}" data-bs-parent="#accordion-{{ $key }}">
                        <div class="accordion-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('Articles') }}</h5>
                                            <p class="card-text fs-2">{{ $subdomainArticlesCount[$key] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- بقية الأعمدة -->
                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('News') }}</h5>
                                            <p class="card-text fs-2">{{ $subdomainNewsCount[$key] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Latest Articles -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="card-title">{{ __('Latest Articles') }}</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach($subdomainLatestArticles[$key] as $article)
                                            <li class="list-group-item d-flex justify-content-between align-items-center flex-column flex-md-row">
                                                <a href="{{ route('articles.show', $article->id) }}">{{ $article->title }}</a>
                                                <span class="badge bg-primary mt-2 mt-md-0">
                                                    <i class="ri-calendar-line"></i> {{ $article->created_at->format('Y-m-d') }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Latest News -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="card-title">{{ __('Latest News') }}</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach($subdomainLatestNews[$key] as $news)
                                            <li class="list-group-item d-flex justify-content-between align-items-center flex-column flex-md-row">
                                                <a href="{{ route('news.show', $news->id) }}">{{ $news->title }}</a>
                                                <span class="badge bg-primary mt-2 mt-md-0">
                                                    <i class="ri-calendar-line"></i> {{ $news->created_at->format('Y-m-d') }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                        </div> <!-- End of accordion-body -->
                    </div> <!-- End of collapse -->
                </div> <!-- End of accordion-item -->
            </div> <!-- End of accordion -->
        </div>
    </div>
    @endforeach

    <!-- Latest Articles Section from Main Database -->
    <div class="card mb-4 mt-4">
        <div class="card-header">
            <h5 class="card-title"><i class="ri-article-line me-1"></i> {{ __('Latest Articles') }}</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($latestArticles as $article)
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-column flex-md-row">
                        <a href="{{ route('articles.show', $article->id) }}">{{ $article->title }}</a>
                        <span class="badge bg-primary mt-2 mt-md-0">
                            <i class="ri-calendar-line"></i> {{ $article->created_at->format('Y-m-d') }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Latest News Section from Main Database -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><i class="ri-notification-line me-1"></i> {{ __('Latest News') }}</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($latestNews as $news)
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-column flex-md-row">
                        <a href="{{ route('news.show', $news->id) }}">{{ $news->title }}</a>
                        <span class="badge bg-primary mt-2 mt-md-0">
                            <i class="ri-calendar-line"></i> {{ $news->created_at->format('Y-m-d') }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Admins and Supervisors Statistics -->
    <div class="row g-3">
        <div class="col-12 col-md-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="ri-shield-user-line me-1"></i> {{ __('Total Admins') }}</h5>
                    <p class="card-text fs-2">{{ $adminsCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="ri-team-line me-1"></i> {{ __('Total Supervisors') }}</h5>
                    <p class="card-text fs-2">{{ $supervisorsCount }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
