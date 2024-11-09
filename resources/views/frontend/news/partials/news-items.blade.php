@foreach($news as $newsItem)
<div class="col-md-6 col-lg-3 mb-4">
    <div class="card h-100 shadow-sm d-flex flex-column">
        <img src="{{ asset('storage/images/' . $newsItem->image) }}" class="card-img-top img-fluid" alt="{{ $newsItem->alt }}" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $newsItem->title }}</h5>
            <p class="card-text">{{ Str::limit(strip_tags($newsItem->description), 60) }}</p>
            <div class="mt-auto">
                <a href="{{ route('frontend.news.show', ['id' => $newsItem->id]) }}" class="btn btn-primary btn-sm">Read More</a>
            </div>
        </div>
        <div class="card-footer text-muted">
            Published on {{ $newsItem->created_at->format('d M Y') }}
        </div>
    </div>
</div>
@endforeach
