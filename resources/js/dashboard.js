import $ from 'jquery';
import Chart from 'chart.js/auto';
import axios from 'axios';
import Swal from 'sweetalert2'

import './components/alert.js'

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
$('#timezoneInput').val(userTimezone);

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
        
        new Chart($('#relapse-chart')[0].getContext('2d'), {
            type: 'line', 
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return convertTime(value, false);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return convertTime(tooltipItem.raw, true);
                            }
                        }
                    }
                }
            }
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