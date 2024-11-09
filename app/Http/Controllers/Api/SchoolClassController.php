<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolClassController extends Controller
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
        $schoolClasses = DB::connection($connection)->table('school_classes')->get();

        return response()->json([
            'status' => true,
            'message' => 'Classes fetched successfully',
            'data' => $schoolClasses
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'grade_name' => 'required|string|max:255',
            'grade_level' => 'required|integer',
            'country' => 'required|string',
        ]);

        $connection = $this->getConnection($request->input('country'));

        DB::connection($connection)->table('school_classes')->insert([
            'grade_name' => $request->input('grade_name'),
            'grade_level' => $request->input('grade_level'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Class added successfully in ' . $request->input('country') . ' database.'], 201);
    }

    public function show(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        $schoolClass = DB::connection($connection)->table('school_classes')->where('id', $id)->first();

        if (!$schoolClass) {
            return response()->json(['message' => 'Class not found'], 404);
        }

        return response()->json($schoolClass);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'grade_name' => 'required|string|max:255',
            'grade_level' => 'required|integer',
            'country' => 'required|string',
        ]);

        $connection = $this->getConnection($request->input('country'));

        $updated = DB::connection($connection)->table('school_classes')->where('id', $id)->update([
            'grade_name' => $request->input('grade_name'),
            'grade_level' => $request->input('grade_level'),
            'updated_at' => now(),
        ]);

        if ($updated) {
            return response()->json(['message' => 'Class updated successfully.']);
        }

        return response()->json(['message' => 'Class not found'], 404);
    }

    public function destroy(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        $deleted = DB::connection($connection)->table('school_classes')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(['message' => 'Class deleted successfully.']);
        }

        return response()->json(['message' => 'Class not found'], 404);
    }
}
