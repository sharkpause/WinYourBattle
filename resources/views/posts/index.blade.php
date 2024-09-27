<x-layout>
    <div class="container">
    
        <h1 class="mb-5">Latest Posts</h1>

        @foreach ($posts as $post)
            <div class="d-flex mb-3 bg-light p-3">
                <div class="flex-shrink-0">
                    <img src="https://picsum.photos/30" class="rounded-circle">
                </div>
                <div class="flex-grow-1 ms-3">
                    <span class="h5">{{ $post->title }} </span>
                    <div class="mb-3">
                        <span class="text-muted me-3">USERNAME</span>
                        <span class="text-muted">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <p>{{ Str::words($post->body, 30) }}</p>
                </div>
              </div>
        @endforeach
    </div>

    <div class="container justify-content-end d-flex">
        {{ $posts->links() }}
    </div>
</x-layout>