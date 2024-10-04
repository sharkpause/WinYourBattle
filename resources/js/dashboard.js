import $ from 'jquery';
import Chart from 'chart.js/auto';
import axios from 'axios';

$(document).ready(() => {
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

    var ctx = $('#myChart')[0].getContext('2d');

    var data = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'], // X-axis labels
        datasets: [{
            label: 'Sales', // Name of the dataset
            data: [65, 59, 80, 81, 56, 55, 40], // Y-axis values
            borderColor: 'rgba(75, 192, 192, 1)', // Line color
            fill: false // Disable fill under the line
        }]
    };
    
    var myChart = new Chart(ctx, {
        type: 'line', // Chart type
        data: data,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});