@props(['profilePic', 'title', 'created_at', 'username', 'wordLimit'])

<div class="d-flex mb-3 bg-light p-3 border-radius-1-rem">
  <div class="flex-shrink-0">
      <img src="{{ $profilePic }}" class="rounded-circle" alt="Profile Picture">
  </div>
  <div class="flex-grow-1 ms-3">
      <span class="h5">{{ $title }} </span>
      <div class="mb-3">
          <span class="text-muted me-3">{{ $username }}</span>
          <span class="text-muted">{{ $created_at }}</span>
      </div>
      <p>{{ $wordLimit }}</p>
  </div>
</div>