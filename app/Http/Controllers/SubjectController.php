<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $country = $request->input('country', 'jordan');

        switch ($country) {
            case 'jordan':
                $connection = 'jo';
                break;
            case 'saudi':
                $connection = 'sa';
                break;
            case 'egypt':
                $connection = 'eg';
                break;
            case 'palestine':
                $connection = 'ps';
                break;
            default:
                $connection = 'jo';
        }

        $subjects = Subject::on($connection)->with('schoolClass')->get();
        $groupedSubjects = $subjects->groupBy(function ($subject) {
            return $subject->schoolClass->grade_name;
        });

        return view('dashboard.subjects.index', compact('groupedSubjects', 'country'));
    }

    public function create(Request $request)
    {
        $country = $request->input('country', 'jordan');

        switch ($country) {
            case 'jordan':
                $connection = 'jo';
                break;
            case 'saudi':
                $connection = 'sa';
                break;
            case 'egypt':
                $connection = 'eg';
                break;
            case 'palestine':
                $connection = 'ps';
                break;
            default:
                $connection = 'jo';
        }

        $classes = SchoolClass::on($connection)->get();

        return view('dashboard.subjects.create', compact('classes', 'country'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'grade_level' => 'required|exists:school_classes,grade_level'
        ]);

        $country = $request->input('country', 'jordan');
        switch ($country) {
            case 'jordan':
                $connection = 'jo';
                break;
            case 'saudi':
                $connection = 'sa';
                break;
            case 'egypt':
                $connection = 'eg';
                break;
            case 'palestine':
                $connection = 'ps';
                break;
            default:
                $connection = 'jo';
        }

        Subject::on($connection)->create([
            'subject_name' => $request->subject_name,
            'grade_level' => $request->grade_level
        ]);

        return redirect()->route('subjects.index', ['country' => $country])->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        return view('dashboard.subjects.show', compact('subject'));
    }

    public function edit(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');

        switch ($country) {
            case 'jordan':
                $connection = 'jo';
                break;
            case 'saudi':
                $connection = 'sa';
                break;
            case 'egypt':
                $connection = 'eg';
                break;
            case 'palestine':
                $connection = 'ps';
                break;
            default:
                $connection = 'jo';
        }

        $subject = Subject::on($connection)->findOrFail($id);
        $classes = SchoolClass::on($connection)->get();

        return view('dashboard.subjects.edit', compact('subject', 'classes', 'country'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'grade_level' => 'required|exists:school_classes,grade_level'
        ]);

        $country = $request->input('country', 'jordan');
        switch ($country) {
            case 'jordan':
                $connection = 'jo';
                break;
            case 'saudi':
                $connection = 'sa';
                break;
            case 'egypt':
                $connection = 'eg';
                break;
            case 'palestine':
                $connection = 'ps';
                break;
            default:
                $connection = 'jo';
        }

        $subject->setConnection($connection)->update([
            'subject_name' => $request->subject_name,
            'grade_level' => $request->grade_level
        ]);

        return redirect()->route('subjects.index', ['country' => $country])->with('success', 'Subject updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $country = $request->input('country', 'jordan');

        switch ($country) {
            case 'jordan':
                $connection = 'jo';
                break;
            case 'saudi':
                $connection = 'sa';
                break;
            case 'egypt':
                $connection = 'eg';
                break;
            case 'palestine':
                $connection = 'ps';
                break;
            default:
                $connection = 'jo';
        }

        $subject = Subject::on($connection)->findOrFail($id);
        $subject->delete();

        return redirect()->route('subjects.index', ['country' => $country])->with('success', 'Subject deleted successfully.');
    }
}
