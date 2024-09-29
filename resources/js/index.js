import $ from 'jquery';

$(document).ready(() => {
    $('#user-post-input').on('click', () => {
        console.log('a'); // redirect to posts.create
    });
});