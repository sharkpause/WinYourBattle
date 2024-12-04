import $ from 'jquery';
import axios from 'axios';

$('#likeButton').on('click', async function(e) {
    e.preventDefault();
    
    console.log($(this));
}); 