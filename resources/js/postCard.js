import $ from 'jquery';
import axios from 'axios';

$('.likeButton').on('click', async function(e) {
    e.preventDefault();
    const postID = $(this).data('post-id');
    const likeCountElem = $('.likeCount-' + postID);
    const dislikeCountElem = $('.dislikeCount-' + postID);
    const dislikeButton = $(`.dislikeButton[data-post-id="${postID}"]`);

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

$('.dislikeButton').on('click', async function(e) {
    e.preventDefault();
    const postID = $(this).data('post-id');
    const dislikeCountElem = $('.dislikeCount-' + postID);
    const likeCountElem = $('.likeCount-' + postID);
    const likeButton = $(`.likeButton[data-post-id="${postID}"]`);

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

$('.commentSectionButton').on('click', async function(e) {
    e.preventDefault();

    const postID = $(this).attr('data-post-id');

    if($(this).attr('data-opened').trim() === 'false') {
        $(this).find('.commentSectionButtonIcon').addClass('text-primary');
        $(this).attr('data-opened', 'true');

        $('#commentSection-' + postID).removeClass('d-none');

        if($(this).attr('data-opened-first-time') === "false") {
            const response = await axios.get('/posts/' + postID + '/comment');
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

    try {
        const response = await axios.post('/posts/' + $(this).attr('data-post-id') + '/comment',
        {
            _token: $(this).attr('data-csrf-token'),
            body: $(this).find('#commentTextarea').val(),
        });

        $('#commentCards-' + $(this).attr('data-post-id')).prepend(response.data.html);
    } catch(err) {
        console.log(err);
    }
});