<x-layout>

    <x-navbarSpace></x-navbarSpace>

    <div class="container"><div class="card border-radius-2-rem shadow"><div class="card-body m-3">
        <h1 class="mb-5">Share Your Thoughts!</h1>

        <div id="image-preview-container" class="mb-3"></div>

        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="d-inline form-group">
                <div class="d-flex align-items-center gap-1">
                    <input type="file" name="image" class="hidden" id="post_image">
                    <label for="post_image" class="pointer-on-hover post-image-upload-button button-click-animation">
                        <i class="fas fa-image"></i>
                    </label>
                    @error('image')
                        <p class="ms-1 text-danger">{{ $message }}</p>
                    @enderror

                    <input type="text" class="form-control mb-1 mt-1 @error('title') error-border @enderror" name="title" placeholder="Title goes here..." autocomplete="off">
                    @error('title')
                        <p class="ms-1 text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <textarea class="form-control mb-1 keep-whitespace @error('body') error-border @enderror" name="body" placeholder="And whatever you are thinking goes here!"></textarea>
            @error('body')
                <p class="ms-1 text-danger">{{ $message }}</p>
            @enderror

            <button class="btn btn-primary float-end button-click-animation">Create post</button>
        </form>
    </div></div></div>

    @vite('resources/js/create.js')

</x-layout>