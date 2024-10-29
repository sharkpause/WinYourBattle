import $ from 'jquery';

$('#registerForm').on('submit', function(event) {
    $('#submitButton').attr('disabled','disabled');
    $('#spinnerDiv').html(`<div class="spinner-border text-muted mt-3 spinner-border-sm" role="status">
        <span class="sr-only text-muted">Please wait...</span>
      </div>`)
});