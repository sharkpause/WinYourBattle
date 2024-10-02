@props(['post'])

<div class="d-flex mb-3 bg-light p-3 border-radius-1-rem">
  <div class="flex-shrink-0">
      <img src="https://picsum.photos/30" class="rounded-circle" alt="Profile Picture">
  </div>
  <div class="flex-grow-1 ms-3">
      <span class="h5">{{ $post->title }} </span>
      <div class="mb-3">
          <a class="no-underline me-3" href="{{ route('posts.user', $post->user) }}">{{ $post->user->username }}</a>
          <span class="text-muted">{{ $post->created_at }}</span>
      </div>
      <p>{{ Str::words($post->body, 30) }}</p>
  </div>
</div>