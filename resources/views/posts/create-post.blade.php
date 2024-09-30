<x-layout>

    <div class="mt-7"></div>

    <div class="container">
        <h1 class="mb-5">Create a post</h1>

        <form method="POST" action="{{ route('posts.store') }}">
            @csrf
            
            <input type="text" class="form-control mb-1" name="title" placeholder="Title goes here...">
            <textarea class="form-control mb-1" name="body" placeholder="And whatever you are thinking goes here!"></textarea>

            <button class="btn btn-primary float-end">Create post</button>
        </form>
    </div>

</x-layout>