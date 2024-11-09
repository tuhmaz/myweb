import $ from 'jquery';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.bundle.js';
import 'summernote/dist/summernote.min.css';

import summernote from 'summernote/dist/summernote.min.js';

try {
  window.$ = $;
  window.jQuery = $;
  window.summernote = summernote;
} catch (e) {
  console.error('Error loading Summernote:', e);
}



export { summernote };
