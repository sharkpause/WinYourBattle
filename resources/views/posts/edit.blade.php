<x-layout>

    <x-navbarSpace></x-navbarSpace>

    <div class="container"><div class="card border-radius-2-rem"><div class="card-body m-3">
        <h1 class="mb-5">Got a Change of Mind?</h1>

        <form method="POST" action="{{ route('posts.update', $post) }}">
            @csrf
            @method('PATCH')
            
            <input type="text" class="form-control mb-1 @error('title') error-border @enderror" name="title" placeholder="New title goes here...">
            @error('title')
                <p class="ms-1 text-danger">{{ $message }}</p>
            @enderror

            <textarea class="form-control mb-1 @error('body') error-border @enderror" name="body" placeholder="And whatever you meant to say goes here!"></textarea>
            @error('body')
                <p class="ms-1 text-danger">{{ $message }}</p>
            @enderror

            <button class="btn btn-primary float-end">Edit Post</button>
        </form>
    </div></div></div>

</x-layout>