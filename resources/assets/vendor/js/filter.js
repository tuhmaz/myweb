'use strict';
document.getElementById('class_id').addEventListener('change', function() {
  var classId = this.value;

  // Filter subjects
  var subjectOptions = document.querySelectorAll('#subject_id .subject-option');
  subjectOptions.forEach(function(option) {
      if (option.getAttribute('data-class') === classId || classId === '') {
          option.style.display = 'block';
      } else {
          option.style.display = 'none';
      }
  });

  // Filter semesters
  var semesterOptions = document.querySelectorAll('#semester_id .semester-option');
  semesterOptions.forEach(function(option) {
      if (option.getAttribute('data-class') === classId || classId === '') {
          option.style.display = 'block';
      } else {
          option.style.display = 'none';
      }
  });
});
