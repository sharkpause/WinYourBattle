import $ from 'jquery';

import './alert.js';
import './submitButtonUnclickable.js';
import showSpinner from './spinner.js';

$('.form-with-spinner').on('submit', () => {
    showSpinner();
});