<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\Request;

class KeywordController extends Controller
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

    public function index(Request $request,)
    {
        $database = $request->session()->get('database', 'jo');

        $articleKeywords = Keyword::on($database)->whereHas('articles')->get();
        $newsKeywords = Keyword::on($database)->whereHas('news')->get();

        return view('frontend.keywords.index', compact('articleKeywords', 'newsKeywords', 'database'));
    }

    public function indexByKeyword(Request $request, $database, $keyword)
    {
        $database = $request->session()->get('database', 'jo');

        $keywordModel = Keyword::on($database)->where('keyword', $keyword)->firstOrFail();

        $articles = $keywordModel->articles()->get();

        $news = $keywordModel->news()->get();

        return view('frontend.keywords.keyword', [
            'articles' => $articles,
            'news' => $news,
            'keyword' => $keywordModel
        ]);
    }
}
