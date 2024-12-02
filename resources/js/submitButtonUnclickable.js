import $ from 'jquery';

$('#form').on('submit', function(e) {
    $('#submitButton').attr('disabled','disabled');
    $('#spinnerElem').html(`<div class="spinner-border text-muted spinner-border-sm" role="status">
        <span class="sr-only text-muted">Please wait...</span>
      </div>`)
});