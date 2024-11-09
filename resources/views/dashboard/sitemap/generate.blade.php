@extends('layouts/layoutMaster')

@section('content')
<div class="container">
    <h1>إدارة خريطة الموقع</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">توليد خريطة الموقع (Sitemap)</h5>
            <p class="card-text">اضغط على الزر أدناه لتوليد خريطة الموقع وتحديثها.</p>
            <a href="{{ route('dashboard.sitemap.generate') }}" class="btn btn-primary">توليد خريطة الموقع</a>
        </div>
    </div>
</div>
@endsection
