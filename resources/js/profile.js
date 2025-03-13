import $ from 'jquery';
import Swal from 'sweetalert2';
import axios from 'axios';

import './components/alert.js';

let followerListPage = 1; // For pagination
let followingListPage = 1;

let followerCountReal = Number($('#follower-count').text().split(' ')[0]); // Split because this .text() return "n Followers"
let followingCountReal = Number($('#following-count').text().split(' ')[0]);

const ownProfilePage = Number($('#own-profile-page').attr('data-own-profile-page'));

const TIMEZONE_COUNTRY_MAP = {
    "Africa/Abidjan": "Ivory Coast",
    "Africa/Accra": "Ghana",
    "Africa/Addis_Ababa": "Ethiopia",
    "Africa/Algiers": "Algeria",
    "Africa/Asmara": "Eritrea",
    "Africa/Bamako": "Mali",
    "Africa/Bangui": "Central African Republic",
    "Africa/Banjul": "Gambia",
    "Africa/Bujumbura": "Burundi",
    "Africa/Cairo": "Egypt",
    "Africa/Casablanca": "Morocco",
    "Africa/Ceuta": "Spain",
    "Africa/Conakry": "Guinea",
    "Africa/Dakar": "Senegal",
    "Africa/Dar_es_Salaam": "Tanzania",
    "Africa/Djibouti": "Djibouti",
    "Africa/El_Aaiun": "Western Sahara",
    "Africa/Freetown": "Sierra Leone",
    "Africa/Gaborone": "Botswana",
    "Africa/Harare": "Zimbabwe",
    "Africa/Johannesburg": "South Africa",
    "Africa/Juba": "South Sudan",
    "Africa/Kampala": "Uganda",
    "Africa/Khartoum": "Sudan",
    "Africa/Kigali": "Rwanda",
    "Africa/Kinshasa": "Democratic Republic of the Congo",
    "Africa/Lagos": "Nigeria",
    "Africa/Libreville": "Gabon",
    "Africa/Lome": "Togo",
    "Africa/Luanda": "Angola",
    "Africa/Lubumbashi": "Democratic Republic of the Congo",
    "Africa/Luanda": "Angola",
    "Africa/Malabo": "Equatorial Guinea",
    "Africa/Maputo": "Mozambique",
    "Africa/Maseru": "Lesotho",
    "Africa/Mbabane": "Eswatini",
    "Africa/Mogadishu": "Somalia",
    "Africa/Monrovia": "Liberia",
    "Africa/Nairobi": "Kenya",
    "Africa/Ndjamena": "Chad",
    "Africa/Porto-Novo": "Benin",
    "Africa/Tripoli": "Libya",
    "Africa/Tunis": "Tunisia",
    "Africa/Windhoek": "Namibia",
    "America/Adak": "United States",
    "America/Anchorage": "United States",
    "America/Anguilla": "Anguilla",
    "America/Antigua": "Antigua and Barbuda",
    "America/Araguaina": "Brazil",
    "America/Argentina/Buenos_Aires": "Argentina",
    "America/Argentina/Catamarca": "Argentina",
    "America/Argentina/ComodRivadavia": "Argentina",
    "America/Argentina/Cordoba": "Argentina",
    "America/Argentina/Jujuy": "Argentina",
    "America/Argentina/La_Rioja": "Argentina",
    "America/Argentina/Mendoza": "Argentina",
    "America/Argentina/Rio_Gallegos": "Argentina",
    "America/Argentina/Salta": "Argentina",
    "America/Argentina/San_Juan": "Argentina",
    "America/Argentina/San_Luis": "Argentina",
    "America/Argentina/Tucuman": "Argentina",
    "America/Argentina/Ushuaia": "Argentina",
    "America/Aruba": "Aruba",
    "America/Asuncion": "Paraguay",
    "America/Atikokan": "Canada",
    "America/Bahia": "Brazil",
    "America/Bahia_Banderas": "Mexico",
    "America/Barbados": "Barbados",
    "America/Belem": "Brazil",
    "America/Belize": "Belize",
    "America/Blanc-Sablon": "Canada",
    "America/Boa_Vista": "Brazil",
    "America/Bogota": "Colombia",
    "America/Boise": "United States",
    "America/Cambridge_Bay": "Canada",
    "America/Campo_Grande": "Brazil",
    "America/Cancun": "Mexico",
    "America/Caracas": "Venezuela",
    "America/Cayenne": "French Guiana",
    "America/Cayman": "Cayman Islands",
    "America/Chicago": "United States",
    "America/Chihuahua": "Mexico",
    "America/Costa_Rica": "Costa Rica",
    "America/Creston": "Canada",
    "America/Cuiaba": "Brazil",
    "America/Curacao": "Curacao",
    "America/Danmarkshavn": "Greenland",
    "America/Dawson": "Canada",
    "America/Dawson_Creek": "Canada",
    "America/Denver": "United States",
    "America/Detroit": "United States",
    "America/Dominica": "Dominica",
    "America/Edmonton": "Canada",
    "America/Eirunepe": "Brazil",
    "America/El_Salvador": "El Salvador",
    "America/Fortaleza": "Brazil",
    "America/Glace_Bay": "Canada",
    "America/Godthab": "Greenland",
    "America/Goose_Bay": "Canada",
    "America/Grand_Turk": "Turks and Caicos Islands",
    "America/Grenada": "Grenada",
    "America/Guadeloupe": "Guadeloupe",
    "America/Guayaquil": "Ecuador",
    "America/Guyana": "Guyana",
    "America/Houston": "United States",
    "America/Indianapolis": "United States",
    "America/Inuvik": "Canada",
    "America/Iqaluit": "Canada",
    "America/Jamaica": "Jamaica",
    "America/Juneau": "United States",
    "America/Kentucky/Louisville": "United States",
    "America/Kentucky/Monticello": "United States",
    "America/La_Paz": "Bolivia",
    "America/Lima": "Peru",
    "America/Los_Angeles": "United States",
    "America/Louisville": "United States",
    "America/Maceio": "Brazil",
    "America/Managua": "Nicaragua",
    "America/Manaus": "Brazil",
    "America/Marigot": "Saint Martin",
    "America/Martinique": "Martinique",
    "America/Mexico_City": "Mexico",
    "America/Miquelon": "Saint Pierre and Miquelon",
    "America/Moncton": "Canada",
    "America/Montevideo": "Uruguay",
    "America/Nassau": "Bahamas",
    "America/New_York": "United States",
    "America/Nipigon": "Canada",
    "America/Nome": "United States",
    "America/Noronha": "Brazil",
    "America/North_Dakota/Center": "United States",
    "America/North_Dakota/New_Salem": "United States",
    "America/Panama": "Panama",
    "America/Pangnirtung": "Canada",
    "America/Paramaribo": "Suriname",
    "America/Phoenix": "United States",
    "America/Port-au-Prince": "Haiti",
    "America/Port_of_Spain": "Trinidad and Tobago",
    "America/Puerto_Rico": "Puerto Rico",
    "America/Rainy_River": "Canada",
    "America/Rankin_Inlet": "Canada",
    "America/Recife": "Brazil",
    "America/Regina": "Canada",
    "America/Resolute": "Canada",
    "America/Rio_Branco": "Brazil",
    "America/Rosario": "Argentina",
    "America/Santarem": "Brazil",
    "America/Santiago": "Chile",
    "America/Santo_Domingo": "Dominican Republic",
    "America/Sao_Paulo": "Brazil",
    "America/Scoresbysund": "Greenland",
    "America/Sitka": "United States",
    "America/St_Barthelemy": "Saint Barthélemy",
    "America/St_Johns": "Canada",
    "America/St_Kitts": "Saint Kitts and Nevis",
    "America/St_Lucia": "Saint Lucia",
    "America/St_Thomas": "United States Virgin Islands",
    "America/Swift_Current": "Canada",
    "America/Tegucigalpa": "Honduras",
    "America/Thule": "Greenland",
    "America/Thunder_Bay": "Canada",
    "America/Tijuana": "Mexico",
    "America/Toronto": "Canada",
    "America/Tortola": "British Virgin Islands",
    "America/Vancouver": "Canada",
    "America/Whitehorse": "Canada",
    "America/Winnipeg": "Canada",
    "America/Yakutat": "United States",
    "America/Yellowknife": "Canada",
    "Antarctica/Palmer": "Antarctica",
    "Asia/Aden": "Yemen",
    "Asia/Almaty": "Kazakhstan",
    "Asia/Amman": "Jordan",
    "Asia/Anadyr": "Russia",
    "Asia/Aqtau": "Kazakhstan",
    "Asia/Aqtobe": "Kazakhstan",
    "Asia/Arbil": "Iraq",
    "Asia/Ashgabat": "Turkmenistan",
    "Asia/Baghdad": "Iraq",
    "Asia/Baku": "Azerbaijan",
    "Asia/Bangkok": "Thailand",
    "Asia/Barnaul": "Russia",
    "Asia/Beirut": "Lebanon",
    "Asia/Bishkek": "Kyrgyzstan",
    "Asia/Brunei": "Brunei",
    "Asia/Chita": "Russia",
    "Asia/Choibalsan": "Mongolia",
    "Asia/Colombo": "Sri Lanka",
    "Asia/Damascus": "Syria",
    "Asia/Dhaka": "Bangladesh",
    "Asia/Dili": "East Timor",
    "Asia/Dubai": "United Arab Emirates",
    "Asia/Dushanbe": "Tajikistan",
    "Asia/Gaza": "Palestine",
    "Asia/Hong_Kong": "Hong Kong",
    "Asia/Hovd": "Mongolia",
    "Asia/Irkutsk": "Russia",
    "Asia/Jakarta": "Indonesia",
    "Asia/Jayapura": "Indonesia",
    "Asia/Jerusalem": "Israel",
    "Asia/Kabul": "Afghanistan",
    "Asia/Kamchatka": "Russia",
    "Asia/Karachi": "Pakistan",
    "Asia/Kathmandu": "Nepal",
    "Asia/Kolkata": "India",
    "Asia/Krasnoyarsk": "Russia",
    "Asia/Kuala_Lumpur": "Malaysia",
    "Asia/Kuching": "Malaysia",
    "Asia/Kuwait": "Kuwait",
    "Asia/Macau": "Macau",
    "Asia/Magadan": "Russia",
    "Asia/Makassar": "Indonesia",
    "Asia/Manila": "Philippines",
    "Asia/Muscat": "Oman",
    "Asia/Nicosia": "Cyprus",
    "Asia/Novokuznetsk": "Russia",
    "Asia/Novosibirsk": "Russia",
    "Asia/Omsk": "Russia",
    "Asia/Oral": "Kazakhstan",
    "Asia/Phnom_Penh": "Cambodia",
    "Asia/Pontianak": "Indonesia",
    "Asia/Qatar": "Qatar",
    "Asia/Qyzylorda": "Kazakhstan",
    "Asia/Riyadh": "Saudi Arabia",
    "Asia/Sakhalin": "Russia",
    "Asia/Samarkand": "Uzbekistan",
    "Asia/Seoul": "South Korea",
    "Asia/Singapore": "Singapore",
    "Asia/Srednekolymsk": "Russia",
    "Asia/Taipei": "Taiwan",
    "Asia/Tashkent": "Uzbekistan",
    "Asia/Tbilisi": "Georgia",
    "Asia/Tehran": "Iran",
    "Asia/Tokyo": "Japan",
    "Asia/Ulaanbaatar": "Mongolia",
    "Asia/Urumqi": "China",
    "Asia/Vientiane": "Laos",
    "Asia/Vladivostok": "Russia",
    "Asia/Yakutsk": "Russia",
    "Asia/Yangon": "Myanmar",
    "Asia/Yekaterinburg": "Russia",
    "Asia/Yerevan": "Armenia",
    "Atlantic/Azores": "Portugal",
    "Atlantic/Bermuda": "Bermuda",
    "Atlantic/Canary": "Spain",
    "Atlantic/Cape_Verde": "Cape Verde",
    "Atlantic/Faroe": "Faroe Islands",
    "Atlantic/Madeira": "Portugal",
    "Atlantic/Reykjavik": "Iceland",
    "Atlantic/South_Georgia": "South Georgia and the South Sandwich Islands",
    "Australia/Adelaide": "Australia",
    "Australia/Brisbane": "Australia",
    "Australia/Broken_Hill": "Australia",
    "Australia/Currie": "Australia",
    "Australia/Darwin": "Australia",
    "Australia/Hobart": "Australia",
    "Australia/Lindeman": "Australia",
    "Australia/Lord_Howe": "Australia",
    "Australia/Melbourne": "Australia",
    "Australia/Perth": "Australia",
    "Australia/Sydney": "Australia",
    "Europe/Amsterdam": "Netherlands",
    "Europe/Andorra": "Andorra",
    "Europe/Astrakhan": "Russia",
    "Europe/Athens": "Greece",
    "Europe/Belgrade": "Serbia",
    "Europe/Berlin": "Germany",
    "Europe/Bratislava": "Slovakia",
    "Europe/Brussels": "Belgium",
    "Europe/Bucharest": "Romania",
    "Europe/Budapest": "Hungary",
    "Europe/Chisinau": "Moldova",
    "Europe/Copenhagen": "Denmark",
    "Europe/Dublin": "Ireland",
    "Europe/Gibraltar": "Gibraltar",
    "Europe/Guernsey": "Guernsey",
    "Europe/Helsinki": "Finland",
    "Europe/Isle_of_Man": "Isle of Man",
    "Europe/Istanbul": "Turkey",
    "Europe/Jersey": "Jersey",
    "Europe/Kaliningrad": "Russia",
    "Europe/Kiev": "Ukraine",
    "Europe/Kirov": "Russia",
    "Europe/Lisbon": "Portugal",
    "Europe/Ljubljana": "Slovenia",
    "Europe/London": "United Kingdom",
    "Europe/Luxembourg": "Luxembourg",
    "Europe/Madrid": "Spain",
    "Europe/Malta": "Malta",
    "Europe/Mariehamn": "Finland",
    "Europe/Minsk": "Belarus",
    "Europe/Monaco": "Monaco",
    "Europe/Moscow": "Russia",
    "Europe/Oslo": "Norway",
    "Europe/Paris": "France",
    "Europe/Podgorica": "Montenegro",
    "Europe/Prague": "Czech Republic",
    "Europe/Riga": "Latvia",
    "Europe/Rome": "Italy",
    "Europe/Samara": "Russia",
    "Europe/Saratov": "Russia",
    "Europe/Simferopol": "Russia",
    "Europe/Sofia": "Bulgaria",
    "Europe/Stockholm": "Sweden",
    "Europe/Tallinn": "Estonia",
    "Europe/Tirane": "Albania",
    "Europe/Ulyanovsk": "Russia",
    "Europe/Vaduz": "Liechtenstein",
    "Europe/Vatican": "Vatican City",
    "Europe/Vienna": "Austria",
    "Europe/Vilnius": "Lithuania",
    "Europe/Volgograd": "Russia",
    "Europe/Warsaw": "Poland",
    "Europe/Zagreb": "Croatia",
    "Europe/Zaporozhye": "Ukraine",
    "Indian/Antananarivo": "Madagascar",
    "Indian/Chagos": "British Indian Ocean Territory",
    "Indian/Christmas": "Australia",
    "Indian/Cocos": "Australia",
    "Indian/Comoro": "Comoros",
    "Indian/Kerguelen": "France",
    "Indian/Maldives": "Maldives",
    "Indian/Mauritius": "Mauritius",
    "Indian/Reunion": "Réunion",
    "Pacific/Apia": "Samoa",
    "Pacific/Auckland": "New Zealand",
    "Pacific/Chatham": "New Zealand",
    "Pacific/Chuuk": "Micronesia",
    "Pacific/Easter": "Chile",
    "Pacific/Efate": "Vanuatu",
    "Pacific/Fakaofo": "Tokelau",
    "Pacific/Fiji": "Fiji",
    "Pacific/Guadalcanal": "Solomon Islands",
    "Pacific/Guam": "United States",
    "Pacific/Honolulu": "United States",
    "Pacific/Kiritimati": "Kiribati",
    "Pacific/Kosrae": "Federated States of Micronesia",
    "Pacific/Kwajalein": "Marshall Islands",
    "Pacific/Majuro": "Marshall Islands",
    "Pacific/Marquesas": "French Polynesia",
    "Pacific/Midway": "United States",
    "Pacific/Nauru": "Nauru",
    "Pacific/Niue": "Niue",
    "Pacific/Norfolk": "Australia",
    "Pacific/Noumea": "New Caledonia",
    "Pacific/Pago_Pago": "American Samoa",
    "Pacific/Palau": "Palau",
    "Pacific/Ponape": "Federated States of Micronesia",
    "Pacific/Port_Moresby": "Papua New Guinea",
    "Pacific/Rarotonga": "Cook Islands",
    "Pacific/Saipan": "United States",
    "Pacific/Tahiti": "French Polynesia",
    "Pacific/Tarawa": "Kiribati",
    "Pacific/Tongatapu": "Tonga",
    "Pacific/Wake": "United States",
    "Pacific/Wallis": "Wallis and Futuna",
    "Pacific/Yap": "Federated States of Micronesia"
};

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

$(document).on('click', '.follow-button', async function(e) {
    e.preventDefault();

    if($(this).attr('data-private-account')) { // Checks if data-private-account exists since on follower, following popups, this attribute doesn't exist
        if($(this).attr('data-private-account').trim() === '1' || $(this).attr('data-private-account').trim() === 'true') { // 1 is public, 0 is private
            if($(this).attr('data-followed').trim() === 'false') {
                $(this).text('Requested');
                $(this).attr('data-followed', 'true');
                $(this).addClass('btn-primary');
                $(this).removeClass('btn-gray');
                $(this).addClass('btn-no-hover');
                $(this).removeClass('btn');
        
                try {
                    const response = await axios.post($(this).attr('data-follow-url'), { _token: $('#profile-follow-button').attr('data-csrf-token') });
                    if($(this).attr('id') === 'profile-follow-button') {
                        $('#follower-count').text(diffForHumans(response.data.followerCount) + ' Followers');
                        $('#following-count').text(diffForHumans(response.data.followingCount) + ' Followings');
                    }
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
                $(this).text('Follow');
                $(this).attr('data-followed', 'false');
                $(this).addClass('btn-primary');
                $(this).removeClass('btn-gray');
                $(this).removeClass('btn-no-hover');
                $(this).addClass('btn');
    
                try {
                    const response = await axios.delete($(this).attr('data-unfollow-url'), { _token: $('#profile-follow-button').attr('data-csrf-token') });
                    if($(this).attr('id') === 'profile-follow-button') {
                        $('#follower-count').text(diffForHumans(response.data.followerCount) + ' Followers');
                        $('#following-count').text(diffForHumans(response.data.followingCount) + ' Followings');
                    }
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
            return;
        }
    }

    if($(this).attr('data-followed').trim() === 'false') {
        $(this).text('Following');
        $(this).attr('data-followed', 'true');
        $(this).addClass('btn-primary');
        $(this).removeClass('btn-gray');
        $(this).addClass('btn-no-hover');
        $(this).removeClass('btn');

        if(ownProfilePage === 1 && $(this).attr('id') !== 'profile-follow-button') {
            followingCountReal += 1;
            $('#following-count').text(diffForHumans(followingCountReal + ' Followings'));
        }

        try {
            const response = await axios.post($(this).attr('data-follow-url'), { _token: $('#profile-follow-button').attr('data-csrf-token') });
            if($(this).attr('id') === 'profile-follow-button') {
                $('#follower-count').text(diffForHumans(response.data.followerCount) + ' Followers');
                $('#following-count').text(diffForHumans(response.data.followingCount) + ' Followings');
            }
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
        let result;

        const isInPopup = $(this).hasClass('following-list-follow-button') || $(this).hasClass('follower-list-follow-button') || false;
        if(!isInPopup) {
            const customAlert = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-danger',
                    popup: 'border-radius-1-rem'
                }
            })
        
            result = await customAlert.fire({
                title: 'Are you sure you want to unfollow this person?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Confirm',
                denyButtonText: 'Cancel',
                animation: false
            });
        }
    
        if(isInPopup || result.isConfirmed) {
            $(this).text('Follow');
            $(this).attr('data-followed', 'false');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-gray');
            $(this).removeClass('btn-no-hover');
            $(this).addClass('btn');

            if(!ownProfilePage && $(this).attr('id') === 'profile-follow-button') {
                //followerCountReal -= 1;
                $('#follower-count').text(diffForHumans(followerCountReal + ' Followers'));
            } else if(ownProfilePage && $(this).attr('id') !== 'profile-follow-button') {
                followingCountReal -= 1;
                $('#following-count').text(diffForHumans(followingCountReal + ' Followings'));
            }

            try {
                const response = await axios.delete($(this).attr('data-unfollow-url'), { _token: $('#profile-follow-button').attr('data-csrf-token') });
                if($(this).attr('id') === 'profile-follow-button') {
                    $('#follower-count').text(diffForHumans(response.data.followerCount) + ' Followers');
                    $('#following-count').text(diffForHumans(response.data.followingCount) + ' Followings');
                }
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

$('#account-info-button').on('click', async function() {
    let accountBasedInHTML = '';
    
    if($(this).attr('data-timezone').trim() !== 'null') {
        accountBasedInHTML = 
        `<p><strong>Account based in </strong> <span class="text-muted">${TIMEZONE_COUNTRY_MAP[$(this).attr('data-timezone')]}</span></p>`;
    }

    const customAlert = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary',
            popup: 'border-radius-1-rem shadow'
        }
    })

   await customAlert.fire({
        title: $(this).attr('data-username'),
        showDenyButton: false,
        showCancelButton: false,
        confirmButtonText: 'Confirm',
        denyButtonText: 'Cancel',
        animation: false,
        icon: 'info',
        html:
        `
            <div class="mt-3 text-start">
                <p><strong>Account created <span class="text-muted">${$(this).attr('data-created-ago')}</span></strong></p>
                <p><strong>Joined</strong> <span class="text-muted">${$(this).attr('data-created-at')}</span></p>
                ${accountBasedInHTML}
            </div>
        `,
    });
});

$('#follower-count').on('click', async function(e) {
    const customAlert = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary',
            popup: 'border-radius-1-rem shadow'
        }
    })

   await customAlert.fire({
        title: $(this).attr('data-username'),
        showDenyButton: false,
        showCancelButton: false,
        showConfirmButton: false,
        animation: false,
        html:
        `
        <ul class="list-unstyled w-100 max-height-50vh" id="follower-list">
            <i class="fa-solid fa-circle-notch spin mt-3"></i>
        </ul>
        `,
        didRender: async() => {
            const followerList = $('#follower-list');
            
            const followers = (await axios.get(
                $(this).attr('data-url') + '?page=' + followerListPage)
            ).data.followers.data;

            followerList.empty();

            if(followers.length <= 0) {
                followerList.prepend(`
                    <p class="text-center text-muted mt-3">No followers</p>
                `);
            } else {
                for(let i = 0; i < followers.length; ++i) {
                    if(followers[i].id === Number($(this).attr('data-auth-user-id').trim())) {
                        followerList.prepend(`
                            <li class="d-flex align-items-center justify-content-between p-2">
                                <div>
                                    <img src="${followers[i].imageURL}" class="rounded-circle me-2" width="30" height="30">
                                    <strong>${followers[i].username}</strong>
                                </div>
                            </li>
                        `);
                    } else {
                        let followedData;
                        if(followers[i].private) {
                            followedData = followers[i].requestedByAuth;
                        } else {
                            followedData = followers[i].followedByAuth;
                        }

                        followerList.append(`
                            <li class="d-flex align-items-center justify-content-between p-2">
                                <a href="${followers[i].profileURL}" class="no-styling pointer-on-hover">
                                    <img src="${followers[i].imageURL}" class="rounded-circle me-2" width="30" height="30">
                                    <strong>${followers[i].username}</strong>
                                </a>
                                <button class="btn btn-primary follow-button follower-list-follow-button"
                                        data-follow-url="${followers[i].followURL}"
                                        data-own-profile-page="${ownProfilePage}"
                                        data-unfollow-url="${followers[i].unfollowURL}"
                                        data-followed="${followedData}"
                                        data-private-account="${followers[i].private}">
                                    <strong>Follow</strong>    
                                </button>
                            </li>
                        `);

                        if(followers[i].followedByAuth) {
                            const followerListFollowButton = $('.follower-list-follow-button');
                            followerListFollowButton.addClass('btn-no-hover btn-gray');
                            followerListFollowButton.text('Following');
                        } else if(followers[i].requestedByAuth) {
                            const followerListFollowButton = $('.follower-list-follow-button');
                            followerListFollowButton.addClass('btn-no-hover btn-gray');
                            followerListFollowButton.text('Requested');
                        }
                    }
            }
            }
        }
    });
});

$('#following-count').on('click', async function(e) {
    const customAlert = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary',
            popup: 'border-radius-1-rem shadow'
        }
    })

   await customAlert.fire({
        title: $(this).attr('data-username'),
        showDenyButton: false,
        showCancelButton: false,
        showConfirmButton: false,
        animation: false,
        html:
        `
        <ul class="list-unstyled w-100 max-height-50vh" id="following-list">
            <i class="fa-solid fa-circle-notch spin mt-3"></i>
        </ul>
        `,
        didRender: async() => {
            const followingList = $('#following-list');
            
            const followings = (await axios.get(
                $(this).attr('data-url') + '?page=' + followingListPage)
            ).data.followings.data;

            if(followings.length <= 0) {
                followingList.prepend(`
                    <p class="text-center text-muted mt-3">No followers</p>
                `);
            } else {
                followingList.empty();
                for(let i = 0; i < followings.length; ++i) {
                    if(followings[i].id === Number($(this).attr('data-auth-user-id').trim())) {
                        followingList.prepend(`
                            <li class="d-flex align-items-center justify-content-between p-2">
                                <div>
                                    <img src="${followings[i].imageURL}" class="rounded-circle me-2" width="30" height="30">
                                    <strong>${followings[i].username}</strong>
                                </div>
                            </li>
                        `);
                    } else {
                        followingList.append(`
                            <li class="d-flex align-items-center justify-content-between p-2">
                                <a href="${followings[i].profileURL}" class="no-styling pointer-on-hover">
                                    <img src="${followings[i].imageURL}" class="rounded-circle me-2" width="30" height="30">
                                    <strong>${followings[i].username}</strong>
                                </a>
                                <button class="btn btn-primary follow-button following-list-follow-button"
                                        data-own-profile-page="${ownProfilePage}"
                                        data-follow-url="${followings[i].followURL}"
                                        data-unfollow-url="${followings[i].unfollowURL}"
                                        data-followed="${followings[i].followedByAuth}">
                                    <strong>Follow</strong>    
                                </button>
                            </li>
                        `);

                        if(followings[i].followedByAuth) {
                            const followingListFollowButton = $('.following-list-follow-button');
                            followingListFollowButton.addClass('btn-no-hover btn-gray');
                            followingListFollowButton.text('Following');
                        }
                    }
                }
            }
        }
    });
});