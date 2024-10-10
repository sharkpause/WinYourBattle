<x-layout>

    <x-navbarSpace></x-navbarSpace>

    <div class="container">
        @if(session('success'))
            <x-alert msg="{{ session('success') }}" classes="text-white post-success-alert"></x-alert>
        @endif

        <div class="mb-5">
            <span class="h1 me-3">Latest Posts</span>
            <span class="text-muted">Be sure to take a break!</span>
        </div>

        @auth
            <input type="text" class="shadow p-3 form-control border-radius-2-rem mb-5" id="user-post-input" placeholder="What are you thinking right now?" data-url="{{ route('posts.create') }}">
        @endauth

        @foreach ($posts as $post)
            <x-postCard :post="$post"></x-postCard>
        @endforeach
    </div>

    <x-paginator :posts="$posts"></x-paginator>

    @vite('resources/js/index.js')
</x-layout>