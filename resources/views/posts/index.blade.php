<x-layout>
    <div class="container mt-7">

        @if(session('success'))
            <x-alert msg="{{ session('success') }}" classes="text-white post-success-alert"></x-alert>
        @endif

        @auth
        <input type="text" class="form-control border-radius-2-rem mb-5" id="user-post-input" placeholder="What are you thinking right now?" data-url="{{ route('posts.create') }}">
        @endauth

        @foreach ($posts as $post)
            <x-postCard :post="$post"></x-postCard>
        @endforeach
    </div>

    <div class="container justify-content-end d-flex">
        {{ $posts->links() }}
    </div>

    @vite('resources/js/index.js')
</x-layout>