<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        if (!$connection) {
            return response()->json(['message' => 'Invalid country selected.'], 400);
        }

        $categories = DB::connection($connection)->table('categories')->get();

        return response()->json([
            'message' => 'Categories fetched successfully.',
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        // لا حاجة لدالة create في الـ API، يتم استخدام دالة store بدلاً من ذلك.
        return response()->json(['message' => 'Use POST /api/categories to create a category'], 405);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string',
        ]);

        $connection = $this->getConnection($validated['country']);

        if (!$connection) {
            return response()->json(['message' => 'Invalid country selected.'], 400);
        }

        try {
            $category = Category::on($connection)->create($validated);
            return response()->json([
                'message' => 'Category created successfully.',
                'category' => $category,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create category.', 'error' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        // لا حاجة لدالة edit في الـ API، يتم استخدام دالة update بدلاً من ذلك.
        return response()->json(['message' => 'Use PUT /api/categories/{id} to update a category'], 405);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string',
        ]);

        $connection = $this->getConnection($validated['country']);

        if (!$connection) {
            return response()->json(['message' => 'Invalid country selected.'], 400);
        }

        $category = Category::on($connection)->findOrFail($id);

        try {
            $category->update($validated);
            return response()->json([
                'message' => 'Category updated successfully.',
                'category' => $category,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update category.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id, Request $request)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        if (!$connection) {
            return response()->json(['message' => 'Invalid country selected.'], 400);
        }

        $category = Category::on($connection)->findOrFail($id);

        try {
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete category.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        if (!$connection) {
            return response()->json(['message' => 'Invalid country selected.'], 400);
        }

        $category = Category::on($connection)->findOrFail($id);

        return response()->json([
            'message' => 'Category fetched successfully.',
            'category' => $category,
        ]);
    }

    private function getConnection($country)
    {
        switch ($country) {
            case 'jordan':
                return 'jo';
            case 'saudi':
                return 'sa';
            case 'egypt':
                return 'eg';
            case 'palestine':
                return 'ps';
            default:
                return null;
        }
    }
}
