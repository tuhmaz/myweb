<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class FrontendNewsController extends Controller
{
    public function setDatabase(Request $request)
    {
        $request->validate([
            'database' => 'required|string|in:jo,sa,eg,ps'
        ]);

        $request->session()->put('database', $request->input('database'));

        return response()->json(['message' => 'Database connection set successfully']);
    }

    private function getConnection(Request $request)
{
    return $request->input('database', 'jo'); // يمكن قراءة قيمة قاعدة البيانات من المدخلات
}


    public function index(Request $request, $database)
    {
      $database = $this->getConnection($request);

      $categories = Category::on($database)->select('id', 'name', 'slug')->get();

      $query = News::on($database)->with('category');

      if ($request->has('category') && !empty($request->input('category'))) {
          $categorySlug = $request->input('category');

          $category = Category::on($database)->where('slug', $categorySlug)->first();

          if ($category) {
              $query->where('category_id', $category->id);
          } else {

              $query->whereNull('category_id');
          }
      }

      $news = $query->paginate(10);
            return response()->json(['news' => $news, 'categories' => $categories, 'database' => $database]);
        }



        public function show(Request $request, $database, string $id)
            {
        // الاتصال بقاعدة البيانات الفرعية
        $connection = $this->getConnection($request);

        // جلب الخبر من قاعدة البيانات الفرعية مع العلاقة الخاصة بالفئة فقط
        $news = News::on($connection)->with('category')->findOrFail($id);

        // جلب المؤلف من قاعدة البيانات الرئيسية فقط
        $news->author = User::on('jo')->find($news->author_id);  // هنا نستخدم قاعدة البيانات الرئيسية "jo" (أو حسب تسمية قاعدة بياناتك الرئيسية)

        // معالجة الكلمات الدلالية
        $news->description = $this->replaceKeywordsWithLinks($news->description, $news->keywords);
        $news->description = $this->createInternalLinks($news->description, $news->keywords);

        return response()->json(['news' => $news,  'database' => $database]);
    }

    private function replaceKeywordsWithLinks($description, $keywords)
    {
        if (is_string($keywords)) {
            $keywords = array_map('trim', explode(',', $keywords));
        }

        foreach ($keywords as $keyword) {
            $database = session('database', 'jo');
            $keywordText = $keyword->keyword ?? $keyword;
            $keywordLink = route('keywords.indexByKeyword', ['database' => $database, 'keywords' => $keywordText]);
            $description = preg_replace('/\b' . preg_quote($keywordText, '/') . '\b/', '<a href="' . $keywordLink . '">' . $keywordText . '</a>', $description);
        }

        return $description;
    }

    private function createInternalLinks($description, $keywords)
    {
        if (is_string($keywords)) {
            $keywordsArray = array_map('trim', explode(',', $keywords));
        } else {
            $keywordsArray = $keywords->pluck('keyword')->toArray();
        }

        foreach ($keywordsArray as $keyword) {
            $database = session('database', 'jo');
            $url = route('keywords.indexByKeyword', ['database' => $database, 'keywords' => $keyword]);
            $description = str_replace($keyword, '<a href="' . $url . '">' . $keyword . '</a>', $description);
        }

        return $description;
    }

    public function category(Request $request, $translatedCategory)
    {
        $connection = $this->getConnection($request);

        $category = Category::on($connection)->where('name', $translatedCategory)->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $categories = Category::on($connection)->pluck('name', 'id');
        $news = News::on($connection)->where('category_id', $category->id)->paginate(10);

        return response()->json(['news' => $news, 'categories' => $categories, 'category' => $category]);
    }

    public function filterNewsByCategory(Request $request)
    {
        $connection = $this->getConnection($request);

        $categorySlug = $request->input('category');

        $category = Category::on($connection)->where('slug', $categorySlug)->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $news = News::on($connection)
            ->where('category_id', $category->id)
            ->paginate(10);

        if ($news->isEmpty()) {
            return response()->json(['message' => 'No news found for the selected category'], 404);
        }

        return response()->json(['news' => $news]);
    }
}
