@props(['comment', 'full' => false])

<div class="mt-4 p-3 border-radius-1-rem shadow-sm" id="commentCard-{{ $comment->id }}">
    <div class="d-flex">
        <div class="flex-shrink-0">
            <img src="{{ asset('storage/' .  $comment->user->image) }}" class="hw-40px rounded-circle">
        </div>
        <div class="flex-grow-1 ms-3">
            @auth
            @if(Auth::user()->id === $comment->user->id)
              <div class="float-end">
                  <i type="button" id="navbarDropdown" data-bs-toggle="dropdown"
                     aria-expanded="false" class="fa-solid fa-ellipsis-vertical expand-clickable-area-1-rem"></i>

                  <div class="dropdown-menu dropdown-menu-end post-menu-dropdown-margin shadow border-radius-1-rem" aria-labelledby="navbarDropdown">
                    <a
                        data-url="{{ route('comments.destroy', [$comment->post->id, $comment->id]) }}"
                        class="dropdown-item red-on-hover delete-comment-button pointer-on-hover"
                        data-comment-id="{{ $comment->id }}"
                        data-csrf-token="{{ csrf_token() }}">Delete Comment</a>
                    <a
                        data-url="{{ route('comments.update', [$comment->post->id, $comment->id]) }}"
                        data-comment-id="{{ $comment->id }}"
                        class="dropdown-item no-underline edit-comment-button pointer-on-hover"
                        data-csrf-token="{{ csrf_token() }}">Edit Comment</a>
                  </div>
              </div>
            @endif
            @endauth
            <div>
                <a class="no-underline me-3" href="{{ route('posts.user', $comment->post->user) }}">{{ $comment->user->username }}</a>
                <span class="text-muted">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <div id="comment-body-{{ $comment->id }}">
                {{ $comment->body }}
            </div>
            
            @auth
            <div class="mt-2">
                <button class="no-styling button-click-animation comment-like-button
                  @if($comment->likes()->where('user_id', Auth::id())->exists()) text-primary @endif"
                  data-comment-id="{{ $comment->id }}"
                  data-csrf-token="{{ csrf_token() }}"
                  data-liked="@if($comment->likes()->where('user_id', Auth::id())->exists()) true @else false @endif"
                  data-like-url="{{ route('comments.like', [$comment->post->id, $comment->id]) }}"
                  data-unlike-url="{{ route('comments.unlike', [$comment->post->id, $comment->id]) }}">

                  <i class="fa-solid fa-thumbs-up me-1"></i>
                </button>
                <span id="comment-like-count-{{ $comment->id }}" class="me-5">@if($comment->like_count <= 0) 0 @else {{ $comment->like_count }} @endif</span>
                <!-- The 0 is there on both like and dislike count because if
                    $comment->like_count of dislike_count is 0, it won't make anything appear on the front end -->
            
                <button class="no-styling button-click-animation comment-dislike-button
                @if($comment->dislikes()->where('user_id', Auth::id())->exists()) text-danger @endif"
                data-comment-id="{{ $comment->id }}"
                data-csrf-token="{{ csrf_token() }}"
                data-disliked="@if($comment->dislikes()->where('user_id', Auth::id())->exists()) true @else false @endif"
                data-dislike-url="{{ route('comments.dislike', [$comment->post->id, $comment->id]) }}"
                data-undislike-url="{{ route('comments.undislike', [$comment->post->id, $comment->id]) }}">

                <i class="fa-solid fa-thumbs-down me-1"></i></button>
                <span id="comment-dislike-count-{{ $comment->id }}">@if($comment->dislike_count <= 0) 0 @else {{ $comment->dislike_count }} @endif</span>
            </div>
            @endauth
        </div>
    </div>
</div>

@vite('resources/js/commentCard.js')