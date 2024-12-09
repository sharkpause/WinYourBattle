import $ from 'jquery';

import './components/autoResizeTextarea.js';

$(document).ready(function () {
    $('#post_image').on('change', function () {
        const files = this.files;

        if (files && files[0]) {
            const fileReader = new FileReader();

            fileReader.onload = function (e) {
                // Clear previous preview
                $('#image-preview-container').empty();

                // Create a new image element
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

                // Append the image to the container
                $('#image-preview-container').append(img);
            };

            fileReader.readAsDataURL(files[0]);
        }
    });
});