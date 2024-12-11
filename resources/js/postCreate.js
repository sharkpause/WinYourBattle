import $ from 'jquery';

import './components/autoResizeTextarea.js';

$('#post-image').on('change', function () {
    const files = this.files;

    if (files && files[0]) {
        const fileReader = new FileReader();

        fileReader.onload = function (e) {
            $('#image-preview-container').empty();

            const img = $('<img>', {
                src: e.target.result,
                alt: 'Image Preview',
                css: {
                    maxWidth: '500px',
                    maxHeight: '500px',
                    borderRadius: '10px',
                    border: '1px solid #ddd',
                    objectFit: 'cover',
                }
            });

            $('#image-preview-container').append(img);
        };

        fileReader.readAsDataURL(files[0]);
    }
});