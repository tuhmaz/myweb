const purgecss = require('@fullhuman/postcss-purgecss');
const cssnano = require('cssnano');

console.log(`Loading PostCSS Config in ${process.env.NODE_ENV} mode`);

module.exports = {
  plugins: [
    cssnano({ // لضغط CSS
      preset: ['default', {
        discardComments: {
          removeAll: true, // إزالة جميع التعليقات
        },
        reduceIdents: false, // تعطيل تقليل الأسماء المكررة
      }],
    }),

    // إضافة شرط لـ PurgeCSS فقط في بيئة الإنتاج
    ...(process.env.NODE_ENV === 'production' ? [
      purgecss({
        content: [
          './resources/**/*.blade.php',
          './resources/**/*.js',
          './resources/**/*.scss',
          './resources/**/*.css',
        ],
        safelist: [
          /^note-/,
          'summernote',
          'note-editable',
          'btn-outline-dark',
          'bubbly-button',
          'd-flex',
          'justify-content-center',
          'align-items-center'
        ],  // تأكد من إضافة الفئات الخاصة بمحرر النصوص هنا

        defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || [],
      }),
    ] : []),
  ],
};
