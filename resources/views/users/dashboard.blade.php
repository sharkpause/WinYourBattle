<x-layout>

    <x-navbarSpace></x-navbarSpace>

    <div class="card container border-radius-2-rem shadow">
    <div class="card-body m-3">
        <div class="d-flex align-items-start justify-content-between">
            <div class="d-flex align-items-start">
                <img src="{{ asset('storage/' . Auth::user()->image) }}" class="rounded-circle hw-200px">
                
                <div class="ms-5">
                    <h1>{{ Auth::user()->username }}</h1>
                    <p class="text-muted">{{ Auth::user()->bio }}</p>
                </div>
            </div>
                
            <div>
                <i type="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false" class="fa-solid fa-ellipsis-vertical expand-clickable-area-1-rem"></i>
                <div class="dropdown-menu dropdown-menu-end post-menu-dropdown-margin shadow border-radius-1-rem" aria-labelledby="navbarDropdown">
                    <a href="{{ route('users.edit', Auth::user()->id) }}" class="dropdown-item no-underline">Edit Account Info</a>
                    <form action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="dropdown-item red-on-hover" type="submit">Delete Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="mt-5 card container border-radius-2-rem shadow">
    <div class="card-body m-3">

    <span class="col-6">
        <span id="current-time" class="h1"></span>
        <span class="h1">{{ auth()->user()->username }}</span>
        <span class="h1 ms-2" id="current-emoji"></span>
    </span>

    <span class="float-end col-6">
        <p class="font-weight-light font-italic">{{ $quote->body }}</p>
        <span class="text-muted">- {{ $quote->person }}</span>
    </span>

    @if($statistics === null || $statistics->date_of_relapse === null)
        <p class="mt-3">You haven't set a relapse date yet</p>
    
        <form method="POST" action="{{ route('set-initial-relapse-date') }}" class="form-inline">
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
        <p class="mt-4 col-5">It has been
            <span class="text-green" id="relapseTimeText"></span>
            since you relapsed, keep it up!
        </p>

        <form method="POST" action="{{ route('set-new-relapse') }}" class="form-inline">
            @csrf
            @method('PUT')

            <button class="btn btn-primary btn-muted-blue text-white button-click-animation" type="submit">I relapsed</button>
        </form>
    @endif
    
    </div></div>

    <div id="chartContainer">
        <div class="container card mt-5 border-radius-2-rem shadow">
            <div class="card-body" id="relapseChartContainer">
                <div class="d-flex"><span id="relapseChartError" class="my-auto"></span></div>
                <canvas id="relapseChart"></canvas>
            </div>
        </div>
        <p class="text-muted fs-6 container">
            The chart shows the date and time of a relapse
            (the date and time when you click the "I relapse" button or
            when you set an initial relapse date and time) and how long
            the streak lasted until the next relapse (the next date and time if you click the "I relapse" button)
        </p>
    </div>

    @if($posts->total())
    <div class="container mt-5">
        <h1 class="mb-4">Your latest posts</h1>

        @foreach($posts as $post)
            <x-postCard :post="$post" :id="$post->id"></x-postCard>
        @endforeach
    </div>
    @endif

    <x-paginator :posts="$posts"></x-paginator>

    @vite('resources/js/dashboard.js')

    </div>

</x-layout>