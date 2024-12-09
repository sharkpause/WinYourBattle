import $ from 'jquery';

$(document).on('click', '.submit-button', function(e) {
  $('.submit-button').attr('disabled', 'disabled');
  $(this).closest('form')[0].requestSubmit();
});