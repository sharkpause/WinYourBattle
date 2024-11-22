import $ from 'jquery';
import axios from 'axios';

$('#likeButton').on('click', async function(e) {
    e.preventDefault();

    //const likeCount = $('#likeCount');
    

    const postID = $(this).data('post-id');

    try {
        const response = await axios.post('/posts/' + postID + '/like', { _token: $(this).data('csrf-token') });
    } catch(err) {
        console.log(err);
    }
});