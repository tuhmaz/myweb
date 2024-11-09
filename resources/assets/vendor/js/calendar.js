'use strict';
 
document.addEventListener('DOMContentLoaded', function () {
  const eventButtons = document.querySelectorAll('.event');
  const modalEventTitle = document.getElementById('modalEventTitle');
  const modalEventDescription = document.getElementById('modalEventDescription');
  const modalEventDate = document.getElementById('modalEventDate');

  eventButtons.forEach(eventButton => {
    eventButton.addEventListener('click', function () {
      const eventTitle = this.dataset.title;
      const eventDescription = this.dataset.description;
      const eventDate = this.dataset.date;

      modalEventTitle.textContent = eventTitle;
      modalEventDescription.textContent = eventDescription;
      modalEventDate.textContent = `Date: ${eventDate}`;

      const modal = new bootstrap.Modal(document.getElementById('eventModal'));
      modal.show();
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
   const events = document.querySelectorAll('.day.has-event');

  events.forEach(event => {
      event.addEventListener('click', function () {
           const title = this.getAttribute('data-title') || 'No Title';
          const description = this.getAttribute('data-description') || 'No Description';
          const date = this.getAttribute('data-date') || 'No Date';

           document.getElementById('modalEventTitle').textContent = title;
          document.getElementById('modalEventDescription').textContent = description;
          document.getElementById('modalEventDate').textContent = date;
      });
  });
});
