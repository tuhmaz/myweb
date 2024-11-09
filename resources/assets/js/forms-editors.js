import 'summernote/dist/summernote-lite.css';
import 'summernote/dist/summernote-lite.js';
import 'summernote/dist/lang/summernote-ar-AR.js';
import axios from 'axios';
$(document).ready(function() {
  $('#summernote').summernote({
    disableDragAndDrop: true,

    height: 300,
    lang: 'ar-AR', 
    popover: {
      image: [
        ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
        ['float', ['floatLeft', 'floatRight', 'floatNone']],
        ['remove', ['removeMedia']]
      ],
      link: [
        ['link', ['linkDialogShow', 'unlink']]
      ],
      table: [
        ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
        ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
      ],
      air: [

        ['font', ['bold', 'underline', 'clear']],
        ['para', ['ul', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture']]
      ]
    },
    buttons: {
      // زر مخصص لإدراج نص
      myButton: function(context) {
        var ui = $.summernote.ui;
        var button = ui.button({
          contents: '<i class="fa fa-child"/> My Button',
          tooltip: 'My Custom Button',
          click: function() {
            context.invoke('editor.insertText', 'Hello World!');
          }
        });
        return button.render();
      },
      // زر لرفع الملفات
      uploadFileButton: function(context) {
        var ui = $.summernote.ui;
        var button = ui.button({
          contents: '<i class="fa fa-upload"/> Upload File',
          tooltip: 'Upload File',
          click: function() {
            $('<input type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt">')
              .on('change', function(event) {
                var file = event.target.files[0];
                if (file) {
                  uploadFile(file, context);
                }
              })
              .click();
          }
        });
        return button.render();
      }
    },
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'italic', 'underline', 'clear']],
      ['fontsize', ['fontsize']],
      ['height', ['height']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['fontname', ['fontname']],
      ['color', ['color']],
      ['insert', ['link', 'picture', 'uploadFileButton']], // إضافة زر رفع الملفات إلى شريط الأدوات
      ['view', ['codeview', 'help']]
    ],
    // رفع الصور
    callbacks: {
      onImageUpload: function(files) {
        if (files.length > 0) {
          uploadImage(files[0]);
        }
      }
    }
  });

});
// وظيفة رفع الصورة
function uploadImage(file) {
  let data = new FormData();
  data.append("file", file);

  axios.post('/upload-image', data, {
    headers: {
      'Content-Type': 'multipart/form-data'
    },
    withCredentials: true
  })
  .then(response => {
    if (response.status === 200 && response.data.url) {
      let fileName = file.name;
      // إدراج الصورة في المحرر
      $('#summernote').summernote('insertImage', response.data.url, function($image) {
        $image.attr('alt', fileName);
      });
    } else {
      alert('Failed to upload the image. Please try again.');
    }
  })
  .catch(error => {
    console.error('Image upload failed:', error);
    alert('An error occurred while uploading the image. Please check your connection or try again later.');
  });
}

// وظيفة رفع الملف
function uploadFile(file, context) {
  let data = new FormData();
  data.append("file", file);

  axios.post('/upload-file', data, {
    headers: {
      'Content-Type': 'multipart/form-data'
    },
    withCredentials: true
  })
  .then(response => {
    if (response.status === 200 && response.data.url) {
      let fileName = file.name;
      let fileUrl = response.data.url;
      // إدراج رابط الملف في المحرر
      context.invoke('editor.createLink', {
        text: fileName,
        url: fileUrl,
        isNewWindow: true
      });
    } else {
      alert('Failed to upload the file. Please try again.');
    }
  })
  .catch(error => {
    console.error('File upload failed:', error);
    alert('An error occurred while uploading the file. Please check your connection or try again later.');
  });
}
