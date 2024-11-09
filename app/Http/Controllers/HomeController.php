<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\File;
use App\Models\Category;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Event;

class HomeController extends Controller
{
  public function setDatabase(Request $request)
  {
    $request->validate([
      'database' => 'required|string|in:jo,sa,eg,ps'
    ]);


    $request->session()->put('database', $request->input('database'));


    return redirect()->route('home');
  }

  /**
   *
   */
  private function getDatabaseConnection(): string
  {
    return session('database', 'jo');
  }

  /**
   *
   */
  public function index(Request $request)
  {
      // الحصول على اتصال قاعدة البيانات الديناميكي
      $database = $this->getDatabaseConnection();

      // Cache الأخبار من قاعدة البيانات المتصلة
      $news = Cache::remember("news_{$database}", 60, function () use ($database) {
          return News::on($database)->with('category')->get();
      });

      // Cache الصفوف الدراسية من قاعدة البيانات المتصلة
      $classes = Cache::remember("classes_{$database}", 60, function () use ($database) {
          return SchoolClass::on($database)->get();
      });

      // جلب الملفات من قاعدة البيانات المتصلة
      $query = File::on($database);

      // تطبيق الفلاتر بناءً على المدخلات من المستخدم للصف، المادة، الفصل الدراسي وفئة الملف
      if ($request->class_id) {
          $query->whereHas('article.semester.subject.schoolClass', function ($q) use ($request) {
              $q->where('id', $request->class_id);
          });
      }

      if ($request->subject_id) {
          $query->whereHas('article.semester.subject', function ($q) use ($request) {
              $q->where('id', $request->subject_id);
          });
      }

      if ($request->semester_id) {
          $query->whereHas('article.semester', function ($q) use ($request) {
              $q->where('id', $request->semester_id);
          });
      }

      if ($request->file_category) {
          $query->where('file_category', $request->file_category);
      }

      // جلب الملفات بناءً على الاستعلام
      $files = $query->get();

      // جلب الفئات من قاعدة البيانات
      $categories = Category::on($database)->get();

      // إعداد البيانات الخاصة بالتقويم
      $month = $request->input('month', date('m'));
      $year = $request->input('year', date('Y'));

      $date = Carbon::createFromDate($year, $month, 1);

      $startOfCalendar = $date->copy()->startOfMonth()->startOfWeek(Carbon::FRIDAY);
      $endOfCalendar = $date->copy()->endOfMonth()->endOfWeek(Carbon::FRIDAY);

      $days = collect();
      $currentDate = $startOfCalendar->copy();
      while ($currentDate <= $endOfCalendar) {
          $days->push($currentDate->copy());
          $currentDate->addDay();
      }

      // جلب الأحداث بناءً على الشهر والسنة
      $events = Event::whereMonth('event_date', $month)->whereYear('event_date', $year)->get();

      // تمرير البيانات إلى العرض
      if (Auth::check()) {
          $user = Auth::user();
          return view('content.pages.home', compact('user', 'news', 'classes', 'categories', 'files', 'days', 'date', 'events'));
      } else {
          return view('content.pages.home', compact('news', 'classes', 'categories', 'files', 'days', 'date', 'events'));
      }
  }



  public function about()
  {
    return view('about');
  }

  public function contact()
  {
    return view('contact');
  }




}
