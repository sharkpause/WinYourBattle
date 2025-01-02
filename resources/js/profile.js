import $ from 'jquery';
import Swal from 'sweetalert2';
import axios from 'axios';

function diffForHumans(num) {
    let result = num;

    if(num >= 1_000_000_000) {
        result /= 1_000_000_000;
        result = result.toFixed(2);
        result += ' B';
    } else if(num >= 1_000_000) {
        result /= 1_000_000;
        result = result.toFixed(2);
        result += ' M';
    } else if(num >= 1_000) {
        result /= 1_000;
        result = result.toFixed(2);
        result += ' K';
    }

    return result;
}

$('#delete-account-form').on('submit', async function(e) {
    e.preventDefault();

    const customAlert = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-danger',
            popup: 'border-radius-1-rem'
        }
    })

    const result = await customAlert.fire({
        title: 'Are you sure you want to delete your account?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'Confirm',
        denyButtonText: 'Cancel',
        animation: false
    });

    if(result.isConfirmed) {
        $('#set-new-relapse-button').attr('disabled', 'disabled');
        $('#reset-relapse-data-button').attr('disabled', 'disabled');
        
        this.submit();
    }
});

$('#follow-button').on('click', async function(e) {
    e.preventDefault();

    if($(this).attr('data-followed').trim() === 'false') {
        $(this).text('Following');
        $(this).attr('data-followed', 'true');
        $(this).addClass('btn-primary');
        $(this).removeClass('btn-gray');
        $(this).addClass('btn-no-hover');
        $(this).removeClass('btn');

        try {
            const response = await axios.post($(this).attr('data-follow-url'), { _token: $(this).attr('data-csrf-token') });
            $('#follower-count').text(diffForHumans(response.data.followCount) + ' Followers');
        } catch(err) {
            $(this).text('Follow');
            $(this).attr('data-followed', 'false');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-gray');
            $(this).removeClass('btn-no-hover');
            $(this).addClass('btn');

            console.log(err);
        }
    } else if($(this).attr('data-followed').trim() === 'true') {
        const customAlert = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger',
                popup: 'border-radius-1-rem'
            }
        })
    
        const result = await customAlert.fire({
            title: 'Are you sure you want to unfollow this person?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Confirm',
            denyButtonText: 'Cancel',
            animation: false
        });
    
        if(result.isConfirmed) {
            $(this).text('Follow');
            $(this).attr('data-followed', 'false');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-gray');
            $(this).removeClass('btn-no-hover');
            $(this).addClass('btn');

            try {
                const response = await axios.post($(this).attr('data-unfollow-url'), { _token: $(this).attr('data-csrf-token') });
                $('#follower-count').text(diffForHumans(response.data.followCount + ' Followers'));
            } catch(err) {
                console.log(err);

                $(this).text('Following');
                $(this).attr('data-followed', 'true');
                $(this).addClass('btn-primary');
                $(this).removeClass('btn-gray');
                $(this).addClass('btn-no-hover');
                $(this).removeClass('btn');
            }
        }
    }
});