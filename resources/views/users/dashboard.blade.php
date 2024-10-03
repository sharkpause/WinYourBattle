

<x-layout>

    <x-navbarSpace></x-navbarSpace>

    <div class="card container border-radius-2-rem shadow">
    <div class="card-body m-3">

    <span id="current-time" class="h1"></span>
    <span class="h1">{{ auth()->user()->username }}</span>
    <span class="h1 ms-2" id="current-emoji"></span>
    <div class="mb-5 mt-5"></div>

    @if($statistics === null || $statistics->date_of_relapse === null)
        <p>You haven't set a relapse date yet</p>
    
        <form method="POST" action="{{ route('set-relapse-date') }}" class="form-inline">
            @csrf
            @method('PUT')

            <div class="form-group col-5">
            <div class="input-group">
                <input type="hidden" id="timezoneInput" name="timezone">
                <input name="date_of_relapse" type="date" class="form-control">
                <input name="time_of_relapse" type="time" class="form-control" step="1">
                <div class="input-group-append">
                    <button class="btn btn-primary set-relapse-button" type="submit">Set relapse date</button>
                </div>
                @error('statistic_failed')
                    <p class="ms-1 text-danger">{{ $message }}</p>
                @enderror
            </div>
            </div>
        </form>

        @error('date_of_relapse')
            <span class="ms-1 text-danger me-3">{{ $message }}</span>
        @enderror
        @error('time_of_relapse')
            <span class="ms-1 text-danger">{{ $message }}</span>
        @enderror
    @else
        <p>It has been
            <span class="text-green">
                {{
                    
                    $statistics->date_of_relapse

                }}
            </span> since you relapsed, keep it up!
        </p>

        <form method="POST" action="{{ route('new-relapse') }}" class="form-inline">
            @csrf
            @method('PUT')

            <button class="btn btn-warning text-white" type="submit">I relapsed</button>
        </form>
    @endif
    
    </div></div>

    <div class="container mt-7">
        <h1 class="mb-4">Your latest posts</h1>

        @foreach ($posts as $post)
            <x-postCard :post="$post"></x-postCard>
        @endforeach
    </div>

    <x-paginator :posts="$posts"></x-paginator>

    @vite('resources/js/dashboard.js')

    </div>

</x-layout>