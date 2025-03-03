import $ from 'jquery';
import Swal from 'sweetalert2';
import axios from 'axios';

let followerListPage = 0;
let followRequestCount = 0;

$('#follow-requests-button').on('click', async function(e) {
    const customAlert = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-danger',
            popup: 'border-radius-1-rem'
        }
    })

    await customAlert.fire({
        showDenyButton: false,
        showCancelButton: false,
        showConfirmButton: false,
        animation: false,
        html:
        `
        <ul class="list-unstyled w-100 max-height-50vh" id="follow-request-list">
            <i class="fa-solid fa-circle-notch spin mt-3"></i>
        </ul>
        `,
        didRender: async() => {
            const followRequestList = $('#follow-request-list');
            
            const followers = (await axios.get(
                $(this).attr('data-url') + '?page=' + followerListPage)
            ).data.data;

            followRequestList.empty();
            for(let i = 0; i < followers.length; ++i) {
                followRequestList.append(`
                    <li class="d-flex align-items-center justify-content-between p-2 account-item">
                        <div>
                            <img src="${followers[i].image_url}" class="rounded-circle me-2" width="30" height="30">
                            <strong>${followers[i].username}</strong>
                        </div>
                        <div>
                            <button class="btn btn-primary accept-follow-request-button" data-accepted="false">
                                <strong>Accept</strong>
                            </button>
                            <button class="btn btn-danger reject-follow-request-button">
                                <strong>Reject</strong>
                            </button>
                        </div>
                    </li>
                `);

                ++followRequestCount;

                // if(followers[i].followedByAuth) {
                //     const followerListFollowButton = $('.follower-list-follow-button');
                    // // followerListFollowButton.addClass('btn-no-hover btn-gray');
                //     // followerListFollowButton.text('Following');
                // }
            }
        }
    });

    // if(result.isConfirmed) {
    //     const commentCard = $('#comment-card-' + $(this).attr('data-comment-id'));
    //     commentCard.hide();

    //     try {
    //         this.submit();
    //     } catch(err) {
    //         console.log(err);
    //         commentCard.show();
    //     }
    // }
});

$(document).on('click', '.accept-follow-request-button', async function(e) {
    if($(this).attr('data-accepted') === 'false') {
        $(this).text('Follow Back')
    } else {
        alert('b')
    }
});

$(document).on('click', '.reject-follow-request-button', async function(e) {
    const accountItem = $(this).closest('.account-item');
    accountItem.addClass('hidden');

    --followRequestCount;
    if(followRequestCount === 0) {
        $('#follow-request-list').append('<p class="text-muted mt-3">No follow requests</p>');
    }
});