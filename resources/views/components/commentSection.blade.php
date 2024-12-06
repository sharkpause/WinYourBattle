@props(['comments'])

@foreach($comments as $comment)
    @include('components.commentCard', ['comment' => $comment])
@endforeach

{{ $comments->links() }}