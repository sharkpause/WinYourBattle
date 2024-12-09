import $ from 'jquery';

import './components/alert.js';

$(function() {
    $('#user-post-input').on('click', function() {
        window.location.href = this.getAttribute('data-url');
    });
});