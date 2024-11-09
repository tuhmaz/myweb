@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\News;
use Detection\MobileDetect;

$detect = new MobileDetect;


// Fetch random news items from the selected database
$randomNews = \App\Models\News::on($database)
->inRandomOrder()
->take(5)
->get();
@endphp



@extends('layouts/layoutFront')

@section('title', $news->title)

@section('meta')
<meta property="og:type" content="article" />
<meta property="og:title" content="{{ $news->title }}" />
<meta property="og:description" content="{{ $news->meta_description }}" />
<meta property="og:image" content="{{ asset('storage/images/' . $news->image) }}" />
<meta property="og:url" content="{{ request()->fullUrl() }}" />
<meta property="og:site_name" content="{{ config('settings.site_name') }}" />
<meta property="article:modified_time" content="{{ $news->updated_at->toIso8601String() }}" />
<meta property="article:published_time" content="{{ $news->created_at->toIso8601String() }}" />

<meta property="article:author" content="{{ optional($news->author)->name ?? 'Unknown' }}" />

<meta property="article:section" content="{{ $news->category->name }}" />

<meta property="article:tag" content="{{ implode(',', $news->keywords->pluck('keyword')->toArray()) }}" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $news->title }}" />
<meta name="twitter:description" content="{{ $news->meta_description }}" />
<meta name="twitter:image" content="{{ asset('storage/images/' . $news->image) }}" />
<meta name="twitter:site" content="{{ config('settings.twitter') }}" />
@endsection

@section('content')
<section class="section-py first-section-pt help-center-header position-relative overflow-hidden" style="background-color: rgb(32, 44, 69);">
  <h1 class="text-center text-white">{{ $news->title }}</h1>
</section>

<div class="container px-4 mt-4">
  <ol class="breadcrumb breadcrumb-style2" aria-label="breadcrumbs">
    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="ti ti-home-check"></i>{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('frontend.news.index',['database' => $database ?? session('database', 'default_database')]) }}">{{ __('News') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $news->title }}</li>
  </ol>
  <div class="progress mt-2">
    <div class="progress-bar" role="progressbar" style="width: 75%;"></div>
  </div>
</div>

<div class="container mt-4 mb-4">
  @if(config('settings.google_ads_desktop_news') || config('settings.google_ads_mobile_news'))
  <div class="ads-container text-center">
    @if($detect->isMobile())
    {!! config('settings.google_ads_mobile_news') !!}
    @else
    {!! config('settings.google_ads_desktop_news') !!}
    @endif
  </div>
  @endif
</div>

<section class="section-py bg-body">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-8 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 gap-2">
              <div>
                <h2 class="mb-2">{{ $news->title }}</h2>
                <p class="text-muted mb-0">{{ __('Published on') }} <span class="fw-medium text-heading">{{ $news->created_at->format('d M Y') }}</span></p>
              </div>
              <div class="social-share">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-icon btn-outline-primary">
                  <i class="ti ti-brand-facebook"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-icon btn-outline-info">
                  <i class="ti ti-brand-twitter"></i>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-icon btn-outline-primary">
                  <i class="ti ti-brand-linkedin"></i>
                </a>
              </div>
            </div>

            @php
    // التحقق إذا كانت الصورة موجودة أم لا
    $imagePath = $news->image ? asset('storage/images/' . $news->image) : asset('path_to_default_image/default.jpg');
@endphp

<img src="{{ $imagePath }}" class="card-img-top img-fluid mb-4" alt="{{ $news->title }}">
<h4 class="mb-4"> {!! $news->description !!}</h4>

            <div class="news-details pt-12">
              <div class="row">
                <div class="col-md-6">
                  <h6>{{ __('Tags') }}</h6>
                  @php
                  $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];
                  $keywordIndex = 0;
                  @endphp

                  @foreach(explode(',', implode(',', $news->keywords->pluck('keyword')->toArray())) as $keyword) @php
                  $color = $colors[$keywordIndex % count($colors)];
                  $keywordIndex++;
                  @endphp
                  <span class="badge bg-{{ $color }} bg-glow me-1">{{ trim($keyword) }}</span>
                  @endforeach
                </div>

                <div class="col-md-6">
                  <h6>{{ __('Meta Description') }}</h6>
                  <span class="badge bg-info bg-glow" style="text-wrap: wrap;">{{ $news->meta_description }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="container mt-4 mb-4">
          @if(config('settings.google_ads_desktop_news_2') || config('settings.google_ads_mobile_news_2'))
          <div class="ads-container text-center">
            @if($detect->isMobile())
            {!! config('settings.google_ads_mobile_news_2') !!}
            @else
            {!! config('settings.google_ads_desktop_news_2') !!}
            @endif
          </div>
          @endif
        </div>

        <div class="card mt-4">
          <div class="card-body">
            <h3 class="mb-4">{{ __('Add a Comment') }}</h3>
            <form action="{{ route('comments.store') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="comment" class="form-label">{{ __('Your Comment') }}</label>
                <textarea class="form-control" id="comment" name="body" rows="4" required></textarea>
              </div>
              <input type="hidden" name="commentable_id" value="{{ $news->id }}">
              <input type="hidden" name="commentable_type" value="{{ get_class($news) }}">
              <button type="submit" class="btn btn-primary">{{ __('Submit Comment') }}</button>
            </form>

            @foreach($news->comments as $comment)
            <div class="mt-4">
              <div class="divider divider-success">
                <div class="divider-text">{{ $comment->user->name }}</div>
              </div>
              <p>{{ $comment->body }}</p>

              <div class="reactions-inline-spacing d-flex justify-content-center">
                <form action="{{ route('reactions.store') }}" method="POST" class="d-inline-block">
                  @csrf
                  <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                  <input type="hidden" name="type" value="like">
                  <button type="submit" class="btn btn-outline-info btn-sm">
                    <i class="ti ti-thumb-up me-1"></i> {{ __('Like') }}
                    <span class="badge bg-white text-info ms-1">{{ $comment->reactions->where('type', 'like')->count() }}</span>
                  </button>
                </form>

                <form action="{{ route('reactions.store') }}" method="POST" class="d-inline-block">
                  @csrf
                  <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                  <input type="hidden" name="type" value="love">
                  <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="ti ti-heart me-1"></i> {{ __('Love') }}
                    <span class="badge bg-white text-danger ms-1">{{ $comment->reactions->where('type', 'love')->count() }}</span>
                  </button>
                </form>

                <form action="{{ route('reactions.store') }}" method="POST" class="d-inline-block">
                  @csrf
                  <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                  <input type="hidden" name="type" value="laugh">
                  <button type="submit" class="btn btn-outline-warning btn-sm">
                    <i class="ti ti-mood-happy me-1"></i> {{ __('Laugh') }}
                    <span class="badge bg-white text-warning ms-1">{{ $comment->reactions->where('type', 'laugh')->count() }}</span>
                  </button>
                </form>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-12 mt-4 mt-lg-0">
        <h5 class="mb-3">{{ __('Related News') }}</h5>
        <div class="accordion stick-top accordion-custom-button" id="relatedNews">
          @foreach($randomNews as $index => $randomItem)
          <div class="accordion-item @if($index === 1) active @endif mb-0">
            <div class="accordion-header" id="heading{{ $index }}">
              <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                <span class="d-flex flex-column">
                  <span class="h6 mb-0">{{ Str::limit($randomItem->title, 50) }}</span>
                </span>
              </button>
            </div>
            <div id="collapse{{ $index }}" class="accordion-collapse collapse @if($index === 2) show @endif" data-bs-parent="#relatedNews">
              <div class="accordion-body py-4">
                <img src="{{ asset('storage/images/' . $randomItem->image) }}" class="card-img-top img-fluid mb-2" alt="{{ $randomItem->title }}">
                <p class="text-body fw-normal">{{ Str::limit(strip_tags($randomItem->description), 100) }}</p>
                <a href="{{ route('frontend.news.show', ['database' => $database ?? session('database', 'default_database'), 'id' => $randomItem->id]) }}" class="btn btn-primary btn-sm">{{ __('Read more') }}</a>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <div class="container mt-4 mb-4">
          @if(config('settings.google_ads_desktop_news') || config('settings.google_ads_mobile_news_2'))
          <div class="ads-container text-center">
            @if($detect->isMobile())
            {!! config('settings.google_ads_mobile_news_2') !!}
            @else
            {!! config('settings.google_ads_desktop_news') !!}
            @endif
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "headline": "{{ $news->title }}",
    "image": "{{ asset('storage/images/' . $news->image) }}",
    "datePublished": "{{ $news->created_at->toIso8601String() }}",
    "author": {
      "@type": "Person",
      "name": "{{ optional($news->author)->name ?? 'Unknown' }}"
    },
    "publisher": {
      "@type": "Organization",
      "name": "{{ config('settings.site_name') }}",
      "logo": {
        "@type": "ImageObject",
        "url": "{{ asset('storage/' . config('settings.site_logo')) }}"
      }
    },
    "description": "{{ $news->meta_description }}"
  }
</script>

<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "{{ url('/') }}"
      },
      {
        "@type": "ListItem",
        "position": 2,
        "name": "News",
        "item": "{{ route('frontend.news.index',['database' => $database ?? session('database', 'default_database')]) }}"
      },
      {
        "@type": "ListItem",
        "position": 3,
        "name": "{{ $news->title }}",
        "item": "{{ request()->url() }}"
      }
    ]
  }
</script>

@endsection
