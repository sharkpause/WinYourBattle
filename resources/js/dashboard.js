import $ from 'jquery';
import Chart from 'chart.js/auto';
import axios from 'axios';

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

(async () => {
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
    
    try {
        const response = await axios.get('/get-statistics');
        const responseDataLength = Object.keys(response.data).length;

        if(responseDataLength <= 0) return $('#chartContainer').empty();

        latestRelapse = response.data[0].relapse_date;

        let labels = [];
        let dataset = [];
        for(let i = responseDataLength - 1; i > 0; --i) {
            labels.push(
                (new Date(response.data[i].relapse_date)).toLocaleString('en-CA').replace(/\.$/, '').replace('p.m', 'PM').replace('a.m', 'AM')
            );
            dataset.push(response.data[i].streak_time);
        }

        const ctx = $('#relapseChart')[0].getContext('2d');

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
        
        const chart = new Chart(ctx, {
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
        
        const relapseChartError = $('#relapseChartError');
        relapseChartError.text('Sorry, there was an unexpected problem when getting the chart :(');
        $('#relapseChartContainer').height('400px');
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

    $('#relapseTimeText').text(timeString);
}

setInterval(updateTime, 1000);

$('#resetRelapseDataForm').on('submit', function(e) {
    $('#resetRelapseDataButton').attr('disabled', 'disabled');
    $(this)[0].requestSubmit();
});

$('#setNewRelapseForm').on('submit', function(e) {
    $('#setNewRelapseButton').attr('disabled', 'disabled');
    $(this)[0].requestSubmit();
});

$('#setInitialRelapseForm').on('submit', function(e) {
    e.preventDefault();

    $('#setInitialRelapseButton').attr('disabled', 'disabled');
    $(this)[0].requestSubmit();
});