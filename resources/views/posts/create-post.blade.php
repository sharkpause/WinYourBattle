<x-layout>

    <div class="mt-7"></div>

    <div class="container">
        <h1 class="mb-5">Create a post</h1>

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
    </div>

</x-layout>