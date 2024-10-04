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

    const response = await axios.get('/get-statistics');
    
    let labels = [];
    let dataset = [];
    for(let i = 0; i < response.data.length; ++i) {
        labels.push(response.data[i].relapse_date);
        dataset.push(response.data[i].streak_time);
    }

    const relapseChartElement = $('#relapseChart')[0];
    relapseChartElement.width = dataset.length * 150;
    let ctx = relapseChartElement.getContext('2d');

    let data = {
        labels: labels, 
        datasets: [{
            label: 'Relapses', 
            data: dataset,
            borderColor: 'rgba(75, 192, 192, 1)', 
            fill: false,
            spanGaps: true
        }]
    };
    
    let relapseChart = new Chart(ctx, {
        type: 'line', 
        data: data,
        options: {
            responsive: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            onAnimationComplete: function () {
                var sourceCanvas = this.chart.ctx.canvas;
                // The -5 is so that we don't copy the edges of the line
                var copyWidth = this.scale.xScalePaddingLeft - 5;
                // The +5 is so that the bottommost y axis label is not clipped off
                // We could factor this in using measureText if we wanted to be generic
                var copyHeight = this.scale.endPoint + 5;
                var targetCtx = $("#relapseChartAxis").getContext("2d");
                targetCtx.canvas.width = copyWidth;
                targetCtx.drawImage(sourceCanvas, 0, 0, copyWidth, copyHeight, 0, 0, copyWidth, copyHeight);
            }
        }
    });

    $('#chartContainer').scrollLeft($('#chartContainer')[0].scrollWidth);
});