@props(['post', 'full' => false])

<div class="mb-4 bg-light p-3 border-radius-1-rem shadow">
  <div class="d-flex">
    <div class="flex-shrink-0">
        <img src="{{ asset('storage/' .  $post->user->image) }}" class="hw-40px rounded-circle">
    </div>
    <div class="flex-grow-1 ms-3">
        <span class="h5 text-break mw-97">{{ $post->title }} </span>
        @auth
        @if(auth()->user()->id === $post->user->id)
          <div class="float-end">
              <i type="button" id="navbar-dropdown" data-bs-toggle="dropdown" aria-expanded="false" class="fa-solid fa-ellipsis-vertical expand-clickable-area-1-rem"></i>
              
              <div class="dropdown-menu dropdown-menu-end post-menu-dropdown-margin shadow border-radius-1-rem" aria-labelledby="navbar-dropdown">
                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="delete-post-form">
                    @csrf
                    @method('DELETE')
                    <button class="dropdown-item red-on-hover delete-post-button" type="submit">Delete Post</button>
                </form>
                <a href="{{ route('posts.edit', $post) }}" class="dropdown-item no-underline">Edit Post</a>
              </div>
          </div>
        @endif
        @endauth
        <div class="mb-3">
            <a class="no-underline me-3" href="{{ route('posts.user', $post->user) }}">{{ $post->user->username }}</a>
            <span class="text-muted">{{ $post->created_at->diffForHumans() }}</span>
        </div>
      
        @if ($post->image !== null)
          <div class="mb-3">
            <img class="mw-97" src="{{ asset('storage/' . $post->image) }}">
          </div>
        @endif
        
        @if ($full || Str::length($post->body) <= 3000)
          <span class="keep-whitespace text-wrap mw-97">{{ $post->body }}</span>  
        @else
          <span class="keep-whitespace text-wrap mw-97">{{ Str::limit($post->body, 3000, $end='...') }}</span>
          <a href="{{ route('posts.show', $post) }}" class="no-underline">Read more</a>
        @endif
        
        <div class="mt-3">
          @auth
          <button class="no-styling button-click-animation post-like-button
            @if($post->likes()->where('user_id', Auth::id())->exists()) text-primary @endif"
            data-post-id="{{ $post->id }}"
            data-csrf-token="{{ csrf_token() }}"
            data-liked="@if($post->likes()->where('user_id', Auth::id())->exists()) true @else false @endif"
            data-like-url="{{ route('posts.like', $post->id) }}"
            data-unlike-url="{{ route('posts.unlike', $post->id) }}">
            
            <i class="fa-solid fa-thumbs-up me-1"></i>
          </button>
          <span id="post-like-count-{{ $post->id }}" class="me-5">{{ $post->like_count }}</span>
        
          <button class="no-styling button-click-animation post-dislike-button
          @if($post->dislikes()->where('user_id', Auth::id())->exists()) text-danger @endif"
          data-post-id="{{ $post->id }}"
          data-csrf-token="{{ csrf_token() }}"
          data-disliked="@if($post->dislikes()->where('user_id', Auth::id())->exists()) true @else false @endif"
          data-dislike-url="{{ route('posts.dislike', $post->id) }}"
          data-undislike-url="{{ route('posts.undislike', $post->id) }}">
          
          <i class="fa-solid fa-thumbs-down me-1"></i></button>
          <span id="post-dislike-count-{{ $post->id }}">{{ $post->dislike_count }}</span>
          @endauth
        
          <button class="float-end no-styling me-5 button-click-animation comment-section-button"
                data-opened="false" data-post-id="{{ $post->id }}"
                data-opened-first-time="false"
                data-url="{{ route('comments.show', $post->id) }}">
            <i class="fa fa-comment" id="comment-section-button-icon"></i>
          </button>
        </div>
      </div>
  </div>

    <div class="mt-4 d-none" id="comment-section-{{ $post->id }}">
      @auth
      <form class="mb-4_5 comment-form" method="POST"
            data-csrf-token="{{ csrf_token() }}"
            data-post-id="{{ $post->id }}"
            data-url="{{ route('comments.store', $post->id) }}">
        <textarea class="form-control mb-1 keep-whitespace @error('body') error-border @enderror"
                name="body"
                placeholder="What do you think about this post?"
                id="comment-textarea"
                autocomplete="off"></textarea>
        <button type="submit" class="float-end btn btn-primary">Post comment</button>
      </form>
      @endauth

      @if($post->comments()->count() > 0)
      <div class="mt-5"></div>
      @endif
      <div id="comment-cards-{{ $post->id }}"></div>
  </div>
</div>

@vite('resources/js/postCard.js')