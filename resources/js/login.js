import $ from 'jquery';

import './components/alert.js';
import './components/submitButtonUnclickable.js';
import showSpinner from './components/spinner.js';

$('.form-with-spinner').on('submit', function(e) {
    showSpinner(this);
});