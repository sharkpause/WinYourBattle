@props(['post', 'full' => false])

<div class="d-flex mb-4 bg-light p-3 border-radius-1-rem shadow">
  <div class="flex-shrink-0">
      <img src="{{ asset('storage/' .  Auth::user()->image) }}" class="hw-40px rounded-circle">
  </div>
  <div class="flex-grow-1 ms-3">
      <span class="h5 text-break mw-97">{{ $post->title }} </span>
      @auth
      @if(auth()->user()->id === $post->user->id)
        <div class="float-end">
            <i type="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false" class="fa-solid fa-ellipsis-vertical expand-clickable-area-1-rem"></i>
            
            <div class="dropdown-menu dropdown-menu-end post-menu-dropdown-margin shadow border-radius-1-rem" aria-labelledby="navbarDropdown">
              <form action="{{ route('posts.destroy', $post) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button class="dropdown-item" type="submit">Delete Post</button>
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
        <span class="post-body text-wrap mw-97">{{ $post->body }}</span>  
      @else
        <span class="post-body text-wrap mw-97">{{ Str::limit($post->body, 3000, $end='...') }}</span>
        <a href="{{ route('posts.show', $post) }}" class="no-underline">Read more</a>
      @endif
    </div>
</div>