import $ from 'jquery';
import axios from 'axios';

$('#likeButton').on('click', async function(e) {
    e.preventDefault();
    const postID = $(this).data('post-id');

    if($(this).attr('data-liked').trim() === 'false') {
        const likeCountElem = $('#likeCount');
        likeCountElem.text(Number(likeCountElem.text()) + 1);
        $(this).attr('data-liked', 'true');
        $(this).addClass('text-primary');

        try {
            await axios.post('/posts/' + postID + '/like', { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
        }
    } else if($(this).attr('data-liked').trim() === 'true') {
        const likeCountElem = $('#likeCount');
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

    if($(this).attr('data-disliked').trim() === 'false') {
        const dislikeCountElem = $('#dislikeCount');
        dislikeCountElem.text(Number(dislikeCountElem.text()) + 1);
        $(this).attr('data-disliked', 'true');
        $(this).addClass('text-danger');

        try {
            await axios.post('/posts/' + postID + '/dislike', { _token: $(this).data('csrf-token') });
        } catch(err) {
            console.log(err);
        }
    } else if($(this).attr('data-disliked').trim() === 'true') {
        const dislikeCountElem = $('#dislikeCount');
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