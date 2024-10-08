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

        let labels = [];
        let dataset = [];
        for(let i = Object.keys(response.data).length - 1; i >= 0; --i) {
            labels.push(response.data[i].relapse_date);
            dataset.push(response.data[i].streak_time);
        }

        if(Math.max(...dataset) < 86,400) {
            for(let i = 0; i < dataset.length; ++i) {
                dataset[i] /= 3600;
            }
        } else if(dataset.max() < 3600) {
            for(let i = 0; i < dataset.length; ++i) {
                dataset[i] /= 60;
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
            }
        });

        chart.config.data.labels.push("A label");
        chart.config.data.labels.push("A label2");
        chart.config.data.datasets[0].data.push(10);
        chart.config.data.datasets[0].data.push(20);
        chart.update();
    } catch(err) {
        console.log(err);
        
        const relapseChartError = $('#relapseChartError');
        relapseChartError.text('Sorry, there was an unexpected problem when getting the chart :(');
        $('#relapseChartContainer').height('400px');
    }
});