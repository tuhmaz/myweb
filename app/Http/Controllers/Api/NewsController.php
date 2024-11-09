<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Keyword;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;


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

        return response()->json([
            'news' => $news,
            'categories' => $categories,
        ]);
    }

    public function show(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        $news = News::on($connection)->with('author')->findOrFail($id);

        return response()->json($news);
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

        $metaDescription = $request->meta_description ?: Str::limit(strip_tags($request->description), 120);

        $fileNameToStore = $request->hasFile('image')
            ? $this->storeImage($request->file('image'))
            : $defaultImage;

        $news = News::on($connection)->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'meta_description' => $metaDescription,
            'image' => $fileNameToStore,
            'alt' => $request->input('title'),
            'author_id' => Auth::id(),
        ]);

        if ($request->keywords) {
            $this->attachKeywords($news, $request->input('keywords'), $connection);
        }

        return response()->json(['message' => 'News created successfully', 'news' => $news], 201);
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

        return response()->json(['message' => 'News updated successfully', 'news' => $news]);
    }

    public function destroy(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        $news = News::on($connection)->findOrFail($id);

        $imageName = $news->image;

        $news->delete();

        if ($imageName && $imageName !== 'default.webp') {
            Storage::disk('public')->delete('images/' . $imageName);
        }

        return response()->json(['message' => 'News deleted successfully']);
    }

    private function storeImage($file)
    {
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileNameToStore = $filename . '_' . time() . '.webp';

        $image = Image::make($file->getRealPath());

        $quality = 70;

        $image->encode('webp', $quality);

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
}
