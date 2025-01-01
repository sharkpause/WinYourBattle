import $ from 'jquery';
import Swal from 'sweetalert2';
import axios from 'axios';

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

    try {
        axios.post($(this).attr('data-url'), { _token: $(this).attr('data-csrf-token') });
    } catch(err) {
        ;
    }
});