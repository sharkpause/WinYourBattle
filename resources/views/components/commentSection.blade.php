@props(['comments', '$post_id'])

@foreach($comments as $comment)
    @include('components.commentCard', ['comment' => $comment])
@endforeach

<div id="commentPaginator-{{ $post_id }}"></div>