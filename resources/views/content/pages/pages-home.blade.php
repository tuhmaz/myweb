@php
$configData = Helper::appClasses();
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    <!-- Welcome Section -->
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title">مرحبا بك، {{ Auth::user()->name }}!</h4>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">عدد الأعضاء</h5>
                    <p class="card-text display-4">{{ $usersCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">عدد المقالات</h5>
                    <p class="card-text display-4">{{ $articlesCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">عدد الأخبار</h5>
                    <p class="card-text display-4">{{ $newsCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Articles Section -->
    <div class="card mb-4 mt-4">
        <div class="card-header">
            <h5 class="card-title">أحدث المقالات</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($latestArticles as $article)
                    <li class="list-group-item">
                        <a href="{{ route('articles.show', $article->id) }}">{{ $article->title }}</a>
                        <span class="badge bg-primary float-end">{{ $article->created_at->format('Y-m-d') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Latest News Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">أحدث الأخبار</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($latestNews as $news)
                    <li class="list-group-item">
                        <a href="{{ route('news.show', $news->id) }}">{{ $news->title }}</a>
                        <span class="badge bg-primary float-end">{{ $news->created_at->format('Y-m-d') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Admins and Supervisors Icons -->
    <div class="row">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="ri-admin-line"></i> المدراء</h5>
                    <p class="card-text">{{ $adminsCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="ri-shield-user-line"></i> المشرفين</h5>
                    <p class="card-text">{{ $supervisorsCount }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
