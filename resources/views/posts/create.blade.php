<x-layout>

    <x-navbarSpace></x-navbarSpace>

    <div class="container"><div class="card border-radius-2-rem shadow"><div class="card-body m-3">
        <h1 class="mb-5">Share Your Thoughts!</h1>

        <form method="POST" action="{{ route('posts.store') }}">
            @csrf
            
            <input type="text" class="form-control mb-1 @error('title') error-border @enderror" name="title" placeholder="Title goes here...">
            @error('title')
                <p class="ms-1 text-danger">{{ $message }}</p>
            @enderror

            <textarea class="form-control mb-1 @error('body') error-border @enderror" name="body" placeholder="And whatever you are thinking goes here!"></textarea>
            @error('body')
                <p class="ms-1 text-danger">{{ $message }}</p>
            @enderror

            <button class="btn btn-primary float-end">Create post</button>
        </form>
    </div></div></div>

</x-layout>