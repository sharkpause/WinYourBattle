<x-layout>
    <div class="container mt-7">

        @if(session('success'))
            <x-alert msg="{{ session('success') }}" classes="text-white post-success-alert"></x-alert>
        @endif

        @auth
        <input type="text" class="form-control border-radius-2-rem mb-5" id="user-post-input" placeholder="What are you thinking right now?" data-url="{{ route('posts.create') }}">
        @endauth

        @foreach ($posts as $post)
            <x-postCard profilePic='https://picsum.photos/30'
                        title='{{ $post->title }}'
                        username='USERNAME'
                        created_at='{{ $post->created_at->diffForHumans() }}'
                        wordLimit='{{ Str::words($post->body, 30) }}'>
            </x-postCard>
        @endforeach
    </div>

    <div class="container justify-content-end d-flex">
        {{ $posts->links() }}
    </div>

    @vite('resources/js/index.js')
</x-layout>