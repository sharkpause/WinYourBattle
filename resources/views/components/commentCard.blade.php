@props(['comment', 'full' => false])

<div class="mt-4 p-3 border-radius-1-rem shadow-sm">
    <div class="d-flex">
        <div class="flex-shrink-0">
            <img src="{{ asset('storage/' .  $comment->user->image) }}" class="hw-40px rounded-circle">
        </div>
        <div class="flex-grow-1 ms-3">
            <div>
                <a class="no-underline me-3" href="{{ route('posts.user', $comment->post->user) }}">{{ $comment->user->username }}</a>
                <span class="text-muted">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <div>
                {{ $comment->body }}
            </div>
        </div>
    </div>
</div>