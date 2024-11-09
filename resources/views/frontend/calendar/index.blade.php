@extends('layouts.layoutFront')

@section('page-style')
    @vite(['resources/css/calendar.css', 'resources/assets/vendor/js/calendar.js'])
@endsection

@section('content')

<div class="container mt-5">
  <div class="col-md-6 mb-4">
    <h3 class="text-center mb-4">{{ date('F Y') }}</h3>
    <div class="row mb-4">
      <div class="calendar">
        <div class="month-year">
          <span id="month">{{ date('F') }}</span>
          <span class="year">{{ date('Y') }}</span>
        </div>
        <div class="days">
          <div class="day-label">{{ __('Saturday') }}</div>
          <div class="day-label">{{ __('Sunday') }}</div>
          <div class="day-label">{{ __('Monday') }}</div>
          <div class="day-label">{{ __('Tuesday') }}</div>
          <div class="day-label">{{ __('Wednesday') }}</div>
          <div class="day-label">{{ __('Thursday') }}</div>
          <div class="day-label">{{ __('Friday') }}</div>

          @php
          // إعداد التواريخ للشهر الحالي
          $date = new DateTime();
          $daysInMonth = $date->format('t');
          $startDate = clone $date;
          $startDate->modify('first day of this month');
          $firstDayOfWeek = $startDate->format('w');

          // إضافة مربعات فارغة للأيام قبل بداية الشهر الحالي
          for ($i = 0; $i < $firstDayOfWeek; $i++) {
              echo '<div class="day dull"></div>';
          }

          // إضافة أيام الشهر الحالي
          for ($i = 1; $i <= $daysInMonth; $i++) {
              $currentDate = new DateTime($date->format('Y-m') . "-$i");
              $hasEvent = false;

              foreach ($events as $event) {
                  if ($event->event_date == $currentDate->format('Y-m-d')) {
                      $hasEvent = true;
                      break;
                  }
              }

              if ($hasEvent) {
                  echo '<div class="day event" data-title="' . $event->title . '" data-description="' . $event->description . '" data-date="' . $event->event_date . '"><div class="content">' . $i . '</div></div>';
              } elseif ($currentDate->format('j') == $date->format('j')) {
                  echo '<div class="day today"><div class="content">' . $i . '</div></div>';
              } else {
                  echo '<div class="day"><div class="content">' . $i . '</div></div>';
              }
          }
          @endphp

        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 id="modalEventTitle"></h5>
          <p id="modalEventDescription"></p>
          <small id="modalEventDate"></small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

@section('scripts')

@endsection
