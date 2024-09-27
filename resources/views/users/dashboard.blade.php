<x-layout>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

                const time = (new Date()).getHours();
                let phrase;

                if(time >= 21) {
                    phrase = 'Good Night, ';
                } else if(time >= 17) {
                    phrase = 'Good Evening, ';
                } else if(time >= 12) {
                    phrase = 'Good Afternoon, ';
                } else if(time >= 4) {
                    phrase = 'Good Morning, ';
                }

                document.querySelector('#current-time').innerText = phrase;
            } catch(err) {
                console.log(err);
            }
        });
    </script>

    <span id="current-time" class="h1"></span>
    <span class="h1">{{ auth()->user()->username }}</span>

</x-layout>