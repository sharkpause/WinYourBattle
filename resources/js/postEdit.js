import $ from 'jquery';

import './components/autoResizeTextarea.js';

$('#post_image_input').on('change', function() {
    const file = this.files[0];

    if(file) {
        const fileReader = new FileReader();

        fileReader.onload = function(e) {
            $('#post_image_preview').attr('src', e.target.result);
        }

        fileReader.readAsDataURL(file);
    }
});