<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        return response()->json(['message' => 'Database connection set successfully']);
    }

    private function getConnection(Request $request)
    {
        return $request->session()->get('database', 'jo');
    }

    public function index(Request $request)
    {
        $database = $this->getConnection($request);

        $articleKeywords = Keyword::on($database)->whereHas('articles')->get();
        $newsKeywords = Keyword::on($database)->whereHas('news')->get();

        return response()->json([
            'article_keywords' => $articleKeywords,
            'news_keywords' => $newsKeywords,
            'database' => $database
        ]);
    }

    public function indexByKeyword(Request $request, $keyword)
    {
        $database = $this->getConnection($request);

        $keywordModel = Keyword::on($database)->where('keyword', $keyword)->firstOrFail();

        $articles = $keywordModel->articles()->get();
        $news = $keywordModel->news()->get();

        return response()->json([
            'keyword' => $keywordModel,
            'articles' => $articles,
            'news' => $news
        ]);
    }
}
