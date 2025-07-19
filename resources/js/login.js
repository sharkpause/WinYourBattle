import $ from 'jquery';

import './components/alert.js';
import './components/submitButtonUnclickable.js';
import showSpinner from './components/spinner.js';

$('.form-with-spinner').on('submit', function(e) {
    showSpinner(this);
});

$('#google-oauth-button, #github-oauth-button').on('click', function(e) {
    showSpinner(this);
    $(this).prop('disabled', true);
    $(this).addClass('disabled-img');
    $(this).removeClass('button-click-animation');
});