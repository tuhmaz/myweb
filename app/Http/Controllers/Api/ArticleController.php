<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        return response()->json($articles);
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
            OneSignal::sendNotificationToAll([
              'headings' => ['en' => "New Article Published"],
              'contents' => ['en' => "Click to read the latest article!"],
              'url' => url("/articles/{$article->id}") // رابط المقالة
          ]);
            $users = User::all();
            foreach ($users as $user) {
                $user->notify(new ArticleNotification($article));
            }
        });

        return response()->json(['message' => 'Article created successfully'], 201);
    }

    public function show(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);
        $article = Article::on($connection)->with(['files', 'subject', 'semester', 'schoolClass', 'keywords'])->findOrFail($id);
        $article->increment('visit_count');

        return response()->json($article);
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

        return response()->json(['message' => 'Article updated successfully']);
    }

    public function destroy(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        $article = Article::on($connection)->with('files')->findOrFail($id);

        foreach ($article->files as $file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            $file->delete();
        }

        $article->delete();

        return response()->json(['message' => 'Article and associated files deleted successfully']);
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

        return response()->json($articles);
    }
}
