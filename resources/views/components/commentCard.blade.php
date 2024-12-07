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
            
            @auth
            <div class="mt-2">
                <button class="no-styling button-click-animation
                  @if($comment->likes()->where('user_id', Auth::id())->exists()) text-primary @endif"
                  id="likeButton" data-comment-id="{{ $comment->id }}"
                  data-csrf-token="{{ csrf_token() }}"
                  data-liked="@if($comment->likes()->where('user_id', Auth::id())->exists()) true @else false @endif">

                  <i class="fa-solid fa-thumbs-up me-1"></i>
                </button>
                <span id="likeCount" class="me-5">@if($comment->like_count <= 0) 0 @else {{ $comment->like_count }} @endif</span>
                <!-- The 0 is there on both like and dislike count because if
                    $comment->like_count of dislike_count is 0, it won't make anything appear on the front end -->
            
                <button class="no-styling button-click-animation
                @if($comment->dislikes()->where('user_id', Auth::id())->exists()) text-danger @endif"
                id="dislikeButton" data-comment-id="{{ $comment->id }}"
                data-csrf-token="{{ csrf_token() }}"
                data-disliked="@if($comment->dislikes()->where('user_id', Auth::id())->exists()) true @else false @endif">

                <i class="fa-solid fa-thumbs-down me-1"></i></button>
                <span id="dislikeCount">@if($comment->dislike_count <= 0) 0 @else {{ $comment->dislike_count }} @endif</span>
            </div>
            @endauth
        </div>
    </div>
</div>

@vite('resources/js/commentCard.js')