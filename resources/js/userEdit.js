import $ from 'jquery';

$('#profile-image-input').on('change', function() {
    const file = this.files[0];

    if(file) {
        const fileReader = new FileReader();

        fileReader.onload = function(e) {
            $('#profile-image-preview').attr('src', e.target.result);
        }

        fileReader.readAsDataURL(file);
    }
});

$('#edit-account-form').on('submit', function(e) {
    $('#edit-account-button').attr('disabled', 'disabled');
});