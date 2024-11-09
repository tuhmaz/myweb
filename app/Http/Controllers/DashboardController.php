<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Article;
use App\Models\News;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    // دالة لجلب الاتصال المناسب بناءً على الدولة
    private function getConnection(string $country): string
    {
        return match ($country) {
            'saudi' => 'sa',
            'egypt' => 'eg',
            'palestine' => 'ps',
            default => 'jo', // قاعدة البيانات الافتراضية هي الأردن
        };
    }

    public function index(Request $request)
    {
        // جلب البيانات من قاعدة البيانات الرئيسية
        $usersCount = User::count();
        $articlesCount = Article::count();
        $newsCount = News::count();

        // جلب المقالات والأخبار الأحدث من قاعدة البيانات الرئيسية
        $latestArticles = Article::latest()->take(10)->get();
        $latestNews = News::latest()->take(10)->get();

        // جلب عدد المستخدمين بناءً على الأدوار باستخدام حزمة Spatie
        $adminsCount = User::role('Admin')->count();
        $supervisorsCount = User::role('Supervisor')->count();

        // جلب البيانات من قواعد البيانات الفرعية
        $countries = ['saudi', 'egypt', 'palestine'];
        $subdomainArticlesCount = [];
        $subdomainNewsCount = [];
        $subdomainLatestArticles = [];
        $subdomainLatestNews = [];

        foreach ($countries as $country) {
            $connection = $this->getConnection($country);
            $subdomainArticlesCount[$country] = Article::on($connection)->count();
            $subdomainNewsCount[$country] = News::on($connection)->count();
            $subdomainLatestArticles[$country] = Article::on($connection)->latest()->take(10)->get();
            $subdomainLatestNews[$country] = News::on($connection)->latest()->take(10)->get();
        }

        return view('dashboard.index', compact(
            'usersCount',
            'articlesCount',
            'newsCount',
            'latestArticles',
            'latestNews',
            'adminsCount',
            'supervisorsCount',
            'subdomainArticlesCount',
            'subdomainNewsCount',
            'subdomainLatestArticles',
            'subdomainLatestNews'
        ));
    }
}
