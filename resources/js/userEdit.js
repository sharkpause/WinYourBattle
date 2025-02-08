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

$('#visibility-toggle').on('click', function(e) {
    console.log($(this).attr('data-visibility').trim());
    if($(this).attr('data-visibility').trim() === '0') {
        $('#account-visibility-state').text('Public');
        $('#account-visibility-information-text').text('Your account is visible to everybody 😎');
        $(this).attr('data-visibility', 1);
    } else {
        $('#account-visibility-state').text('Private');
        $('#account-visibility-information-text').text('Your account is only visible to followers 🤫');
        $(this).attr('data-visibility', 0);
    }
});