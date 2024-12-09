import $ from 'jquery';

import './submitButtonUnclickable.js';
import showSpinner from './spinner.js';

$('.form-with-spinner').on('submit', () => {
    showSpinner();
});