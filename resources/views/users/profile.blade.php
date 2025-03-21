<x-layout>

    <x-navbarSpace></x-navbarSpace>

    <div class="card container border-radius-2-rem shadow">
    @if(session('success'))
        <x-alert msg="{{ session('success') }}" classes="text-white success-alert"></x-alert>
    @endif
    <div class="card-body m-3">
        <div class="d-flex align-items-start justify-content-between">
            <div class="d-flex align-items-start">
                <img src="{{ $user->image }}" class="rounded-circle hw-200px">
                
                <div class="ms-5">
                    <div class="d-flex align-items-center">
                        <span class="h1">{{ $user->username }}</span>
                        @auth
                        @php
                            $isFollowing = DB::table('followings')
                                ->where('user_id', Auth::id())
                                ->where('following_id', $user->id)
                                ->exists();
                            $isRequesting = DB::table('follow_requests')
                                ->where('follower_id', Auth::id())
                                ->where('followed_id', $user->id)
                                ->exists();
                        @endphp
                        @if(Auth::id() !== $user->id)
                            <button class="ms-4 fw-bold follow-button @if($isFollowing || $isRequesting) btn-no-hover btn-gray @else btn btn-primary @endif"
                                    id="profile-follow-button"
                                    data-follow-url="{{ route('users.follow', $user->id) }}"
                                    data-unfollow-url="{{ route('users.unfollow', $user->id) }}"
                                    data-csrf-token="{{ csrf_token() }}"
                                    data-followed="@if($isFollowing || $isRequesting) true @else false @endif"
                                    data-private-account="{{ $private }}">
                                        @if($isFollowing) Following
                                        @elseif($isRequesting) Requested
                                        @else Follow @endif
                                    </button>
                        @endif
                        @endauth
                    </div>

                    <a id="post-count" class="pointer-on-hover underline-on-hover text-muted fs-6 no-underline"
                          href="{{ route('posts.user', $user->id) }}">{{ $user->posts()->count() }} Posts</a>
                    <span data-url="{{ route('users.followers', $user->id) }}"
                          data-auth-user-ID="{{ Auth::id() }}"
                          id="follower-count" 
                          class="pointer-on-hover underline-on-hover text-muted fs-6 ms-3">{{ $user->followers()->count() }} Followers</span>
                    <span data-url="{{ route('users.followings', $user->id) }}"
                          data-auth-user-ID="{{ Auth::id() }}"
                          id="following-count"
                          class="pointer-on-hover underline-on-hover text-muted fs-6 ms-3">{{ $user->followings()->count() }} Followings</span>

                    <p class="fs-5">{{ $user->bio }}</p>
                </div>
            </div>
                
            <div>
                <i type="button" id="navbar-dropdown" data-bs-toggle="dropdown" aria-expanded="false"
                   class="fa-solid fa-ellipsis-vertical expand-clickable-area-1-rem"></i>
                <div class="dropdown-menu dropdown-menu-end post-menu-dropdown-margin shadow border-radius-1-rem" aria-labelledby="navbar-dropdown">
                    @auth
                    @if(Auth::id() === $user->id)
                        <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item no-underline">Edit Account Info</a>
                        <form action="{{ route('users.delete', $user->id) }}" method="POST" id="delete-account-form">
                            @csrf
                            @method('DELETE')

                            <button class="dropdown-item red-on-hover" type="submit" id="delete-account-button">Delete Account</button>
                        </form>
                    @endif
                    @endauth
                    <a id="account-info-button"
                       class="dropdown-item no-underline pointer-on-hover"
                       data-timezone="{{ $user->statistics ? $user->statistics->timezone : 'null' }}"
                       data-created-at="{{ $user->created_at->format('d F, Y') }}"
                       data-created-ago="{{ $user->created_at->diffForHumans() }}"
                       data-username="{{ $user->username }}">Account info</a>
                </div>
            </div>
        </div>
    </div>
    </div>

    @if($private === false) @if($posts->total() > 0)
    <div class="container mt-5">
        <h1 class="mb-4">{{ Auth::user()->username === $user->username ? 'Your' : $user->username . "'s" }} latest posts</h1>

        @foreach($posts as $post)
            <x-postCard :post="$post" :id="$post->id"></x-postCard>
        @endforeach
    </div>
    @endif

    <x-paginator :items="$posts"></x-paginator>
    @else
    <div class="container mt-5 text-center">
        <i class="fa-solid fa-lock font-size-150-percent"></i>
        <span class="h4 ms-2 text-muted">This person's account is private, follow them to see their content!</span>
    </div>
    @endif

    </div>

    <span class="hidden" id="own-profile-page" data-own-profile-page="{{ Auth::id() === $user->id }}"></span>

</x-layout>

@vite('resources/js/profile.js')