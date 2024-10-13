import $ from 'jquery';
import Chart from 'chart.js/auto';
import axios from 'axios';

$(document).ready(async () => {
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

    let latestRelapse;
    try {
        const response = await axios.get('/get-statistics');
        const responseDataLength = Object.keys(response.data).length;

        if(responseDataLength <= 0) return;

        latestRelapse = response.data[0].relapse_date;

        let timeUnit = '';
        let labels = [];
        let dataset = [];
        for(let i = responseDataLength - 1; i > 0; --i) {
            labels.push(
                (new Date(response.data[i].relapse_date)).toLocaleString('en-CA').replace(/\.$/, '').replace('p.m', 'PM').replace('a.m', 'AM')
            );
            dataset.push(response.data[i].streak_time);
        }

        const maxData = Math.max(...dataset);
        if(maxData < 3600) {
            for(let i = 0; i < dataset.length; ++i) {
                dataset[i] /= 60;
                timeUnit = 'Minutes';
            }
        } else if(maxData < 86,400) { // TODO: Might change this design to only convert each dataset to its own time unit instead of converting everything into one time unit
            for(let i = 0; i < dataset.length; ++i) {
                dataset[i] /= 3600;
                timeUnit = 'Hours';
            }
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
                                return value + ' ' + timeUnit;
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.raw + ' ' + timeUnit;
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
});