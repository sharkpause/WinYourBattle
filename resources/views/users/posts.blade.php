<x-layout>

    <div class="mt-7"></div>

    <div class="container mt-3">
        <h1>{{ $username }}'s Latest Posts</h1>
        <p class="text-muted">Posts posted: {{ $posts->total() }}</p>

        @foreach ($posts as $post)
            <x-postCard :post="$post"></x-postCard>
        @endforeach
    </div>

    <x-paginator></x-paginator>

</x-layout>