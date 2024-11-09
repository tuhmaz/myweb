'use strict';

$(document).ready(function() {
  // تهيئة Tagify للكلمات المفتاحية
  var tagify = new Tagify(document.querySelector('#TagifyBasic'));

  // تحويل الكلمات المفتاحية إلى نصوص مفصولة بفواصل عند إرسال النموذج
  $('#articleForm').on('submit', function(e) {
    e.preventDefault(); // منع إرسال النموذج تلقائيًا

    // استخراج الكلمات المفتاحية من Tagify
    var tagifyData = tagify.value.map(function(item) {
      return item.value;
    });

    // تحويل الكلمات المفتاحية إلى نص مفصول بفواصل
    var keywords = tagifyData.join(',');

    // تعيين الكلمات المفتاحية في الحقل المخفي أو إرسالها مع البيانات
    $('#TagifyBasic').val(keywords);

    // يمكنك الآن إرسال النموذج بعد معالجة الكلمات المفتاحية
    this.submit();
  });
});
