import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import html from '@rollup/plugin-html';
import { glob } from 'glob';
import { terser } from 'rollup-plugin-terser';
import { visualizer } from 'rollup-plugin-visualizer';

/**
 * Get Files from a directory
 * @param {string} query
 * @returns array
 */
function GetFilesArray(query) {
  return Array.from(new Set(glob.sync(query))); // Remove duplicate files by using Set initially
}

// File paths to be collected
const fileQueries = {
  pageJsFiles: 'resources/assets/js/*.js',
  vendorJsFiles: 'resources/assets/vendor/js/*.js',
  libsJsFiles: 'resources/assets/vendor/libs/**/*.js',
  coreScssFiles: 'resources/assets/vendor/scss/**/!(_)*.scss',
  libsScssFiles: 'resources/assets/vendor/libs/**/!(_)*.scss',
  libsCssFiles: 'resources/assets/vendor/libs/**/*.css',
  fontsScssFiles: 'resources/assets/vendor/fonts/!(_)*.scss',
};

// Collect all files
const files = Object.entries(fileQueries).reduce((acc, [key, query]) => {
  acc[key] = GetFilesArray(query);
  return acc;
}, {});

function collectInputFiles() {
  return [
    'resources/css/app.css',
    'resources/assets/css/edu.css',
    'resources/js/app.js',
    ...Object.values(files).flat(), // Flatten all arrays into one
  ];
}

// Processing Window Assignment for Libs like jKanban, pdfMake
function libsWindowAssignment() {
  return {
    name: 'libsWindowAssignment',
    transform(src, id) {
      const replacements = {
        'jkanban.js': ['this.jKanban', 'window.jKanban'],
        'vfs_fonts': ['this.pdfMake', 'window.pdfMake'],
      };
      for (const [key, [searchValue, replaceValue]] of Object.entries(replacements)) {
        if (id.includes(key)) {
          return src.replaceAll(searchValue, replaceValue);
        }
      }
    },
  };
}

export default defineConfig({
  plugins: [
    laravel({
      input: collectInputFiles(),
      refresh: true,
    }),
    html(),
    libsWindowAssignment(),
    terser(),
    ...(process.env.NODE_ENV === 'production' ? [visualizer({ open: true })] : []),
  ],

  css: {
    postcss: './postcss.config.cjs',
  },

  build: {
    // حذف الـ sourcemap الثابت
    sourcemap: process.env.NODE_ENV !== 'production', // Sourcemaps only for non-production
    rollupOptions: {
      external: ['summernote'],

      output: {
        globals: {
          onesignal: 'OneSignal',
        },
      },
      plugins: [
        terser({
          format: {
            comments: false, // Remove comments in production
          },
        }),
      ],
    },
    minify: 'terser',
    chunkSizeWarningLimit: 600, // Increase limit to avoid warnings for larger chunks
  },
});
