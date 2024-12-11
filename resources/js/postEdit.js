import $ from 'jquery';

import './components/autoResizeTextarea.js';

$('#post-image-input').on('change', function() {
    const file = this.files[0];

    if(file) {
        const fileReader = new FileReader();

        fileReader.onload = function(e) {
            $('#post-image-preview').attr('src', e.target.result);
        }

        fileReader.readAsDataURL(file);
    }
});