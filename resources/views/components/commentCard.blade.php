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
                <button class="no-styling button-click-animation commentLikeButton
                  @if($comment->likes()->where('user_id', Auth::id())->exists()) text-primary @endif"
                  data-comment-id="{{ $comment->id }}"
                  data-csrf-token="{{ csrf_token() }}"
                  data-liked="@if($comment->likes()->where('user_id', Auth::id())->exists()) true @else false @endif"
                  data-like-url="{{ route('comments.like', [$comment->post->id, $comment->id]) }}"
                  data-unlike-url="{{ route('comments.unlike', [$comment->post->id, $comment->id]) }}">

                  <i class="fa-solid fa-thumbs-up me-1"></i>
                </button>
                <span id="commentLikeCount-{{ $comment->id }}" class="me-5">{{ $comment->like_count }}</span>
                <!-- The 0 is there on both like and dislike count because if
                    $comment->like_count of dislike_count is 0, it won't make anything appear on the front end -->
            
                <button class="no-styling button-click-animation commentDislikeButton
                @if($comment->dislikes()->where('user_id', Auth::id())->exists()) text-danger @endif"
                data-comment-id="{{ $comment->id }}"
                data-csrf-token="{{ csrf_token() }}"
                data-disliked="@if($comment->dislikes()->where('user_id', Auth::id())->exists()) true @else false @endif"
                data-dislike-url="{{ route('comments.dislike', [$comment->post->id, $comment->id]) }}"
                data-undislike-url="{{ route('comments.undislike', [$comment->post->id, $comment->id]) }}">

                <i class="fa-solid fa-thumbs-down me-1"></i></button>
                <span id="commentDislikeCount-{{ $comment->id }}">@if($comment->dislike_count <= 0) 0 @else {{ $comment->dislike_count }} @endif</span>
            </div>
            @endauth
        </div>
    </div>
</div>

@vite('resources/js/commentCard.js')