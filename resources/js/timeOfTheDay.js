document.addEventListener('DOMContentLoaded', async function() {
    try {
        const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        const time = (new Date()).getHours();
        let phrase, emoji;

        if(time >= 21) {
            phrase = 'Good Night, ';
            emoji = '🌙'; 
        } else if(time >= 17) {
            phrase = 'Good Evening, ';
            emoji = '🍵';
        } else if(time >= 12) {
            phrase = 'Good Afternoon, ';
            emoji = '☀️';
        } else if(time >= 4) {
            phrase = 'Good Morning, ';
            emoji = '☕';
        }

        document.querySelector('#current-time').innerText = phrase;
        document.querySelector('#current-emoji').innerText = emoji;
    } catch(err) {
        console.log(err);
    }
});