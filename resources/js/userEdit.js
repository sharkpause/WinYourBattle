import $ from 'jquery';

$('#profile_image_input').on('change', function() {
    const file = this.files[0];

    if(file) {
        const fileReader = new FileReader();

        fileReader.onload = function(e) {
            $('#profile_image_preview').attr('src', e.target.result);
        }

        fileReader.readAsDataURL(file);
    }
});

$('#editAccountForm').on('submit', function(e) {
    $('#editAccountButton').attr('disabled', 'disabled');
    $(this)[0].requestSubmit();
});