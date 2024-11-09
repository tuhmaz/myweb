<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\SchoolClass;
use App\Models\News;
use App\Models\SitemapExclusion;
use App\Models\Category;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
  public function setDatabase(Request $request)
  {
    $request->validate([
      'database' => 'required|string|in:jo,sa,eg,ps'
    ]);


    $request->session()->put('database', $request->input('database'));

    return redirect()->back();
  }

  private function getConnection(Request $request)
  {
    if ($request->has('database')) {
      $request->session()->put('database', $request->input('database'));
    }
    return $request->session()->get('database', 'jo'); // قاعدة البيانات الافتراضية
  }


  public function index(Request $request)
  {
    $sitemaps = collect(Storage::disk('public')->files('sitemaps'))
      ->filter(function ($file) {
        return Str::endsWith($file, '.xml');
      })->values()->all();

    $database = $this->getConnection($request);

    return view('dashboard.sitemap.index', compact('sitemaps', 'database'));
  }

  public function manageIndex(Request $request)
  {
    if ($request->has('database')) {
      $request->session()->put('database', $request->input('database'));
    }

    $database = $request->session()->get('database', 'jo');
    $connection = $database;

    $articles = Article::on($connection)->get();
    $classes = SchoolClass::on($connection)->get();
    $newsItems = News::on($connection)->get();
    $categories = Category::on($connection)->get();
    $statuses = SitemapExclusion::on($connection)->get()->keyBy(fn($status) => $status->resource_type . '-' . $status->resource_id);

    return view('dashboard.sitemap.manage', compact('articles', 'classes', 'newsItems', 'statuses', 'categories', 'database'));
  }

  public function updateResourceInclusion(Request $request)
  {
    $database = $this->getConnection($request);
    $connection = $database;

    $currentExclusions = SitemapExclusion::on($connection)->get();

    // Loop through articles and update status
    foreach (Article::on($connection)->get() as $article) {
      $key = 'article_' . $article->id;
      if ($request->has($key)) {
        if (!$currentExclusions->contains('resource_type', 'article') || !$currentExclusions->contains('resource_id', $article->id)) {
          SitemapExclusion::on($connection)->create([
            'resource_type' => 'article',
            'resource_id' => $article->id,
            'is_included' => true,
          ]);
        }
      } else {
        SitemapExclusion::on($connection)
          ->where('resource_type', 'article')
          ->where('resource_id', $article->id)
          ->delete();
      }
    }

    // Loop through classes and update status
    foreach (SchoolClass::on($connection)->get() as $class) {
      $key = 'class_' . $class->id;
      if ($request->has($key)) {
        if (!$currentExclusions->contains('resource_type', 'class') || !$currentExclusions->contains('resource_id', $class->id)) {
          SitemapExclusion::on($connection)->create([
            'resource_type' => 'class',
            'resource_id' => $class->id,
            'is_included' => true,
          ]);
        }
      } else {
        SitemapExclusion::on($connection)
          ->where('resource_type', 'class')
          ->where('resource_id', $class->id)
          ->delete();
      }
    }

    // Loop through news and update status
    foreach (News::on($connection)->get() as $news) {
      $key = 'news_' . $news->id;
      if ($request->has($key)) {
        if (!$currentExclusions->contains('resource_type', 'news') || !$currentExclusions->contains('resource_id', $news->id)) {
          SitemapExclusion::on($connection)->create([
            'resource_type' => 'news',
            'resource_id' => $news->id,
            'is_included' => true,
          ]);
        }
      } else {
        SitemapExclusion::on($connection)
          ->where('resource_type', 'news')
          ->where('resource_id', $news->id)
          ->delete();
      }
    }

    // Loop through categories and update status
    foreach (Category::on($connection)->get() as $category) {
      $key = 'category_' . $category->id;
      if ($request->has($key)) {
        if (!$currentExclusions->contains('resource_type', 'category') || !$currentExclusions->contains('resource_id', $category->id)) {
          SitemapExclusion::on($connection)->create([
            'resource_type' => 'category',
            'resource_id' => $category->id,
            'is_included' => true,
          ]);
        }
      } else {
        SitemapExclusion::on($connection)
          ->where('resource_type', 'category')
          ->where('resource_id', $category->id)
          ->delete();
      }
    }

    return redirect()->back()->with('success', 'تم تحديث حالة الأرشفة بنجاح.');
  }

  public function generate(Request $request)
  {
    $database = $this->getConnection($request);

    // Generate sitemaps for the selected database
    $this->generateStaticSitemap($request);
    $this->generateArticlesSitemap($request);
    $this->generateNewsSitemap($request);

    return redirect()->route('sitemap.index')->with('success', 'تم توليد جميع الخرائط بنجاح.');
  }


  private function getFirstImageFromContent($content, $defaultImageUrl)
  {
    preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches);
    return $matches[1] ?? $defaultImageUrl;
  }

  public function generateArticlesSitemap(Request $request)
  {
    $database = $this->getConnection($request);
    $sitemap = Sitemap::create();

    Article::on($database)->get()->each(function (Article $article) use ($sitemap, $database) {
      $url = Url::create(route('frontend.articles.show', ['database' => $database, 'article' => $article->id]))
        ->setLastModificationDate($article->updated_at)
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
        ->setPriority(0.8);

      $defaultImageUrl = asset('assets/img/front-pages/icons/articles_default_image.jpg');
      $imageUrl = $article->image_url ?? $this->getFirstImageFromContent($article->content, $defaultImageUrl);
      $altText = $article->alt ?? $article->title;

      if ($imageUrl) {
        $url->addImage($imageUrl, $altText);
      }

      $sitemap->add($url);
    });

    $fileName = "sitemaps/sitemap_articles_{$database}.xml";
    Storage::disk('public')->put($fileName, $sitemap->render());
  }




  public function generateNewsSitemap(Request $request)
  {
      $database = $this->getConnection($request);

      // Use dynamic database connection
      $newsItems = News::on($database)->get();

      $sitemap = Sitemap::create();

      foreach ($newsItems as $news) {
          $url = Url::create(route('frontend.news.show', ['database' => $database, 'id' => $news->id]))
              ->setLastModificationDate($news->updated_at)
              ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
              ->setPriority(0.7);

          // Check if the image exists, use Storage::url to generate the correct path
          if ($news->image) {
            // Assuming the images are stored in the 'public/images' folder, use Storage::url to generate the URL
            $imagePath = Storage::url('images/' . $news->image);
        } else {
            // Use the default image if no image is uploaded
            $imagePath = asset('assets/img/front-pages/icons/news_default_image.jpg');
        }


          // The alt text is based on the title or custom alt
          $altText = $news->alt ?? $news->title;

          // Add the image to the sitemap if it exists
          if ($imagePath) {
              $url->addImage($imagePath, $altText);
          }

          $sitemap->add($url);
      }

      // Save the sitemap to the public disk
      $fileName = "sitemaps/sitemap_news_{$database}.xml";
      Storage::disk('public')->put($fileName, $sitemap->render());
  }



  public function generateStaticSitemap(Request $request)
  {
    $database = $this->getConnection($request);
    $sitemap = Sitemap::create();

    $sitemap->add(Url::create(route('home'))
      ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
      ->setPriority(1.0));

    SchoolClass::on($database)->get()->each(function (SchoolClass $class) use ($sitemap, $database) {
      $url = Url::create(route('frontend.class.show', ['database' => $database, 'id' => $class->id]))
        ->setLastModificationDate($class->updated_at)
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        ->setPriority(0.6);

      $sitemap->add($url);
    });

    Category::on($database)->get()->each(function (Category $category) use ($sitemap, $database) {
      $url = Url::create(route('frontend.categories.show', ['database' => $database, 'category' => $category->slug]))
        ->setLastModificationDate($category->updated_at)
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        ->setPriority(0.5);

      $sitemap->add($url);
    });

     $fileName = "sitemaps/sitemap_static_{$database}.xml";
    Storage::disk('public')->put($fileName, $sitemap->render());
  }



  public function delete($type, $database)
  {
    $fileName = "sitemaps/sitemap_{$type}_{$database}.xml";

    if (Storage::disk('public')->exists($fileName)) {
      Storage::disk('public')->delete($fileName);
      return redirect()->back()->with('success', 'Sitemap deleted successfully.');
    }

    return redirect()->back()->with('error', 'Sitemap not found.');
  }
}
