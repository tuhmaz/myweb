<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\File;
use App\Models\Article;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    // دالة لاسترجاع قاعدة البيانات المناسبة
    private function getConnection(Request $request): string
    {
        return $request->query('database', session('database', 'jo'));
    }

    // عرض صفحة الفلترة (API JSON)
    public function index(Request $request)
    {
        // استعادة قاعدة البيانات المحددة
        $database = $this->getConnection($request);

        // جلب الصفوف من قاعدة البيانات المناسبة
        $classes = SchoolClass::on($database)->get();

        // تجهيز الاستعلام لجلب المقالات بناءً على التصفية
        $query = Article::on($database);

        // تصفية بناءً على الصف
        if ($request->class_id) {
            $query->whereHas('semester', function ($q) use ($request) {
                $q->where('grade_level', $request->class_id);
            });
        }

        // تصفية بناءً على المادة
        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        // تصفية بناءً على الفصل الدراسي
        if ($request->semester_id) {
            $query->where('semester_id', $request->semester_id);
        }

        // تصفية بناءً على نوع الملف وجلب المقالات المرتبطة
        if ($request->file_category) {
            $query->whereHas('files', function ($q) use ($request) {
                $q->where('file_category', $request->file_category);
            });
        }

        // جلب المقالات المصفاة
        $articles = $query->get();

        // جلب الملفات المرتبطة بالمقالات المصفاة من قاعدة البيانات المناسبة
        $files = File::on($database)->whereIn('article_id', $articles->pluck('id'))->get();

        // إعداد الرد بصيغة JSON
        return response()->json([
            'classes' => $classes,
            'articles' => $articles,
            'files' => $files,
            'database' => $database,
        ]);
    }

    // جلب المواد بناءً على الصف
    public function getSubjectsByClass(Request $request, $classId)
    {
        $database = $this->getConnection($request);

        // جلب الصف المحدد
        $schoolClass = SchoolClass::on($database)->find($classId);

        if (!$schoolClass) {
            return response()->json(['message' => 'Class not found'], 404);
        }

        // جلب المواد التي تنتمي إلى الصف المحدد
        $subjects = Subject::on($database)->where('grade_level', $schoolClass->grade_level)->get();

        if ($subjects->isEmpty()) {
            return response()->json(['message' => 'No subjects found'], 404);
        }

        return response()->json($subjects);
    }

    // جلب الفصول بناءً على المادة
    public function getSemestersBySubject(Request $request, $subjectId)
    {
        $database = $this->getConnection($request);

        // جلب المادة المحددة
        $subject = Subject::on($database)->find($subjectId);

        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        // جلب الفصول الدراسية التي تنتمي إلى المادة
        $semesters = Semester::on($database)->where('grade_level', $subject->grade_level)->get();

        if ($semesters->isEmpty()) {
            return response()->json(['message' => 'No semesters found'], 404);
        }

        return response()->json($semesters);
    }

    // جلب أنواع الملفات بناءً على الفصل
    public function getFileTypesBySemester(Request $request, $semesterId)
    {
        $database = $this->getConnection($request);

        // جلب أنواع الملفات بناءً على الفصل الدراسي المحدد
        $fileTypes = File::on($database)
            ->whereHas('article.semester', function ($q) use ($semesterId) {
                $q->where('id', $semesterId);
            })
            ->distinct()
            ->pluck('file_type'); // جلب الأنواع الفريدة من الملفات

        return response()->json($fileTypes);
    }
}
