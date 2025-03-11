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
            if(followers.length <= 0) {
                followRequestList.append('<p class="text-muted mt-3" id="no-follow-request-text">No follow requests</p>');
            } else {
                for(let i = 0; i < followers.length; ++i) {
                    followRequestList.append(`
                        <li class="d-flex align-items-center justify-content-between p-2 account-item">
                            <a href="${followers[i].profileURL}" class="no-styling pointer-on-hover">
                                <img src="${followers[i].imageURL}" class="rounded-circle me-2" width="30" height="30">
                                <strong>${followers[i].username}</strong>
                            </a>
                            <div>
                                <button class="btn btn-primary accept-follow-request-button"
                                        data-accepted="false" data-url="${followers[i].acceptURL}"
                                        data-private="${followers[i].private}" data-followed="${followers[i].followedByAuth}"
                                        data-follow-url=${followers[i].followURL} data-requested="${followers[i].requestedByAuth}">
                                    <strong>Accept</strong>
                                </button>
                                <button class="btn btn-danger reject-follow-request-button" data-url="${followers[i].rejectURL}">
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
        if($(this).attr('data-followed').trim() === 'true') {
            $(this).text('Following');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-gray');
            $(this).addClass('btn-no-hover');
            $(this).removeClass('btn');
        } else if($(this).attr('data-requested').trim() === 'true') {
            $(this).text('Requested');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-gray');
            $(this).addClass('btn-no-hover');
            $(this).removeClass('btn');
        } else if($(this).attr('data-followed').trim() === 'false' || $(this).attr('data-requested').trim() === 'false') {
            $(this).text('Follow back');
        } 

        $(this).attr('data-accepted', 'true');

        try {
            const response = await axios.post($(this).attr('data-url'), { _token: $('#profile-follow-button').attr('data-csrf-token') });
        } catch(err) {
            $(this).text('Accept')
            $(this).attr('data-accepted', 'false');

            console.log(err);
        }
    } else {
        if($(this).attr('data-followed').trim() === 'false') {
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-gray');
            $(this).addClass('btn-no-hover');
            $(this).removeClass('btn');

            if($(this).attr('data-private').trim() === 'true') {
                $(this).text('Requested');
            } else {
                $(this).text('Following');
            }

            try {
                const response = await axios.post($(this).attr('data-follow-url'), { _token: $('#profile-follow-button').attr('data-csrf-token') });
            } catch(err) {
                console.log(err);
            }
        }
    }
});

$(document).on('click', '.reject-follow-request-button', async function(e) {
    const accountItem = $(this).closest('.account-item');
    accountItem.addClass('hidden');

    --followRequestCount;
    if(followRequestCount === 0) {
        $('#follow-request-list').append('<p class="text-muted mt-3" id="no-follow-request-text">No follow requests</p>');
    }

    try {
        const response = await axios.delete($(this).attr('data-url'));
    } catch(err) {
        console.log(err);
        ++followRequestCount;
        accountItem.removeClass('hidden');

        if(followRequestCount === 1) {
            $('#no-follow-request-text').addClass('hidden');
        }
    }
});