<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Keyword;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use OneSignal;

use App\Notifications\FirebaseMessageNotification;
class NewsController extends Controller
{
  private function getConnection(string $country): string
  {
    switch ($country) {
      case 'saudi':
        return 'sa';
      case 'egypt':
        return 'eg';
      case 'palestine':
        return 'ps';
      default:
        return 'jo';
    }
  }

  public function index(Request $request)
  {
    $country = $request->input('country', 'jordan');
    $connection = $this->getConnection($country);

    $categories = Category::on($connection)->select('id', 'name', 'slug')->get();

    $query = News::on($connection)->orderBy('created_at', 'desc');

    if ($request->has('category') && !empty($request->input('category'))) {
      $query->where('category_id', $request->input('category'));
    }

    $news = $query->paginate(10);

    return view('dashboard.news.index', compact('news', 'categories', 'country'));
  }

  public function show($id, Request $request)
  {
    $database = $request->get('database', session('database', 'mysql'));

    config()->set('database.default', $database);

    $news = News::on($database)->findOrFail($id);

    $news = News::on($database)->with('author')->findOrFail($id);

    return view('dashboard.news.show', compact('news'));
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

  public function create(Request $request)
  {
    $country = $request->input('country', 'jordan');
    $connection = $this->getConnection($country);

    $categories = Category::on($connection)->pluck('name', 'id');

    return view('dashboard.news.create', compact('country', 'categories'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|max:60',
      'description' => 'required',
      'category_id' => 'required|exists:categories,id',
      'meta_description' => 'nullable|max:160',
      'keywords' => 'nullable|string',
      'image' => 'nullable|image|max:6999',
      'author_id' => 'nullable|exists:users,id'
    ]);


    $country = $request->input('country', 'jordan');
    $connection = $this->getConnection($country);

    $defaultImage = match ($country) {
      'jo' => 'noimage.svg',
      'saudi' => 'newssa.svg',
      'egypt' => 'newseg.svg',
      'palestine' => 'newsps.svg',
      default => 'noimage.svg',
    };

    DB::connection($connection)->transaction(function () use ($request, $validated, $country, $connection, $defaultImage) {
      $metaDescription = $request->meta_description ?: Str::limit(strip_tags($request->description), 120);

      $fileNameToStore = $request->hasFile('image')
        ? $this->storeImage($request->file('image'))
        : $defaultImage;

      $imageAltText = $request->input('title');
      $authorId = Auth::user()->id;

      $news = News::on($connection)->create([
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'category_id' => $request->input('category_id'),
        'meta_description' => $metaDescription,
        'image' => $fileNameToStore,
        'alt' => $imageAltText,
        'author_id' => $authorId,
      ]);


      if ($request->keywords) {
        $this->attachKeywords($news, $request->input('keywords'), $connection);
      }

       // إرسال إشعار باستخدام OneSignal عند إضافة خبر جديد
       OneSignal::sendNotificationToAll(
        "تم نشر خبر جديد: {$news->title}",
      //  $url = route('news.show', ['country' => $country, 'id' => $news->id]),
        $data = null,
        $buttons = null,
        $schedule = null
    );
});


    return redirect()->route('news.index', ['country' => $country])
      ->with('success', 'News Created Successfully');
  }



  private function storeImage($file)
  {
      $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
      $fileNameToStore = $filename . '_' . time() . '.webp';

      $image = Image::make($file->getRealPath());

      // Define quality (optional)
      $quality = 70; // Value between 0 and 100

      // Convert the image to WebP format
      $image->encode('webp', $quality);

      // Save the image to storage
      Storage::disk('public')->put('images/' . $fileNameToStore, (string) $image);

      return $fileNameToStore;
  }



  private function attachKeywords($news, $keywords, $connection)
  {
    $keywordsArray = array_map('trim', explode(',', $keywords));
    foreach ($keywordsArray as $keyword) {
      $keywordModel = Keyword::on($connection)->firstOrCreate(['keyword' => $keyword]);
      $news->keywords()->attach($keywordModel->id);
    }
  }

  public function edit(Request $request, $id)
  {
    $country = $request->input('country', 'jordan');
    $connection = $this->getConnection($country);

    $news = News::on($connection)->findOrFail($id);

    $categories = Category::on($connection)->pluck('name', 'id');

    return view('dashboard.news.edit', compact('news', 'country', 'categories'));
  }


  public function update(Request $request, $id)
  {
    $country = $request->input('country', 'jordan');
    $connection = $this->getConnection($country);

    $request->validate([
      'title' => 'required|max:60',
      'description' => 'required',
      'category_id' => 'required|exists:categories,id',
      'meta_description' => 'required|max:160',
      'keywords' => 'nullable|string',
      'image' => 'nullable|image|max:6999',
      'author_id' => 'nullable|exists:users,id'
    ]);

    $news = News::on($connection)->findOrFail($id);
    $news->update($request->only('title', 'description', 'category_id', 'meta_description'));

    if ($request->hasFile('image')) {
      $fileNameToStore = $this->storeImage($request->file('image'));
      $news->update(['image' => $fileNameToStore]);
    }

    if ($request->keywords) {
      $this->attachKeywords($news, $request->input('keywords'), $connection);
    }

    return redirect()->route('news.index', ['country' => $country])->with('success', 'News Updated');
  }

  public function destroy(Request $request, $id)
{
    $country = $request->input('country', 'jordan');
    $connection = $this->getConnection($country);

    $news = News::on($connection)->findOrFail($id);

    // الحصول على اسم ملف الصورة المرتبطة بالخبر قبل حذفه
    $imageName = $news->image;

    // حذف الخبر من قاعدة البيانات
    $news->delete();

    // التحقق من أن الصورة ليست الصورة الافتراضية وحذفها من التخزين
    if ($imageName && $imageName !== 'default.webp') {
        // استخدام Storage Facade لحذف الملف
        Storage::disk('public')->delete('images/' . $imageName);
    }

    return redirect()->route('news.index', ['country' => $country])
        ->with('success', 'تم حذف الخبر والصورة المرتبطة به بنجاح');
}

}
