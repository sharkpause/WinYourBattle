import $ from 'jquery';
import axios from 'axios';

$('.commentLikeButton').on('click', async function(e) {
    e.preventDefault();
    
    const commentID = $(this).attr('data-comment-id');
    const commentLikeCountElem = $('#commentLikeCount-' + commentID);
    const commentDislikeCountElem = $('#commentDislikeCount-' + commentID);
    const commentDislikeButton = $(`.commentDislikeButton[data-comment-id="${commentID}"]`);

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

$('.commentDislikeButton').on('click', async function(e) {
    e.preventDefault();
    
    const commentID = $(this).attr('data-comment-id');
    const commentDislikeCountElem = $('#commentDislikeCount-' + commentID);
    const commentLikeCountElem = $('#commentLikeCount-' + commentID);
    const commentLikeButton = $(`.commentLikeButton[data-comment-id="${commentID}"]`);

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