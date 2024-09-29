import $ from 'jquery';

$(function() {
    $('#user-post-input').on('click', function() {
        window.location.href = this.getAttribute('data-url');
    });
});