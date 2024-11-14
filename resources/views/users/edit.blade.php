<x-layout>
    
    <x-navbarSpace></x-navbarSpace>

    <div class="card container border-radius-2-rem shadow">
        <div class="card-body m-3">
            <form method="POST" action="{{ route('users.update', Auth::user()->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="d-flex align-items-start mb-4">
                    <img src="{{ asset('storage/' . Auth::user()->image) }}" class="rounded-circle hw-200px">

                    <div class="mw-30 ms-5 my-auto">
                        <label class="h4" for="image">New profile picture?</label>
                        <input type="file" name="image" class="form-control">
                        @error('image')
                            <p class="ms-1 text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label class="h3" for="bio">New bio?</label>
                    <input name="bio" type="text" class="form-control" placeholder="Your bio goes here!" value="{{ Auth::user()->bio }}">
                    @error('bio')
                        <p class="ms-1 text-danger">{{ $message }}</p>
                    @enderror
                </div>
    
                <button class="btn btn-primary float-end mt-2">Edit Account</button>
            </form>
        </div>
    </div>

</x-layout>

<!--
<div class="d-flex align-items-start justify-content-between">
                <div class="d-flex align-items-start">
                    <img src="{{ asset('storage/' . Auth::user()->image) }}" class="rounded-circle hw-200px">
                    
                    <div class="ms-5">
                        <h1>{{ Auth::user()->username }}</h1>
                        <p class="text-muted">{{ Auth::user()->bio }}</p>
                    </div>
                </div>
                    
                <div>
                    <i type="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false" class="fa-solid fa-ellipsis-vertical expand-clickable-area-1-rem"></i>
                    <div class="dropdown-menu dropdown-menu-end post-menu-dropdown-margin shadow border-radius-1-rem" aria-labelledby="navbarDropdown">
                        <a href="{{ route('users.edit', Auth::user()->id) }}" class="dropdown-item no-underline">Edit Account Info</a>
                        <form action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="dropdown-item red-on-hover" type="submit">Delete Account</button>
                        </form>
                    </div>
                </div>
            </div>
-->