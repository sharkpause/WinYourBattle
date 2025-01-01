<x-layout>

    <x-navbarSpace></x-navbarSpace>

    <div class="card container border-radius-2-rem shadow">
    @if(session('success'))
        <x-alert msg="{{ session('success') }}" classes="text-white success-alert"></x-alert>
    @endif
    <div class="card-body m-3">
        <div class="d-flex align-items-start justify-content-between">
            <div class="d-flex align-items-start">
                <img src="{{ asset('storage/' . $user->image) }}" class="rounded-circle hw-200px">
                
                <div class="ms-5">
                    <div class="d-flex align-items-center">
                        <span class="h1">{{ $user->username }}</span>
                        @auth
                        @if(Auth::id() !== $user->id && !Auth::user()->followings()->where('following_id', $user->id)->exists())
                            <button class="btn-no-hover ms-4 btn-gray fw-bold"
                                    id="follow-button"
                                    data-url="{{ route('users.follow', $user->id) }}"
                                    data-csrf-token="{{ csrf_token() }}">Follow</button>
                        @endif
                        @endauth
                    </div>

                    <p class="text-muted fs-6">Account created {{ $user->created_at->diffForHumans() }}</p>
                    <p class="fs-5">{{ $user->bio }}</p>
                </div>
            </div>
                
            @auth
            @if(Auth::id() === $user->id)
            <div>
                <i type="button" id="navbar-dropdown" data-bs-toggle="dropdown" aria-expanded="false"
                   class="fa-solid fa-ellipsis-vertical expand-clickable-area-1-rem"></i>
                <div class="dropdown-menu dropdown-menu-end post-menu-dropdown-margin shadow border-radius-1-rem" aria-labelledby="navbar-dropdown">
                    <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item no-underline">Edit Account Info</a>
                    <form action="{{ route('users.delete', $user->id) }}" method="POST" id="delete-account-form">
                        @csrf
                        @method('DELETE')

                        <button class="dropdown-item red-on-hover" type="submit" id="delete-account-button">Delete Account</button>
                    </form>
                </div>
            </div>
            @endif
            @endauth
        </div>
    </div>
    </div>

    @if($posts->total() > 0)
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

@vite('resources/js/profile.js')