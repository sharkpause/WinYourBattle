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

$('#show-password-button').on('click', function(e) {
    if($(this).attr('data-state') == '0') {
        $(this).removeClass('fa-eye');

        $(this).attr('data-state', '1');
        $(this).addClass('fa-eye-slash');

        $('#password-input').prop('type', 'text');
    } else if($(this).attr('data-state') == '1') {
        $(this).removeClass('fa-eye-slash');
        $(this).addClass('fa-eye');

        $(this).attr('data-state', '0');

        $('#password-input').prop('type', 'password');
    }
})