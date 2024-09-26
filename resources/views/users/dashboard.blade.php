<x-layout>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

                const response = await fetch('/set-timezone', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ timezone: userTimezone })
                })

                const time = Number((await response.json()).current_time);
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