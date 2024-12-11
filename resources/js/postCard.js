import $ from 'jquery';
import axios from 'axios';

import './components/autoResizeTextarea.js';

$('.postLikeButton').on('click', async function(e) {
    e.preventDefault();

    const postID = $(this).data('post-id');
    const postLikeCountElem = $('#postLikeCount-' + postID);
    const postDislikeCountElem = $('#postDislikeCount-' + postID);
    const postDislikeButton = $(`.postDislikeButton[data-post-id="${postID}"]`);

    if(postDislikeButton.attr('data-disliked').trim() === 'true') {
        postDislikeCountElem.text(Number(postDislikeCountElem.text()) - 1);
        postDislikeButton.attr('data-disliked', 'false');
        postDislikeButton.removeClass('text-danger');
    }

    if($(this).attr('data-liked').trim() === 'false') {
        postLikeCountElem.text(Number(postLikeCountElem.text()) + 1);
        $(this).attr('data-liked', 'true');
        $(this).addClass('text-primary');

        try {
            await axios.post($(this).attr('data-like-url'), { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
        }
    } else if($(this).attr('data-liked').trim() === 'true') {
        postLikeCountElem.text(Number(postLikeCountElem.text()) - 1);
        $(this).attr('data-liked', 'false');
        $(this).removeClass('text-primary');
        
        try {
            await axios.post($(this).attr('data-unlike-url'), { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
        }
    }
});

$('.postDislikeButton').on('click', async function(e) {
    e.preventDefault();
    
    const postID = $(this).data('post-id');
    const postDislikeCountElem = $('#postDislikeCount-' + postID);
    const postLikeCountElem = $('#postLikeCount-' + postID);
    const postLikeButton = $(`.postLikeButton[data-post-id="${postID}"]`);

    if(postLikeButton.attr('data-liked').trim() === 'true') {
        postLikeCountElem.text(Number(postLikeCountElem.text()) - 1);
        postLikeButton.attr('data-liked', 'false');
        postLikeButton.removeClass('text-primary');
    }

    if($(this).attr('data-disliked').trim() === 'false') {
        postDislikeCountElem.text(Number(postDislikeCountElem.text()) + 1);
        $(this).attr('data-disliked', 'true');
        $(this).addClass('text-danger');

        try {
            await axios.post($(this).attr('data-dislike-url'), { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
        }
    } else if($(this).attr('data-disliked').trim() === 'true') {
        postDislikeCountElem.text(Number(postDislikeCountElem.text()) - 1);
        $(this).attr('data-disliked', 'false');
        $(this).removeClass('text-danger');
        
        try {
            await axios.post($(this).attr('data-undislike-url'), { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
        }
    }
});

$('.commentSectionButton').on('click', async function(e) {
    e.preventDefault();

    const postID = $(this).attr('data-post-id');

    if($(this).attr('data-opened').trim() === 'false') {
        $(this).find('.commentSectionButtonIcon').addClass('text-primary');
        $(this).attr('data-opened', 'true');

        $('#commentSection-' + postID).removeClass('d-none');

        if($(this).attr('data-opened-first-time') === "false") {
            const response = await axios.get($(this).attr('data-url'));
            $('#commentCards-' + postID).html(response.data.html);
            $('#commentPaginator-' + postID).html(response.data.paginator);

            $(this).attr('data-opened-first-time', 'true')
        }

        $(document).on('click', '#commentPaginator-' + postID + ' a', async function(e) {
            e.preventDefault();
        
            try {
                const response = await axios.get($(this).attr('href'));
                $('#commentCards-' + postID).html(response.data.html);
                $('#commentPaginator-' + postID).html(response.data.paginator);
            } catch (error) {
                console.error('Error fetching paginated comments:', error);
            }
        });
    } else if($(this).attr('data-opened').trim() === 'true') {
        $(this).find('.commentSectionButtonIcon').removeClass('text-primary');
        $(this).attr('data-opened', 'false');

        $('#commentSection-' + $(this).attr('data-post-id')).addClass('d-none');
    }
});

$('.commentForm').on('submit', async function(e) {
    e.preventDefault();
    
    const commentTextareaElem = $(this).find('#commentTextarea');
    const textareaValue = commentTextareaElem.val();
    commentTextareaElem.val('');

    try {
        const response = await axios.post($(this).attr('data-url'),
        {
            _token: $(this).attr('data-csrf-token'),
            body: textareaValue,
        });

        $('#commentCards-' + $(this).attr('data-post-id')).prepend(response.data.html);
    } catch(err) {
        console.log(err);

        commentTextareaElem.val(textareaValue);
    }
});

$('.delete-post-form').on('submit', async function(e) {
    e.preventDefault();

    const customAlert = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-danger',
            popup: 'border-radius-1-rem'
        }
    })

    const result = await customAlert.fire({
        title: 'Are you sure you want to delete your post?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'Confirm',
        denyButtonText: 'Cancel',
        animation: false
    });

    if(result.isConfirmed) {
        const commentCard = $('#commentCard-' + $(this).attr('data-comment-id'));
        commentCard.hide();

        try {
            this.submit();
        } catch(err) {
            console.log(err);
            commentCard.show();
        }
    }
});