@props(['comments', '$post_id'])

@foreach($comments as $comment)
    @include('components.commentCard', ['comment' => $comment])
@endforeach

<div id="comment-paginator-{{ $post_id }}"></div>