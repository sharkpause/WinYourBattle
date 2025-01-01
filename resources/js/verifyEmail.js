import $ from 'jquery';

import './components/alert.js';
import './components/submitButtonUnclickable.js';
import showSpinner from './components/spinner.js';

$('.form-with-spinner').on('submit', function() {
    showSpinner(this);
});

$('#wrong-email-link').on('click', function() {
    $(this).addClass('d-none');
    $('#send-email-again-container').hide();
    $('#change-email-container').removeClass('d-none');
    $('#card-title').text('Enter your new email');
});