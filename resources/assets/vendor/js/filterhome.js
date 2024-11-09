'use strict';
document.addEventListener('DOMContentLoaded', function() {
  const classSelect = document.getElementById('class-select');
  const subjectSelect = document.getElementById('subject-select');
  const semesterSelect = document.getElementById('semester-select');

  if (classSelect) {
      classSelect.addEventListener('change', function() {
          const classId = this.value;

          if (classId) {
              fetch(`/api/subjects/${classId}`)
                  .then(response => response.json())
                  .then(data => {
                      subjectSelect.innerHTML = '<option value="">أختار المادة</option>';
                      semesterSelect.innerHTML = '<option value="">أختار الفصل</option>';
                      semesterSelect.disabled = true; // تعطيل قائمة الفصول حتى يتم اختيار المادة

                      if (data.message) {
                          console.error(data.message);
                      } else {
                          data.forEach(subject => {
                              subjectSelect.innerHTML += `<option value="${subject.id}">${subject.subject_name}</option>`;
                          });

                          subjectSelect.disabled = false;
                      }
                  })
                  .catch(error => console.error('Error:', error));
          } else {
              subjectSelect.innerHTML = '<option value="">أختار المادة</option>';
              subjectSelect.disabled = true;
              semesterSelect.innerHTML = '<option value="">أختار الفصل</option>';
              semesterSelect.disabled = true;
          }
      });

      subjectSelect.addEventListener('change', function() {
          const subjectId = this.value;

          if (subjectId) {
              fetch(`/api/semesters/${subjectId}`)
                  .then(response => response.json())
                  .then(data => {
                      semesterSelect.innerHTML = '<option value="">أختار الفصل</option>';

                      if (data.message) {
                          console.error(data.message);
                      } else {
                          data.forEach(semester => {
                              semesterSelect.innerHTML += `<option value="${semester.id}">${semester.semester_name}</option>`;
                          });

                          semesterSelect.disabled = false;
                      }
                  })
                  .catch(error => console.error('Error:', error));
          } else {
              semesterSelect.innerHTML = '<option value="">أختار الفصل</option>';
              semesterSelect.disabled = true;
          }
      });
  }
});
