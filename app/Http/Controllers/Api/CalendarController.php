<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function calendar(Request $request, $month = null, $year = null)
    {
        // إعداد القيم الافتراضية
        if (!$month) {
            $month = date('m');
        }
        if (!$year) {
            $year = date('Y');
        }

        // التحقق من القيم
        if ($month < 1 || $month > 12) {
            return response()->json(['error' => 'Invalid month value.'], 400);
        }
        if ($year < 1900 || $year > 2100) {
            return response()->json(['error' => 'Invalid year value.'], 400);
        }

        // إعداد التاريخ
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

        return response()->json([
            'message' => 'Calendar fetched successfully.',
            'days' => $days,
            'events' => $events,
        ]);
    }

    // إضافة حدث جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
        ]);

        try {
            $event = Event::create($validated);
            return response()->json([
                'message' => 'Event added successfully!',
                'event' => $event,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add event.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // تعديل حدث
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
        ]);

        $event = Event::findOrFail($id);

        try {
            $event->update($validated);
            return response()->json([
                'message' => 'Event updated successfully!',
                'event' => $event,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update event.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // حذف حدث
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        try {
            $event->delete();
            return response()->json(['message' => 'Event deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete event.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
