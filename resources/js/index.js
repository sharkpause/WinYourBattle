import $ from 'jquery';

$(function() {
    $('#user-post-input').on('click', function() {
        window.location.href = this.getAttribute('data-url');
    });

    $("#post-success-alert").fadeTo(2000, 500).slideUp(500, function(){
        $("#success-alert").slideUp(500);
    });
});