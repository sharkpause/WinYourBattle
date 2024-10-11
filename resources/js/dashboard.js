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

    try {
        const response = await axios.get('/get-statistics');
        const responseDataLength = Object.keys(response.data).length;

        let timeUnit;
        let labels = [];
        let dataset = [];
        for(let i = responseDataLength - 1; i > 0; --i) {
            console.log(response.data[i].relapse_date, userTimezone);
            labels.push(
                (new Date(response.data[i].relapse_date)).toLocaleString('en-CA')
            );
            dataset.push(response.data[i].streak_time);
        }

        if(responseDataLength > 0) {
            const beforeRelapse = new Date(response.data[responseDataLength - 1].relapse_date);
            const nowTime = new Date();
            console.log(Math.abs(beforeRelapse - nowTime) / 1000 / 60 / 60);
        }

        if(Math.max(...dataset) < 3600) {
            for(let i = 0; i < dataset.length; ++i) {
                dataset[i] /= 60;
                timeUnit = 'Minutes';
            }
        } else if(Math.max(...dataset) < 86,400) {
            for(let i = 0; i < dataset.length; ++i) {
                dataset[i] /= 3600;
                timeUnit = 'Hours';
            }
        }

        const ctx = $('#relapseChart')[0].getContext('2d');

        const data = {
            labels: labels, 
            datasets: [{
                label: 'Relapses', 
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
        chart.update();
    } catch(err) {
        console.log(err);
        
        const relapseChartError = $('#relapseChartError');
        relapseChartError.text('Sorry, there was an unexpected problem when getting the chart :(');
        $('#relapseChartContainer').height('400px');
    }
});