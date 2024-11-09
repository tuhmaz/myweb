@php
$configData = Helper::appClasses();
use Detection\MobileDetect;
$detect = new MobileDetect;

$database = session('database', 'jo');

$pageTitle = $article->title;
@endphp

@extends('layouts/layoutFront')

@section('title', $pageTitle)

@section('page-style')
@vite('resources/assets/vendor/scss/pages/front-page-help-center.scss')
@endsection

@section('meta')
<meta name="keywords" content="{{ implode(',', $article->keywords->pluck('keyword')->toArray()) }}">

<meta name="description" content="{{ $article->meta_description }}">

<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:title" content="{{ $article->title }}" />
<meta property="og:description" content="{{ $article->meta_description }}" />
<meta property="og:type" content="article" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:image" content="{{ $article->image_url ?? asset('assets/img/front-pages/icons/articles_default_image.jpg') }}" />
<meta property="og:image:width" content="800" />
<meta property="og:image:height" content="600" />
<meta property="og:locale" content="ar_AR" />
<meta property="og:site_name" content="{{ config('settings.site_name') ? config('settings.site_name') : 'site_name' }}" />
<meta property="article:published_time" content="{{ $article->created_at->toIso8601String() }}" />
<meta property="article:modified_time" content="{{ $article->updated_at->toIso8601String() }}" />
@if ($author)
<meta property="article:author" content="{{ $author->name }}" />
@else
<meta property="article:author" content="Unknown Author" />
@endif

<meta property="article:section" content="{{ $subject->subject_name }}" />
<meta property="article:tag" content="{{ implode(',', $article->keywords->pluck('keyword')->toArray()) }}" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $article->title }}" />
<meta name="twitter:description" content="{{ $article->meta_description }}" />
<meta name="twitter:image" content="{{ $article->image_url ?? asset('assets/img/front-pages/icons/articles_default_image.jpg') }}" />
<meta name="twitter:site" content="{{ config('settings.twitter') }}" />
@if ($author && $author->twitter_handle)
<meta name="twitter:creator" content="{{ $author->twitter_handle }}" />
@else
<meta name="twitter:creator" content="@YourDefaultTwitterHandle" />
@endif
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">


<section class="section-py first-section-pt help-center-header position-relative overflow-hidden" style="background-color: rgb(32, 44, 69); padding-bottom: 20px;">
  <h1 class="text-center text-white fw-semibold">{{ $subject->subject_name }}</h1>
  <p class="text-center text-white px-4 mb-0">{{ $grade_level }} - {{ $subject->subject_name }} - {{ $semester->semester_name }}</p>
</section>




<div class="container px-4 mt-4">
  <ol class="breadcrumb breadcrumb-style2" aria-label="breadcrumbs">
    <li class="breadcrumb-item">
      <a href="{{ route('home') }}">
        <i class="ti ti-home-check"></i>{{ __('Home') }}
      </a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('class.index', ['database' => $database ?? session('database', 'default_database')]) }}">{{ __('Classes') }}</a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('frontend.class.show', ['database' => $database ?? session('database', 'default_database'),'id' => $subject->schoolClass->id]) }}">
        {{ $subject->schoolClass->grade_name }}
      </a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('frontend.subjects.show', ['database' => $database ?? session('database', 'default_database'),'subject' => $subject->id]) }}">
        {{ $subject->subject_name }}
      </a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('frontend.subject.articles', ['database' => $database ?? session('database', 'default_database'),'subject' => $subject->id, 'semester' => $semester->id, 'category' => $category]) }}">
        {{ __($category) }} - {{ $semester->semester_name }}
      </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ $article->title }}</li>
  </ol>
  <div class="progress mt-2">
    <div class="progress-bar" role="progressbar" style="width: 100%;"></div>
  </div>
</div>

<section class="section-py bg-body first-section-pt" style="padding-top: 10px;">

  <div class="container mt-4 mb-4">
    @if(config('settings.google_ads_desktop_article') || config('settings.google_ads_mobile_article'))
    <div class="ads-container text-center">
      @if($detect->isMobile())
      {!! config('settings.google_ads_mobile_article') !!}
      @else
      {!! config('settings.google_ads_desktop_article') !!}
      @endif
    </div>
    @endif
  </div>
  <div class="container">
    <div class="card px-3 mt-6">
      <div class="row">
        <div class="content-header text-center bg-primary py-3">
          <h2 class="text-white">{{ $article->title }}</h2>
        </div>

        <div class="content-body text-center mt-3">
          @php
          $file = $article->files->first();
          $fileType = $file ? $file->file_type : 'default';
          $imagePath = match ($fileType) {
          'pdf' => asset('assets/img/icon/pdf-icon.png'),
          'doc', 'docx' => asset('assets/img/icon/word-icon.png'),
          'xls', 'xlsx' => asset('assets/img/icon/excel-icon.png'),
          default => asset('assets/img/icon/default-icon.png'),
          };
          @endphp

          <img src="{{ $imagePath }}" class="img-fluid document-icon mb-3 mt-3" alt="Document Icon" style="max-width: 100px;">

          <h3 class="mb-3">
            @switch($category)
            @case('plans')
            {{ __('study_plans') }}
            @break
            @case('papers')
            {{ __('worksheets') }}
            @break
            @case('tests')
            {{ __('tests') }}
            @break
            @case('books')
            {{ __('school_books') }}
            @break
            @default
            {{ __('articles') }}
            @endswitch
          </h3>

          <div class="divider divider-success">
            <div class="divider-text">{{ $subject->subject_name }} - {{ $semester->semester_name }}</div>
          </div>

          <div class="table-responsive mb-4">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td><strong>{{ __('grade') }}</strong></td>
                  <td>{{ $grade_level }}</td>
                </tr>
                <tr>
                  <td><strong>{{ __('semester') }}</strong></td>
                  <td>{{ $semester->semester_name }}</td>
                </tr>
                <tr>
                  <td><strong>{{ __('subject') }}</strong></td>
                  <td>{{ $subject->subject_name }}</td>
                </tr>
                <tr>
                  <td><strong>{{ __('content_type') }}</strong></td>
                  <td>
                    @switch($category)
                    @case('plans')
                    {{ __('study_plans') }}
                    @break
                    @case('papers')
                    {{ __('worksheets') }}
                    @break
                    @case('tests')
                    {{ __('tests') }}
                    @break
                    @case('books')
                    {{ __('school_books') }}
                    @break
                    @default
                    {{ __('articles') }}
                    @endswitch
                  </td>
                </tr>
                <tr>
                  <td><strong>{{ __('last_updated') }}</strong></td>
                  <td>{{ $article->created_at->format('d M Y') }}</td>
                </tr>
                <tr>
                  <td><strong>{{ __('keywords') }}</strong></td>
                  <td>
                    {{ implode(',', $article->keywords->pluck('keyword')->toArray()) }}
                  </td>

                </tr>
                <tr>
                  <td><strong>{{ __('visits') }}</strong></td>
                  <td>{{ $article->visit_count }}</td>
                </tr>
                <tr>
                  <td><strong>{{ __('downloads') }}</strong></td>
                  <td>{{ $file->download_count }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="card mb-4 p-3">
            <div class="card-text">

              @php
              switch($database) {
              case 'sa':
              $defaultImageUrl = asset('assets/img/front-pages/icons/articles_saudi_image.jpg');
              break;
              case 'eg':
              $defaultImageUrl = asset('assets/img/front-pages/icons/articles_egypt_image.jpg');
              break;
              case 'ps':
              $defaultImageUrl = asset('assets/img/front-pages/icons/articles_palestine_image.jpg');
              break;
              default:
              $defaultImageUrl = asset('assets/img/front-pages/icons/articles_default_image.jpg');
              break;
              }
              @endphp

              @php
              if (!function_exists('insertDefaultImageIfNeeded')) {
              function insertDefaultImageIfNeeded($content, $defaultImageUrl, $articleTitle) {
              preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches);

                if (!isset($matches[1])) {
                $content = '<img src="' . $defaultImageUrl . '" alt="' . e($articleTitle) . '" class="article-default-image" style="max-width:100%; height:auto;">' . $content;
                }

                return $content;
                }
                }

                if (isset($article) && isset($article->content) && isset($article->title)) {
                $articleContent = insertDefaultImageIfNeeded($article->content, $defaultImageUrl, $article->title);
                }
                @endphp



                <style>
                  .article-default-image {
                    max-width: 100%;
                    height: auto;
                    display: block;
                    margin: 0 auto;
                    max-height: 300px;
                  }

                  @media (max-width: 768px) {
                    .article-default-image {
                      max-width: 90%;
                      max-height: 200px;
                    }
                  }

                  .article-content img {
                    max-width: 100%;
                    height: auto;
                  }
                </style>

                <div class="article-content">

                  {!! $articleContent !!}
                  <div class="container mt-4 mb-4">
                    @if(config('settings.google_ads_desktop_article_2') || config('settings.google_ads_mobile_article_2'))
                    <div class="ads-container text-center">
                      @if($detect->isMobile())
                      {!! config('settings.google_ads_mobile_article_2') !!}
                      @else
                      {!! config('settings.google_ads_desktop_article_2') !!}
                      @endif
                    </div>
                    @endif
                  </div>


                  @foreach($article->keywords as $keyword)
                  <a href="{{ route('keywords.indexByKeyword', ['database' => $database,'keywords' => $keyword->keyword]) }}">{{ $keyword->keyword }}</a>
                  @endforeach
                </div>




            </div>

            {!! $article->meta_description !!}
          </div>

          @foreach ($article->files as $file)
          <div class="divider divider-danger">
            <div class="divider-text">
              <a href="{{ route('download.page', ['file' => $file->id]) }}" class="btn btn-outline-danger" target="_blank">
                {{ __('download') }}
              </a>
            </div>

          </div>
          @endforeach

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


        <div class="container mt-4 mb-4">
          @if(config('settings.google_ads_desktop_2') || config('settings.google_ads_mobile_2'))
          <div class="ads-container text-center">
            @if($detect->isMobile())
            {!! config('settings.google_ads_mobile_2') !!}
            @else
            {!! config('settings.google_ads_desktop_2') !!}
            @endif
          </div>
          @endif
        </div>


        <div class="card mt-4">
          <div class="card-body">
            <h4>{{ __('Add a Comment') }}</h4>
            <form action="{{ route('comments.store') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="comment" class="form-label">{{ __('Your Comment') }}</label>
                <textarea class="form-control" id="comment" name="body" rows="4" required></textarea>
              </div>
              <input type="hidden" name="commentable_id" value="{{ $article->id }}">
              <input type="hidden" name="commentable_type" value="{{ get_class($article) }}">
              <button type="submit" class="btn btn-primary">{{ __('Submit Comment') }}</button>
            </form>


            @foreach($article->comments as $comment)
            <div class="mt-4">
              @php
              $roleColor = $comment->user->hasRole('Admin') ? 'text-danger' :
              ($comment->user->hasRole('Supervisor') ? 'text-warning' : 'text-primary');
              $dividerColor = $roleColor == 'text-danger' ? 'divider-danger' :
              ($roleColor == 'text-warning' ? 'divider-warning' : 'divider-primary');
              @endphp
              <div class="divider {{ $dividerColor }}">
                <div class="divider-text {{ $roleColor }}">
                  {{ $comment->user->name }}
                </div>
              </div>
              <p>{{ $comment->body }}</p>


              <div class="reactions-inline-spacing d-flex justify-content-center">
                <form action="{{ route('reactions.store') }}" method="POST" class="d-inline-block">
                  @csrf
                  <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                  <input type="hidden" name="type" value="like">
                  <button type="submit" class="btn btn-outline-info btn-sm">
                    <i class="ti ti-thumb-up me-1"></i> {{ __('Like') }}
                    <span class="badge bg-white text-info ms-1">
                      {{ $comment->reactions->where('type', 'like')->count() }}
                    </span>
                  </button>
                </form>

                <form action="{{ route('reactions.store') }}" method="POST" class="d-inline-block">
                  @csrf
                  <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                  <input type="hidden" name="type" value="love">
                  <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="ti ti-heart me-1"></i> {{ __('Love') }}
                    <span class="badge bg-white text-danger ms-1">
                      {{ $comment->reactions->where('type', 'love')->count() }}
                    </span>
                  </button>
                </form>

                <form action="{{ route('reactions.store') }}" method="POST" class="d-inline-block">
                  @csrf
                  <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                  <input type="hidden" name="type" value="laugh">
                  <button type="submit" class="btn btn-outline-warning btn-sm">
                    <i class="ti ti-mood-happy me-1"></i> {{ __('Laugh') }}
                    <span class="badge bg-white text-warning ms-1">
                      {{ $comment->reactions->where('type', 'laugh')->count() }}
                    </span>
                  </button>
                </form>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>

@php
$country = session('database', 'jordan');

switch($country) {
case 'sa':
$defaultImageUrl = asset('assets/img/front-pages/icons/articles_saudi_image.jpg');
break;
case 'eg':
$defaultImageUrl = asset('assets/img/front-pages/icons/articles_egypt_image.jpg');
break;
case 'ps':
$defaultImageUrl = asset('assets/img/front-pages/icons/articles_palestine_image.jpg');
break;
default:
$defaultImageUrl = asset('assets/img/front-pages/icons/articles_default_image.jpg');
break;
}

if (!function_exists('getFirstImageFromContent')) {
function getFirstImageFromContent($content, $defaultImageUrl) {
preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches);
  return $matches[1] ?? $defaultImageUrl;
  }
  }

  $firstImageUrl = getFirstImageFromContent($article->content, $defaultImageUrl);
  @endphp

  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ url()->current() }}"
      },
      "headline": "{{ $article->title }}",
      "description": "{{ $article->meta_description }}",
      "author": {
        "@type": "Person",
        "name": "{{ $author ? $author->name : 'Anonymous' }}"
      },
      "datePublished": "{{ $article->created_at->toIso8601String() }}",
      "dateModified": "{{ $article->updated_at->toIso8601String() }}",
      "publisher": {
        "@type": "Organization",
        "name": "{{ config('settings.site_name') ? config('settings.site_name') : 'site_name' }}",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ asset('storage/' . config('settings.site_logo')) }}"
        }
      },
      "image": {
        "@type": "ImageObject",
        "url": "{{ $firstImageUrl }}",
        "width": 800,
        "height": 600
      }
    }
  </script>

  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
          "@type": "ListItem",
          "position": 1,
          "name": "{{ __('Home') }}",
          "item": "{{ url('/') }}"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "{{ __('Classes') }}",
          "item": "{{ route('class.index', ['database' => $database ?? session('database', 'default_database')]) }}"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "{{ $subject->subject_name }}",
          "item": "{{ route('frontend.subjects.show', ['database' => $database ?? session('database', 'default_database'),'subject' => $subject->id]) }}"
        },
        {
          "@type": "ListItem",
          "position": 4,
          "name": "{{ $article->title }}",
          "item": "{{ url()->current() }}"
        }
      ]
    }
  </script>

  @endsection
