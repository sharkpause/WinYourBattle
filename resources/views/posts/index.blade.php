<x-layout>

    <x-navbarSpace></x-navbarSpace>

    <div class="container">
        @if(session('success'))
            <x-alert msg="{{ session('success') }}" classes="text-white success-alert"></x-alert>
        @endif
        @if(session('error'))
            <x-alert msg="{{ session('success') }}" classes="text-white danger-alert"></x-alert>
        @endif

        <div class="mb-5">
            <span class="h1 me-3">Latest Posts</span>
            <span class="text-muted">Be sure to take a break!</span>
        </div>

        @auth
            <input type="text"
                autocomplete="off"
                class="shadow p-3 form-control border-radius-2-rem mb-5 button-click-animation-sm"
                id="user-post-input"
                placeholder="What are you thinking right now?"
                data-url="{{ route('posts.create') }}">
        @endauth

        @foreach($posts as $post)
            <x-postCard :post="$post"></x-postCard>
        @endforeach
    </div>

    <x-paginator :items="$posts"></x-paginator>

    @vite(['resources/js/alert.js', 'resources/js/index.js'])
</x-layout>