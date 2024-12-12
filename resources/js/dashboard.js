import $ from 'jquery';
import Chart from 'chart.js/auto';
import axios from 'axios';
import Swal from 'sweetalert2';
import zoomPlugin from 'chartjs-plugin-zoom';
import './jsCalendar.js';

import './components/alert.js'

Chart.register(zoomPlugin);

function convertTime(seconds, precise) {
    let timeSeconds = seconds;
    let timeString = '';

    if(timeSeconds > 86400) {
        const days = String(Math.floor(timeSeconds / 86400)); 
        timeSeconds -= Number(days) * 86400;
        if(timeSeconds === 0) {
            timeString += days + ' days';
        } else {
            timeString += days + ' days, ';
        }
    }
    if(timeSeconds > 3600) {
        const hours = String(Math.floor(timeSeconds / 3600));
        timeSeconds -= Number(hours) * 3600;
        if(timeSeconds === 0) {
            timeString += hours + ' hours';
        } else {
            timeString += hours + ' hours, ';
        }
    }
    if(precise) {
        if(timeSeconds > 60) {
            const minutes = String(Math.floor(timeSeconds / 60));
            timeSeconds -= Number(minutes) * 60;
            if(timeSeconds === 0) {
                timeString += minutes + ' minutes';
            } else {
                timeString += minutes + ' minutes, ';
            }
        }
        if(timeSeconds > 0) {
            timeString += timeSeconds + ' seconds';
        }
    }

    return timeString;
}

let latestRelapse;

const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
const time = (new Date()).getHours();
let phrase, emoji;

if(time >= 21) {
    phrase = 'Good Night, ';
    emoji = '🌙'; 
} else if(time >= 15) {
    phrase = 'Good Evening, ';
    emoji = '🍵';
} else if(time >= 10) {
    phrase = 'Good Afternoon, ';
    emoji = '☀️';
} else if(time >= 4) {
    phrase = 'Good Morning, ';
    emoji = '☕';
}

$('#current-time').text(phrase);
$('#current-emoji').text(emoji);
$('#timezone-input').val(userTimezone);

(async () => {
    try {
        const response = await axios.get('/get-statistics');
        const responseDataLength = Object.keys(response.data).length;

        if(responseDataLength <= 0) return $('#chart-container').empty();

        latestRelapse = response.data[0].relapse_date;

        let labels = [];
        let dataset = [];
        for(let i = responseDataLength - 1; i > 0; --i) {
            labels.push(
                (new Date(response.data[i].relapse_date)).toLocaleString('en-CA').replace(/\.$/, '').replace('p.m', 'PM').replace('a.m', 'AM')
            );
            dataset.push(response.data[i].streak_time);
        }

        const data = {
            labels: labels, 
            datasets: [{
                label: 'Streak times', 
                data: dataset,
                borderColor: 'rgba(75, 192, 192, 1)', 
                fill: false,
                spanGaps: true
            }]
        };

        const chartConfig = {
            type: 'line', 
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return convertTime(value, false);
                            }
                        }
                    },
                    x: {
                        type: 'category',
                        ticks: {
                            maxTicksLimit: 8,
                        },
                    },
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return convertTime(tooltipItem.raw, true);
                            }
                        }
                    },
                    zoom: {
                        pan: {
                            enabled: true,
                            mode: 'xy', // Allow panning on the x-axis
                        },
                        zoom: {
                            wheel: {
                                enabled: true, // Enable zooming with the mouse wheel
                            },
                            pinch: {
                                enabled: true, // Enable zooming with touch gestures
                            },
                            mode: 'x', // Allow zooming on the x-axis
                        },
                    },
                }
            }
        };
        
        const chart = new Chart($('#relapse-chart')[0].getContext('2d'), chartConfig);

        $('#reset-chart-view-button').on('click', () => {
            chart.resetZoom();
        });
    } catch(err) {
        console.log(err);
        
        const relapseChartError = $('#relapse-chart-error');
        relapseChartError.text('Sorry, there was an unexpected problem when getting the chart :(');
        $('#relapse-chart-container').height('400px');
    }
})();

function updateTime() {
    const diffInSeconds = Math.floor(((new Date()) - new Date(latestRelapse)) / 1000);
    
    const days = Math.floor(diffInSeconds / (3600 * 24));
    const hours = Math.floor((diffInSeconds % (3600 * 24)) / 3600);
    const minutes = Math.floor((diffInSeconds % 3600) / 60);
    const seconds = diffInSeconds % 60;
    
    let timeString = '';

    if(days > 0) {
        timeString += days + ' day' + (days > 1 ? 's' : '') + ', ';
    }
    if(hours > 0) {
        timeString += hours + ' hour' + (hours > 1 ? 's' : '') + ', ';
    }
    if(minutes > 0) {
        timeString += minutes + ' minute' + (minutes > 1 ? 's' : '') + ', ';
    }
    if(seconds > 0) {
        timeString += seconds + ' second' + (seconds > 1 ? 's' : '');
    }

    $('#relapse-time-text').text(timeString);
}
setInterval(updateTime, 1000);

$('#reset-relapse-data-form').on('submit', async function(e) {
    e.preventDefault();

    const result = await Swal.fire({
        title: 'Are you sure you want to reset all relapse data?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'Confirm',
        denyButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
        $('#set-new-relapse-button').attr('disabled', 'disabled');
        $('#reset-relapse-data-button').attr('disabled', 'disabled');
        this.submit();
    }
});


$('#set-new-relapse-form').on('submit', function(e) {
    $('#set-new-relapse-button').attr('disabled', 'disabled');
    $('#reset-relapse-data-button').attr('disabled', 'disabled');
});

$('#set-nitial-relapse-form').on('submit', function(e) {
    $('#set-initial-relapse-button').attr('disabled', 'disabled');
});

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

$(document).on('click', '.auto-jsCalendar table td', function(e) {
    e.preventDefault();
    alert('a');
    console.log('a');
});

$('#mood-face').on('click', async function(e) {
    let userMoodIcon;
    let userMoodText;

    const customAlert = Swal.mixin({
        customClass: {
            popup: 'border-radius-1-rem width-850px'
        }
    })

    const result = await customAlert.fire({
        title: 'How do you feel today?',
        input: 'radio',
        inputOptions: {
            '0': '<i data-text="Sad" data-mood="fa-sad-cry" class="pointer-on-hover mood-icon center-icon fa fa-sad-cry"></i> Sad',
            '1': '<i data-text="Angry" data-mood="fa-angry" class="pointer-on-hover mood-icon center-icon fa fa-angry"></i> Angry',
            '2': '<i data-text="Lonely" data-mood="fa-sad-tear" class="pointer-on-hover mood-icon center-icon fa fa-sad-tear"></i> Lonely',
            '3': '<i data-text="Stressed" data-mood="fa-tired" class="pointer-on-hover mood-icon center-icon fa fa-tired"></i> Stressed',
            '4': '<i data-text="Regret" data-mood="fa-frown" class="pointer-on-hover mood-icon center-icon fa fa-frown"></i> Regret',
            '5': '<i data-text="Excited" data-mood="fa-grin-squint" class="pointer-on-hover mood-icon center-icon fa fa-grin-squint"></i> Excited',
            '6': '<i data-text="Content" data-mood="fa-smile" class="pointer-on-hover mood-icon center-icon fa fa-smile"></i> Content',
            '7': '<i data-text="Grateful" data-mood="fa-heart" class="pointer-on-hover mood-icon center-icon fa fa-heart"></i> Grateful',
            '8': '<i data-text="Happy" data-mood="fa-grin-beam" class="pointer-on-hover mood-icon center-icon fa fa-grin-beam"></i> Happy',
            '9': '<i data-text="Indifferent" data-mood="fa-meh" class="pointer-on-hover mood-icon center-icon fa fa-meh"></i> Indifferent',
        },
        animation: false,
        showConfirmButton: false,
        didOpen: () => {
            const radioButtons = $('input[name="swal2-radio"]');
            for(let i = 0; i < radioButtons.length; ++i) {
                $(radioButtons[i]).hide();
            }
            $('.mood-icon').on('click', function(e) {
                userMoodIcon = $(this).attr('data-mood');
                userMoodText = $(this).attr('data-text');
                Swal.close();
            });
        }
    });

    $('#mood-face').removeClass().addClass(`fa-regular ${userMoodIcon} mt-5 font-size-100px pointer-on-hover`);
    $('#mood-text').text(userMoodText);
});

$('#start-writing-entry-button').on('click', function(e) {
    if ($(this).text() === 'Start writing an entry') {
        $('#journal-entry-textarea').removeClass('hidden').height(260);
        $('#submit-entry-button').removeClass('hidden');
        $('#journal-entry-text').addClass('hidden');
        $(this).text('Cancel');s
    } else if ($(this).text() === 'Cancel') {
        $('#journal-entry-textarea').addClass('hidden').removeClass('max-height-260px');
        $('#journal-entry-text').removeClass('hidden');
        $('#submit-entry-button').addClass('hidden');
        $(this).text('Start writing an entry');
    }
});

$('#submit-entry-button').on('click', function(e) {
    alert('submit');
});