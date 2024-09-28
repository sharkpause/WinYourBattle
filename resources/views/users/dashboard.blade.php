<x-layout>

    <div class="container">
    <div class="mt-7"></div>
    <script>
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
    </script>

    <span id="current-time" class="h1"></span>
    <span class="h1">{{ auth()->user()->username }}</span>
    <span class="h1 ms-2" id="current-emoji"></span>
    <div class="mb-5 mt-5"></div>
    
    @if(auth()->user()->date_of_relapse === null)
        <p>You haven't set a relapse date yet</p>
    
        <form method="POST" action="{{ route('set-relapse-date') }}" class="form-inline">
            @csrf

            <div class="form-group col-4">
            <div class="input-group">
                <input name="relapseDate" type="date" class="form-control">
                <div class="input-group-append">
                    <button class="btn btn-primary relapse-button" type="submit">Set relapse date</button>
                </div>
            </div>
            </div>
        </form>
    @endif
    </div>

</x-layout>