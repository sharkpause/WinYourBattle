<x-layout>

    <x-navbarSpace></x-navbarSpace>

    <div class="card container border-radius-2-rem shadow">
    @if(session('success'))
        <x-alert msg="{{ session('success') }}" classes="text-white success-alert"></x-alert>
    @endif
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
                <i type="button" id="navbar-dropdown" data-bs-toggle="dropdown" aria-expanded="false"
                   class="fa-solid fa-ellipsis-vertical expand-clickable-area-1-rem"></i>
                <div class="dropdown-menu dropdown-menu-end post-menu-dropdown-margin shadow border-radius-1-rem" aria-labelledby="navbar-dropdown">
                    <a href="{{ route('users.edit', Auth::id()) }}" class="dropdown-item no-underline">Edit Account Info</a>
                    <form action="{{ route('users.delete', Auth::id()) }}" method="POST" id="delete-account-form">
                        @csrf
                        @method('DELETE')

                        <button class="dropdown-item red-on-hover" type="submit" id="delete-account-button">Delete Account</button>
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
        <span class="h1">{{ Auth::user()->username }}</span>
        <span class="h1 ms-2" id="current-emoji"></span>
    </span>

    <span class="float-end col-6">
        <p class="font-weight-light font-italic">{{ $quote->body }}</p>
        <span class="text-muted">- {{ $quote->person }}</span>
    </span>

    @if(Auth::user()->statistics === null || Auth::user()->statistics->date_of_relapse === null)
        <p class="mt-3">You haven't set a relapse date yet</p>
    
        <form method="POST" action="{{ route('set-initial-relapse-date') }}" class="form-inline" id="set-initial-relapse-form">
            @csrf
            @method('PUT')

            <div class="form-group col-5">
            <div class="input-group">
                <input type="hidden" id="timezone-input" name="timezone">
                <input name="date_of_relapse" type="date" class="form-control">
                <input name="time_of_relapse" type="time" class="form-control" step="1">
                <div class="input-group-append">
                    <button class="btn btn-primary set-relapse-button" type="submit" id="set-initial-relapse-button">Set relapse date</button>
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
            <span class="text-green" id="relapse-time-text"></span>
            since you relapsed, keep it up!
        </p>
        
        <div class="d-flex gap-1">
            <form action="{{ route('set-new-relapse') }}" method="POST" id="set-new-relapse-form">
                @csrf
                @method('PUT')

                <button class="btn-no-hover btn-muted-blue text-white button-click-animation" id="set-new-relapse-button">I relapsed</button>
            </form>
            <form action="{{ route('reset-relapse-data') }}" method="POST" id="reset-relapse-data-form">
                @csrf
                @method('DELETE')

                <button class="btn-no-hover btn-red text-white button-click-animation" id="reset-relapse-data-bbutton">Reset relapse data</button>
            </form>
        </div>
    @endif
    
    </div></div>

    <div id="chart-container">
        <div class="container card mt-5 border-radius-2-rem shadow">
            <div class="card-body" id="relapse-chart-container">
                <div class="d-flex"><span id="relapse-chart-error" class="my-auto"></span></div>
                <canvas id="relapse-chart"></canvas>
                <button class="btn btn-primary mt-3 float-end" id="reset-chart-view-button">Reset view</button>
            </div>
        </div>
        <p class="text-muted fs-6 container">
            The chart shows the date and time of a relapse
            (the date and time when you click the "I relapse" button or
            when you set an initial relapse date and time) and how long
            the streak lasted until the next relapse (the next date and time if you click the "I relapse" button).
            The datetime format is: YYYY-MM-DD HH:mm:ss
        </p>
    </div>

    <div class="container card mt-5 border-radius-2-rem shadow">
        <div class="row">
            <div class="col-auto my-3 mx-1">
                <div id="relapse-calendar" class="material-theme"></div>
            </div>
            
            <div class="col-md-2 my-3 me-1 border-radius-1-rem shadow d-flex flex-column align-items-center max-width-10-percent">
                <p class="text-center mt-3 h3 text-muted">Mood</p>
                <p class="text-center text-muted" id="mood-selected-date"></p>
                <i class="fa-regular fa-face-meh-blank mt-5 font-size-100px pointer-on-hover"
                   id="mood-icon"
                   data-url="{{ route('set-mood') }}"
                   data-csrf-token="{{ csrf_token() }}"></i>
                <p class="text-center mt-3 text-wrap" id="mood-text">
                    Not set yet, click the face to set a mood for this day!
                </p>
            </div>
            
            <div class="col my-3 mx-3 d-flex flex-column border-radius-1-rem shadow">
                <div class="mt-3">
                    <span class="h3 text-muted">Journal</span><span class="text-muted ms-2" id="journal-selected-date"></span>
                    <button class="btn btn-primary float-end" id="start-writing-entry-button">Start writing an entry</button>
                    <button class="btn btn-primary float-end me-1 hidden" id="submit-entry-button">Submit entry</button>
                </div>
                <div class="text-wrap overflow-auto max-height-260px break-word mt-2" id="journal-entry-text">
                    No entry today!
                </div>
                <textarea
                    class="text-wrap overflow-auto max-height-260px break-word mt-2 hidden height-250px overflow-y-scroll keep-whitespace"
                    id="journal-entry-textarea">No entry today!</textarea>
            </div>
        </div>
    </div>

    @if($posts->total())
    <div class="container mt-5">
        <h1 class="mb-4">Your latest posts</h1>

        @foreach($posts as $post)
            <x-postCard :post="$post" :id="$post->id"></x-postCard>
        @endforeach
    </div>
    @endif

    <x-paginator :items="$posts"></x-paginator>

    </div>

</x-layout>

@vite(['resources/js/dashboard.js', 'resources/css/jsCalendar.css'])