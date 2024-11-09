<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\File;
use App\Models\Article;

class FileController extends Controller
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

        $files = File::on($connection)->with('article')->get();

        return response()->json(['files' => $files, 'country' => $country]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'file' => 'required|file',
            'file_category' => 'required|string',
        ]);

        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        $article = Article::on($connection)->findOrFail($request->article_id);
        $class_name = $article->schoolClass->grade_name;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $directory = 'files/' . Str::slug($country) . '/' . Str::slug($class_name) . '/' . $request->file_category;
            $path = $file->storeAs($directory, $filename, 'public');

            $fileData = File::on($connection)->create([
                'article_id' => $request->article_id,
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'file_category' => $request->file_category,
                'file_Name' => $filename,
            ]);

            return response()->json(['message' => 'File uploaded successfully.', 'file' => $fileData]);
        }

        return response()->json(['message' => 'No file was uploaded.'], 400);
    }

    public function show(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        $file = File::on($connection)->findOrFail($id);

        return response()->json($file);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'file' => 'nullable|file',
            'file_category' => 'required|string'
        ]);

        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        $file = File::on($connection)->findOrFail($id);

        if ($request->hasFile('file')) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            $article = Article::on($connection)->findOrFail($request->article_id);
            $class_name = $article->schoolClass->grade_name;
            $uploadedFile = $request->file('file');
            $filename = time() . '_' . $uploadedFile->getClientOriginalName();
            $directory = 'files/' . Str::slug($country) . '/' . Str::slug($class_name) . '/' . $request->file_category;
            $path = $uploadedFile->storeAs($directory, $filename, 'public');

            $file->update([
                'article_id' => $request->article_id,
                'file_path' => $path,
                'file_type' => $uploadedFile->getClientOriginalExtension(),
                'file_category' => $request->file_category,
                'file_Name' => $filename,
            ]);
        }

        if ($request->article_id !== $file->article_id) {
            $file->update(['article_id' => $request->article_id]);
        }

        return response()->json(['message' => 'File updated successfully.', 'file' => $file]);
    }

    public function destroy(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        $file = File::on($connection)->findOrFail($id);

        try {
            if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            $file->delete();

            return response()->json(['message' => 'File deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Error deleting file: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting file.'], 500);
        }
    }

    public function downloadFile(Request $request, $id)
    {
        $database = $request->query('database', session('database', 'jo'));

        if (!$database) {
            return response()->json(['message' => 'Database not specified.'], 400);
        }

        $file = File::on($database)->findOrFail($id);
        $file->increment('download_count');

        $filePath = storage_path('app/public/' . $file->file_path);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return response()->json(['message' => 'File not found.'], 404);
    }
}
