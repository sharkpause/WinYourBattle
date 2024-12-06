@props(['comments'])

@foreach($comments as $comment)
    @include('components.commentCard', ['comment' => $comment])
@endforeach

<x-paginator :items="$comments"></x-paginator>