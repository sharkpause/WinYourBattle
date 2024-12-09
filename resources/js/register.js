import $ from 'jquery';

import './components/submitButtonUnclickable.js';
import showSpinner from './components/spinner.js';

$('.form-with-spinner').on('submit', () => {
    showSpinner();
});