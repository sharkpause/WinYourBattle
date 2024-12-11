import $ from 'jquery';
import axios from 'axios';

import './components/autoResizeTextarea.js';

$('.comment-like-button').on('click', async function(e) {
    e.preventDefault();
    
    const commentID = $(this).attr('data-comment-id');
    const commentLikeCountElem = $('#comment-like-count-' + commentID);
    const commentDislikeCountElem = $('#comment-dislike-count-' + commentID);
    const commentDislikeButton = $(`.comment-dislike-button[data-comment-id="${commentID}"]`);

    if(commentDislikeButton.attr('data-disliked').trim() === 'true') {
        commentDislikeCountElem.text(Number(commentDislikeCountElem.text()) - 1);
        commentDislikeButton.attr('data-disliked', 'false');
        commentDislikeButton.removeClass('text-danger');
    }

    if($(this).attr('data-liked').trim() === 'false') {
        commentLikeCountElem.text(Number(commentLikeCountElem.text()) + 1);
        $(this).attr('data-liked', 'true');
        $(this).addClass('text-primary');

        try {
            await axios.post($(this).attr('data-like-url'), { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
            commentLikeCountElem.text(Number(commentLikeCountElem.text()) - 1);
            $(this).attr('data-liked', 'false');
            $(this).removeClass('text-primary');
        }
    } else if($(this).attr('data-liked').trim() === 'true') {
        commentLikeCountElem.text(Number(commentLikeCountElem.text()) - 1);
        $(this).attr('data-liked', 'false');
        $(this).removeClass('text-primary');
        
        try {
            await axios.post($(this).attr('data-unlike-url'), { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
            commentLikeCountElem.text(Number(commentLikeCountElem.text()) + 1);
            $(this).attr('data-liked', 'true');
            $(this).addClass('text-primary');
        }
    }
});

$('.comment-dislike-button').on('click', async function(e) {
    e.preventDefault();
    
    const commentID = $(this).attr('data-comment-id');
    const commentDislikeCountElem = $('#comment-dislike-count-' + commentID);
    const commentLikeCountElem = $('#comment-like-count-' + commentID);
    const commentLikeButton = $(`.comment-like-button[data-comment-id="${commentID}"]`);

    if(commentLikeButton.attr('data-liked').trim() === 'true') {
        commentLikeCountElem.text(Number(commentLikeCountElem.text()) - 1);
        commentLikeButton.attr('data-liked', 'false');
        commentLikeButton.removeClass('text-primary');
    }

    if($(this).attr('data-disliked').trim() === 'false') {
        commentDislikeCountElem.text(Number(commentDislikeCountElem.text()) + 1);
        $(this).attr('data-disliked', 'true');
        $(this).addClass('text-danger');

        try {
            await axios.post($(this).attr('data-dislike-url'), { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
            commentDislikeCountElem.text(Number(commentDislikeCountElem.text()) - 1);
            $(this).attr('data-disliked', 'false');
            $(this).removeClass('text-danger');
        }
    } else if($(this).attr('data-disliked').trim() === 'true') {
        commentDislikeCountElem.text(Number(commentDislikeCountElem.text()) - 1);
        $(this).attr('data-disliked', 'false');
        $(this).removeClass('text-danger');
        
        try {
            await axios.post($(this).attr('data-undislike-url'), { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
            commentDislikeCountElem.text(Number(commentDislikeCountElem.text()) + 1);
            $(this).attr('data-disliked', 'true');
            $(this).addClass('text-danger');
        }
    }
});

$(document).on('click', '.delete-comment-button', async function(e) {
    e.preventDefault();

    const result = await Swal.fire({
        title: 'Are you sure you want to delete your comment?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'Confirm',
        denyButtonText: 'Cancel'
    });

    if(result.isConfirmed) {
        const commentCard = $('#comment-card-' + $(this).attr('data-comment-id'));
        commentCard.hide();

        try {
            await axios.delete($(this).attr('data-url'), { _token: $(this).data('csrf-token') });
            commentCard.remove();
        } catch(err) {
            console.log(err);
            commentCard.show();
        }
    }
});

$(document).on('click', '.edit-comment-button', async function(e) {
    e.preventDefault();

    const commentID = $(this).attr('data-comment-id');
    const commentBodyElem = $('#comment-body-' + commentID);
    commentBodyElem.html(
        '<textarea class="form-control mb-1 keep-whitespace"' +
                'name="body"' +
                'placeholder="Changed your mind?"' +
                `id="comment-edit-text-area-${commentID}"` +
                'autocomplete="off">' +
            $('<span>').text(commentBodyElem.text().trim()).html() +
        '</textarea>' +
        `<button class="btn btn-primary float-end button-click-animation comment-submit-edit"
                 data-comment-id="${commentID}"
                 data-url="${$(this).attr('data-url')}"
                 data-csrf-token="${$(this).attr('data-csrf-token')}">Submit Edit</button>`);
});

$(document).on('click', '.comment-submit-edit', async function(e) {
    e.preventDefault();

    try {
        const commentID = $(this).attr('data-comment-id');
        const commentEditTextareaElemValue = $('#comment-edit-text-area-' + commentID).val();
        
        await axios.patch($(this).attr('data-url'), { body: commentEditTextareaElemValue, _token: $(this).attr('data-csrf-token') });

        $('#comment-body-' + commentID).html(`
            <div id="comment-body-{{ ${commentID} }}">
                ${commentEditTextareaElemValue}
            </div>`);
    } catch(err) {
        console.log(err);
    }
});