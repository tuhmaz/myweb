<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Event;

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
          return redirect()->route('calendar.index')->withErrors(['error' => 'Invalid month value.']);
      }
      if ($year < 1900 || $year > 2100) {
          return redirect()->route('calendar.index')->withErrors(['error' => 'Invalid year value.']);
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

      // التحقق إذا كان الطلب من لوحة التحكم أو الواجهة الأمامية
      if ($request->is('dashboard/*')) {
          return view('dashboard.calendar.index', compact('days', 'date', 'events'));
      } else {
          return view('frontend.calendar.index', compact('days', 'date', 'events'));
      }
  }


    // إضافة حدث جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
        ]);

        Event::create($validated);
        return redirect()->route('calendar.index')->with('success', 'Event added successfully!');
    }

    // تعديل حدث
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
        ]);

        $event->update($validated);
        return redirect()->route('calendar.index')->with('success', 'Event updated successfully!');
    }

    // حذف حدث
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('calendar.index')->with('success', 'Event deleted successfully!');
    }
}
