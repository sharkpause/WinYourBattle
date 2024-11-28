import $ from 'jquery';
import axios from 'axios';

$('#likeButton').on('click', async function(e) {
    e.preventDefault();
    const postID = $(this).data('post-id');
    const likeCountElem = $('#likeCount');
    const dislikeCountElem = $('#dislikeCount');
    const dislikeButton = $('#dislikeButton');

    if(dislikeButton.attr('data-disliked').trim() === 'true') {
        dislikeCountElem.text(Number(dislikeCountElem.text()) - 1);
        dislikeButton.attr('data-disliked', 'false');
        dislikeButton.removeClass('text-danger');
    }

    if($(this).attr('data-liked').trim() === 'false') {
        likeCountElem.text(Number(likeCountElem.text()) + 1);
        $(this).attr('data-liked', 'true');
        $(this).addClass('text-primary');

        try {
            await axios.post('/posts/' + postID + '/like', { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
        }
    } else if($(this).attr('data-liked').trim() === 'true') {
        likeCountElem.text(Number(likeCountElem.text()) - 1);
        $(this).attr('data-liked', 'false');
        $(this).removeClass('text-primary');
        
        try {
            await axios.post('/posts/' + postID + '/unlike', { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
        }
    }
});

$('#dislikeButton').on('click', async function(e) {
    e.preventDefault();
    const postID = $(this).data('post-id');
    const dislikeCountElem = $('#dislikeCount');
    const likeCountElem = $('#likeCount');
    const likeButton = $('#likeButton');

    if(likeButton.attr('data-liked').trim() === 'true') {
        likeCountElem.text(Number(likeCountElem.text()) - 1);
        likeButton.attr('data-liked', 'false');
        likeButton.removeClass('text-primary');
    }

    if($(this).attr('data-disliked').trim() === 'false') {
        dislikeCountElem.text(Number(dislikeCountElem.text()) + 1);
        $(this).attr('data-disliked', 'true');
        $(this).addClass('text-danger');

        try {
            await axios.post('/posts/' + postID + '/dislike', { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
        }
    } else if($(this).attr('data-disliked').trim() === 'true') {
        dislikeCountElem.text(Number(dislikeCountElem.text()) - 1);
        $(this).attr('data-disliked', 'false');
        $(this).removeClass('text-danger');
        
        try {
            await axios.post('/posts/' + postID + '/undislike', { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
        }
    }
});

const commentSectionButton = $('#commentSectionButton');
commentSectionButton.on('click', async function(e) {
    e.preventDefault();

    if(commentSectionButton.attr('data-opened') === 'false') {
        $('#commentSectionButtonIcon').addClass('text-primary');
        commentSectionButton.attr('data-opened', 'true');

        $('#commentSection').removeClass('d-none');
    } else if(commentSectionButton.attr('data-opened') === 'true') {
        $('#commentSectionButtonIcon').removeClass('text-primary');
        commentSectionButton.attr('data-opened', 'false');

        $('#commentSection').addClass('d-none');
    }
});

$('#commentForm').on('submit', async function(e) {
    e.preventDefault();

    try {
        await axios.post('/posts/' + $(this).attr('data-post-id') + '/comment',
        {
            _token: $(this).attr('data-csrf-token'),
            body: $('#commentTextarea').val(),
        });
    } catch(err) {
        console.log(err);
    }
});