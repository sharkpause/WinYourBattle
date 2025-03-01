import $ from 'jquery';
import Swal from 'sweetalert2';
import axios from 'axios';

let followerListPage = 0;

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
                    <li class="d-flex align-items-center justify-content-between p-2">
                        <div>
                            <img src="${followers[i].image_url}" class="rounded-circle me-2" width="30" height="30">
                            <strong>${followers[i].username}</strong>
                        </div>
                        <button class="btn btn-primary follow-button follower-list-follow-button"
                            <strong>Accept</strong>
                        </button>
                    </li>
                `);

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