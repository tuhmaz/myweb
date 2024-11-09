<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\SchoolClass;
use App\Models\File;
use App\Models\User;
use App\Models\Keyword;
use App\Notifications\ArticleNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use OneSignal;

class ArticleController extends Controller
{
  private function getConnection(string $country): string
  {
    return match ($country) {
      'saudi' => 'sa',
      'egypt' => 'eg',
      'palestine' => 'ps',
      default => 'jo',
    };
  }


  public function index(Request $request)
  {
      $country = $request->input('country', 'jordan');
      $connection = $this->getConnection($country);

      $articles = Article::on($connection)
          ->with(['schoolClass', 'subject', 'semester', 'keywords'])

          ->paginate(25);

      return view('dashboard.articles.index', compact('articles', 'country'));
  }

  public function create(Request $request)
  {
      $country = $request->input('country', 'jordan');
      $connection = $this->getConnection($country);

      $classes = SchoolClass::on($connection)->get();
      $subjects = Subject::on($connection)->get();
      $semesters = Semester::on($connection)->get();

      return view('dashboard.articles.create', compact('classes', 'subjects', 'semesters', 'country'));
  }

  public function store(Request $request)
  {
      $validated = $request->validate([
          'class_id' => 'required|exists:school_classes,id',
          'subject_id' => 'required|exists:subjects,id',
          'semester_id' => 'required|exists:semesters,id',
          'title' => 'required|string|max:60',
          'content' => 'required',
          'keywords' => 'nullable|string',
          'file_category' => 'required|string',
          'file' => 'nullable|file',
          'meta_description' => 'nullable|string|max:120',
      ]);

      $country = $request->input('country', 'jordan');
      $connection = $this->getConnection($country);

      DB::connection($connection)->transaction(function () use ($request, $validated, $country, $connection) {

          $metaDescription = $request->meta_description;

          if ($request->use_title_for_meta && !$metaDescription) {
              $metaDescription = Str::limit($request->title, 120);
          }

          if ($request->use_keywords_for_meta && !$metaDescription && $request->keywords) {
              $metaDescription = Str::limit($request->keywords, 120);
          }

          if (!$metaDescription) {
              $metaDescription = Str::limit(strip_tags($request->content), 120);
          }

          $article = Article::on($connection)->create([
              'grade_level' => $request->class_id,
              'subject_id' => $request->subject_id,
              'semester_id' => $request->semester_id,
              'title' => $request->title,
              'content' => $request->content,
              'meta_description' => $metaDescription,
              'author_id' => Auth::id(),
          ]);

          if ($request->keywords) {
              $keywords = array_map('trim', explode(',', $request->keywords));

              foreach ($keywords as $keyword) {
                  $keywordModel = Keyword::on($connection)->firstOrCreate(['keyword' => $keyword]);
                  $article->keywords()->attach($keywordModel->id);
              }
          }

          if ($request->hasFile('file')) {
              $schoolClass = SchoolClass::on($connection)->find($request->class_id);
              $folderName = $schoolClass ? $schoolClass->grade_name : 'General';
              $folderCategory = Str::slug($request->file_category);

              $country = $request->input('country', 'default_country');
              $originalFilename = $request->file('file')->getClientOriginalName();
              $filename = $request->input('file_Name') ? $request->input('file_Name') : $originalFilename;

              $folderNameSlug = Str::slug($folderName);
              $countrySlug = Str::slug($country);

              $folderPath = "files/$countrySlug/$folderNameSlug/$folderCategory";
              $path = $request->file('file')->storeAs($folderPath, $filename, 'public');

              File::on($connection)->create([
                  'article_id' => $article->id,
                  'file_path' => $path,
                  'file_type' => $request->file('file')->getClientOriginalExtension(),
                  'file_category' => $request->file_category,
                  'file_Name' => $filename,
              ]);
          }
            OneSignal::sendNotificationToAll(
              "تم نشر مقال جديد: {$article->title} (الصف: " . SchoolClass::on($connection)->find($article->grade_level)->grade_name . ")",
              $data =null,
              $buttons = null,
              $schedule = null
            );





          $users = User::all();
          foreach ($users as $user) {
              $user->notify(new ArticleNotification($article));
          }
      });


      return redirect()->route('articles.index', ['country' => $country])->with('success', 'Article created successfully.');
  }

  public function show(Request $request, $id)
  {
      $country = $request->input('country', 'jordan');
      $connection = $this->getConnection($country);
      $article = Article::on($connection)->with(['files', 'subject', 'semester', 'schoolClass', 'keywords'])->findOrFail($id);
      $article->increment('visit_count');
      $contentWithKeywords = $this->replaceKeywordsWithLinks($article->content, $article->keywords);
      $article->content = $this->createInternalLinks($article->content, $article->keywords);

      return view('dashboard.articles.show', compact('article', 'contentWithKeywords', 'country'));
  }

  private function replaceKeywordsWithLinks($content, $keywords)
  {
      foreach ($keywords as $keyword) {
        $database = session('database', 'jo');
          $keywordText = $keyword->keyword;
          $keywordLink = route('keywords.indexByKeyword', ['database' => $database,'keywords' => $keywordText]);
          $content = preg_replace('/\b' . preg_quote($keywordText, '/') . '\b/', '<a href="' . $keywordLink . '">' . $keywordText . '</a>', $content);
      }

      return $content;
  }

  public function edit(Request $request, $id)
  {
      $country = $request->input('country', 'jordan');
      $connection = $this->getConnection($country);

      $article = Article::on($connection)->with('files')->findOrFail($id);
      $classes = SchoolClass::on($connection)->get();
      $subjects = Subject::on($connection)->where('grade_level', $article->grade_level)->get();
      $semesters = Semester::on($connection)->where('grade_level', $article->grade_level)->get();

      return view('dashboard.articles.edit', compact('article', 'classes', 'subjects', 'semesters', 'country'));
  }

  public function update(Request $request, $id)
  {
      $validated = $request->validate([
          'class_id' => 'required|exists:school_classes,id',
          'subject_id' => 'required|exists:subjects,id',
          'semester_id' => 'required|exists:semesters,id',
          'title' => 'required|string|max:60',
          'content' => 'required',
          'keywords' => 'nullable|string',
          'file_category' => 'required|string',
          'file' => 'nullable|file',
          'meta_description' => 'nullable|string|max:120',
      ]);

      $country = $request->input('country', 'jordan');
      $connection = $this->getConnection($country);

      $article = Article::on($connection)->findOrFail($id);

      $metaDescription = $request->meta_description;
      if (!$metaDescription) {
          if ($request->keywords) {
              $metaDescription = Str::limit($request->keywords, 120);
          } else {
              $metaDescription = Str::limit(strip_tags($request->content), 120);
          }
      }

      $article->update([
          'subject_id' => $request->subject_id,
          'semester_id' => $request->semester_id,
          'title' => $request->title,
          'content' => $request->content,
          'meta_description' => $metaDescription,
      ]);

      if ($request->keywords) {
          $keywords = array_map('trim', explode(',', $request->keywords));

          $article->keywords()->detach();

          foreach ($keywords as $keyword) {
              $keywordModel = Keyword::on($connection)->firstOrCreate(['keyword' => $keyword]);
              $article->keywords()->attach($keywordModel->id);
          }
      }

      if ($request->hasFile('new_file')) {
          $currentFile = $article->files->first();
          if ($currentFile) {
              Storage::disk('public')->delete($currentFile->file_path);
              $currentFile->delete();
          }

          $schoolClass = SchoolClass::on($connection)->find($request->class_id);
          $folderName = $schoolClass ? $schoolClass->grade_name : 'General';
          $folderCategory = Str::slug($request->file_category);
          $folderNameSlug = Str::slug($folderName);
          $folderPath = "files/$folderNameSlug/$folderCategory";
          $filename = time() . '_' . $request->file('new_file')->getClientOriginalName();
          $path = $request->file('new_file')->storeAs($folderPath, $filename, 'public');

          File::on($connection)->create([
              'article_id' => $article->id,
              'file_path' => $path,
              'file_type' => $request->file('new_file')->getClientOriginalExtension(),
              'file_category' => $request->file_category,
              'file_Name' => $filename
          ]);
      }

      return redirect()->route('articles.index', ['country' => $country])->with('success', 'Article updated successfully.');
  }

  public function destroy(Request $request, $id)
{
    $country = $request->input('country', 'jordan');
    $connection = $this->getConnection($country);

    // جلب المقال مع الصور والملفات المرتبطة به
    $article = Article::on($connection)->with('files')->findOrFail($id);

    // حذف الصورة المرتبطة بالمقال (إذا كانت موجودة وليست الصورة الافتراضية)
    $imageName = $article->image;

    if ($imageName && $imageName !== 'default.webp') {
        if (Storage::disk('public')->exists('images/' . $imageName)) {
            Storage::disk('public')->delete('images/' . $imageName);
        }
    }

    // حذف الملفات المرتبطة بالمقال
    foreach ($article->files as $file) {
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }
        $file->delete();
    }

    // حذف المقال من قاعدة البيانات
    $article->delete();

    return redirect()->route('articles.index', ['country' => $country])
        ->with('success', 'تم حذف المقال والصور والملفات المرتبطة به بنجاح.');
}

  public function indexByClass(Request $request, $grade_level)
  {
      $country = $request->input('country', 'jordan');
      $connection = $this->getConnection($country);

      $articles = Article::on($connection)
          ->whereHas('subject', function ($query) use ($grade_level) {
              $query->where('grade_level', $grade_level);
          })
          ->get();

      return view('dashboard.articles.index', compact('articles', 'country'));
  }

  private function createInternalLinks($content, $keywords)
  {
      $keywordsArray = $keywords->pluck('keyword')->toArray();

      foreach ($keywordsArray as $keyword) {
        $database = session('database', 'jo');
          $keyword = trim($keyword);
          $url = route('keywords.indexByKeyword', ['database' => $database,'keywords' => $keyword]);
          $content = str_replace($keyword, '<a href="' . $url . '">' . $keyword . '</a>', $content);
      }

      return $content;
  }

  public function indexByKeyword(Request $request, $keyword)
  {
      $country = $request->input('country', 'jordan');
      $connection = $this->getConnection($country);
      $keywordModel = Keyword::on($connection)->where('keyword', $keyword)->firstOrFail();
      $articles = $keywordModel->articles()->get();
      return view('frontend.keywords.keyword', [
          'articles' => $articles,
          'keyword' => $keywordModel
      ]);
  }
}
