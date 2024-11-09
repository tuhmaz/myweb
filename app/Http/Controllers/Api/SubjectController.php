<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SubjectController extends Controller
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

        $subjects = Subject::on($connection)->with('schoolClass')->get();
        $groupedSubjects = $subjects->groupBy(function ($subject) {
            return $subject->schoolClass->grade_name;
        });

        return response()->json($groupedSubjects);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'grade_level' => 'required|exists:school_classes,grade_level',
            'country' => 'required|string'
        ]);

        $connection = $this->getConnection($request->input('country'));

        $subject = Subject::on($connection)->create([
            'subject_name' => $request->subject_name,
            'grade_level' => $request->grade_level
        ]);

        return response()->json(['message' => 'Subject created successfully', 'subject' => $subject], 201);
    }

    public function show(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        $subject = Subject::on($connection)->findOrFail($id);

        return response()->json($subject);
    }

    public function edit(Request $request, $id)
    {
        // Note: edit method is typically used for displaying a view in web applications.
        // For an API, editing is handled by fetching the resource and then allowing updates via the `update` method.
        return response()->json(['message' => 'Edit action not required for API']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'grade_level' => 'required|exists:school_classes,grade_level',
            'country' => 'required|string'
        ]);

        $connection = $this->getConnection($request->input('country'));

        $subject = Subject::on($connection)->findOrFail($id);

        $subject->update([
            'subject_name' => $request->subject_name,
            'grade_level' => $request->grade_level
        ]);

        return response()->json(['message' => 'Subject updated successfully', 'subject' => $subject]);
    }

    public function destroy(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');
        $connection = $this->getConnection($country);

        $subject = Subject::on($connection)->findOrFail($id);
        $subject->delete();

        return response()->json(['message' => 'Subject deleted successfully']);
    }
}
