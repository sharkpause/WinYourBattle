<x-layout>
    
    <x-navbarSpace></x-navbarSpace>

    <div class="card container border-radius-2-rem shadow">
        <div class="card-body m-3">
            <form method="POST" action="{{ route('users.update', Auth::id()) }}" enctype="multipart/form-data" id="edit-account-form">
                @csrf
                @method('PATCH')

                <div class="d-flex align-items-start mb-4">
                    <input type="file" name="image" class="hidden" id="profile-image-input">
                    <label for="profile-image-input" class="image-container position-relative pointer-on-hover d-flex align-items-center justify-content-center">
                        <img src="{{ asset('storage' . Auth::user()->image) }}" class="rounded-circle hw-200px profile-image" id="profile-image-preview">
                        <i class="fas fa-edit icon icon-on-top"></i>
                    </label>
                    @error('image')
                        <p class="ms-1 text-danger">{{ $message }}</p>
                    @enderror
                    
                    <div class="ms-5 my-auto flex-grow-1">
                        <input  name="bio"
                                type="text"
                                class="form-control w-100"
                                placeholder="Your bio goes here!"
                                value="{{ Auth::user()->bio }}"
                                style="max-width: 100%;"
                                autocomplete="off">
                        
                                @error('bio')
                            <p class="ms-1 text-danger">{{ $message }}</p>
                        @enderror
                        <div class="mt-2">
                            @php
                                $visibilityState;

                                if(Auth::user()->public == 1)
                                    $visibilityState = 1;
                                else
                                    $visibilityState = 0;
                            @endphp
                            <span class="text-muted" id="account-visibility-information-text">
                                @if($visibilityState === 1) Your account is visible to everybody 😎
                                @else Your account is only visible to followers 🤫 @endif
                            </span>

                            <label class="ms-2 me-2 toggle-switch">
                                <input type="checkbox" id="visibilityToggle"
                                       data-visibility="@if($visibilityState === 1) 1
                                       @else 0 @endif">
                                <span class="slider"></span>
                            </label>

                            <span class="text-muted" id="account-visibility-state">
                                @if($visibilityState === 1) Public
                                @else Private @endif
                            </span>
                        </div>
                    </div>
                </div>
    
                <button class="btn btn-primary float-end mt-2" id="edit-account-button">Edit Account</button>
            </form>
        </div>
    </div>

    @vite('resources/js/userEdit.js')

</x-layout>