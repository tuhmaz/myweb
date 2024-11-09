<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Article;
use App\Models\File;
use App\Models\User;

class GradeOneController extends Controller
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

    return $request->session()->get('database', 'jo');
  }

  public function index(Request $request)
  {
    $database = $this->getConnection($request);


    $lesson = SchoolClass::on($database)->get();
    $classes = SchoolClass::on($database)->get();

    return view('frontend.class.index', compact('lesson', 'classes'));
  }

  public function show(Request $request, $database, $id)
  {
    $database = $this->getConnection($request);
    $class = SchoolClass::on($database)->findOrFail($id);

    return view('frontend.class.show', compact('class', 'database'));
  }



  public function showSubject(Request $request, $database, $id)
  {
    $database = $this->getConnection($request);
    $database = $request->session()->get('database', 'jo');


    $subject = Subject::on($database)->findOrFail($id);


    $gradeLevel = $subject->grade_level;
    $semesters = Semester::on($database)->where('grade_level', $gradeLevel)->get();


    return view('frontend.subject.show', compact('subject', 'semesters', 'database'));
  }

  public function subjectArticles(Request $request, $database, Subject $subject, Semester $semester, $category)
{
    $database = $this->getConnection($request);

    $articles = Article::on($database)
        ->where('subject_id', $subject->id)
        ->where('semester_id', $semester->id)
        ->whereHas('files', function ($query) use ($category) {
            $query->where('file_category', $category);
        })
        ->with(['files' => function ($query) use ($category) {
            $query->where('file_category', $category);
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    $grade_level = $subject->schoolClass->grade_name;

    return view('frontend.articles.index', compact('articles', 'subject', 'semester', 'category', 'grade_level', 'database'));
}


  public function showArticle(Request $request, $database, $id)
  {

    $database = $request->input('database', session('database', 'jo'));


    $article = Article::on($database)->with(['subject', 'semester', 'schoolClass', 'keywords'])->findOrFail($id);


    $file = $article->files()->first();
    $category = $file ? $file->file_category : 'articles';

    $subject = $article->subject;
    $semester = $article->semester;
    $grade_level = $subject->schoolClass->grade_name;

    $article->increment('visit_count');

    $author = User::on('jo')->find($article->author_id);

    $contentWithKeywords = $this->replaceKeywordsWithLinks($article->content, $article->keywords);
    $article->content = $this->createInternalLinks($article->content, $article->keywords);


    return view('frontend.articles.show', compact('article', 'subject', 'semester', 'grade_level', 'category', 'database', 'contentWithKeywords', 'author'));
  }


  private function createInternalLinks($content, $keywords)
  {

    $keywordsArray = $keywords->pluck('keyword')->toArray();

    foreach ($keywordsArray as $keyword) {
      $keyword = trim($keyword);
      $database = $database ?? session('database', 'jo');
      $url = route('keywords.indexByKeyword', ['database' => $database, 'keywords' => $keyword]);
      $content = str_replace($keyword, '<a href="' . $url . '">' . $keyword . '</a>', $content);
    }

    return $content;
  }

  private function replaceKeywordsWithLinks($content, $keywords)
  {
    foreach ($keywords as $keyword) {
      $keywordText = $keyword->keyword;

      // جلب قاعدة البيانات بشكل صحيح من المتغير أو الجلسة
      $database = $database ?? session('database', 'jo');

      // تمرير معلمة database بالإضافة إلى keyword
      $keywordLink = route('keywords.indexByKeyword', ['database' => $database, 'keywords' => $keywordText]);

      // استبدال الكلمة الدلالية بالرابط الخاص بها
      $content = preg_replace('/\b' . preg_quote($keywordText, '/') . '\b/', '<a href="' . $keywordLink . '">' . $keywordText . '</a>', $content);
    }


    return $content;
  }



  public function downloadFile(Request $request, $id)
  {
    $database = $this->getConnection($request);


    $file = File::on($database)->findOrFail($id);


    $file->increment('download_count');


    $filePath = storage_path('app/public/' . $file->file_path);
    if (file_exists($filePath)) {
      return response()->download($filePath);
    }

    return redirect()->back()->with('error', 'File not found.');
  }
}
