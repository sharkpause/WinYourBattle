import $ from 'jquery';

import './components/submitButtonUnclickable.js';
import showSpinner from './components/spinner.js';

$('.form-with-spinner').on('submit', () => {
    showSpinner(this);
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

$('#show-confirm-password-button').on('click', function(e) {
    if($(this).attr('data-state') == '0') {
        $(this).removeClass('fa-eye');

        $(this).attr('data-state', '1');
        $(this).addClass('fa-eye-slash');

        $('#confirm-password-input').prop('type', 'text');
    } else if($(this).attr('data-state') == '1') {
        $(this).removeClass('fa-eye-slash');
        $(this).addClass('fa-eye');

        $(this).attr('data-state', '0');

        $('#confirm-password-input').prop('type', 'password');
    }
})