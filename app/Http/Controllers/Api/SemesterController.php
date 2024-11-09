<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
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

        $semesters = Semester::on($connection)->with('schoolClass')->get();
        $groupedSemesters = $semesters->groupBy(function ($semester) {
            return $semester->schoolClass->grade_name;
        });

        return response()->json($groupedSemesters);
    }

    public function store(Request $request)
    {
        $request->validate([
            'semester_name' => 'required|string|max:255',
            'grade_level' => 'required|exists:school_classes,id',
            'country' => 'required|string',
        ]);

        $connection = $this->getConnection($request->input('country'));

        $semester = Semester::on($connection)->create([
            'semester_name' => $request->semester_name,
            'grade_level' => $request->grade_level,
        ]);

        return response()->json(['message' => 'Semester created successfully', 'semester' => $semester], 201);
    }

    public function show(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        $semester = Semester::on($connection)->findOrFail($id);

        return response()->json($semester);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'semester_name' => 'required|string|max:255',
            'grade_level' => 'required|exists:school_classes,id',
            'country' => 'required|string',
        ]);

        $connection = $this->getConnection($request->input('country'));

        $semester = Semester::on($connection)->findOrFail($id);
        $semester->update([
            'semester_name' => $request->semester_name,
            'grade_level' => $request->grade_level,
        ]);

        return response()->json(['message' => 'Semester updated successfully', 'semester' => $semester]);
    }

    public function destroy(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        $semester = Semester::on($connection)->findOrFail($id);
        $semester->delete();

        return response()->json(['message' => 'Semester deleted successfully']);
    }
}

