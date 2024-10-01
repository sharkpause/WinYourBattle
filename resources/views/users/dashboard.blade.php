<x-layout>

    <div class="container">
    <div class="mt-7"></div>

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

    <div class="container mt-7">
        <h1 class="mb-4">Your latest posts</h1>

        @foreach ($posts as $post)
            <x-postCard :post="$post"></x-postCard>
        @endforeach
    </div>

    <div class="container justify-content-end d-flex">
        {{ $posts->links() }}
    </div>

    @vite('resources/js/dashboard.js')

</x-layout>