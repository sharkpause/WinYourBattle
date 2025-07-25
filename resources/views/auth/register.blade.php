<x-layout>
      
  <div class="container"><div class="d-flex flex-column min-vh-80 justify-content-center align-items-center"><div class="card col-4 border-radius-2-rem shadow">
        <div class="card-body m-3">
          <h3 class="mb-5">Register</h3>
          <form method="POST" action="{{ route('register') }}" class="form-with-spinner">
              @csrf
      
              <div class="form-group mb-4">
                <input type="text" name="username"
                       class="form-control ps-0 underline-form @error('username') error-underline @enderror"
                       placeholder="Username">
                @error('username')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
      
              <div class="form-group mb-4">
                <input type="text" name="email" 
                       class="form-control ps-0 underline-form @error('email') error-underline @enderrorr @enderror"
                       placeholder="exampleemail@example.com">
                @error('email')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
      
              <div class="form-group mb-4 position-relative">
                <input type="password" name="password"
                       class="form-control ps-0 underline-form @error('password') error-underline @enderror"
                       placeholder="Password" id="password-input">
                <button type="button" class="no-styling position-absolute top-50 end-0 translate-middle-y me-2">
                <i class="fa-solid fa-eye" id="show-password-button" data-state="0"></i>
              </button>
                @error('password')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>

              <div class="form-group mb-5">
                <input type="password" name="password_confirmation"
                       class="form-control ps-0 underline-form @error('password') error-underline @enderror"
                       placeholder="Confirm Password">
                @error('password') @enderror
              </div>

              <a href="{{ route('login') }}" class="text-end text-muted fs-6 text-sm-end">Already have an account? Log in</a>
      
              <button type="submit" class="width-100 btn btn-primary button-click-animation submit-button mt-1">
                <x-spinner></x-spinner>
                <span class="text-center w-100">Register</span>
              </button>
            </form>

            <div class="row justify-content-center mt-2"><div class="col-12 d-flex gap-2">
              <button class="no-border bg-white col flex-grow-1 text-center shadow border-radius-0_375-rem button-click-animation"
                      onclick="window.location.href='{{ route('google.login') }}'" id="google-oauth-button">
                <x-spinner></x-spinner>
                <img src="{{ asset('storage/assets/Google_Logo.png') }}" height="40px">
              </button>
              <button class="no-border bg-white col flex-grow-1 text-center shadow border-radius-0_375-rem button-click-animation"
                      onclick="window.location.href='{{ route('github.login') }}'" id="github-oauth-button">
                <x-spinner></x-spinner>
                <img src="{{ asset('storage/assets/Github_Logo.png') }}" height="27px">
              </button>
            </div></div>
        </div>
  </div></div></div>

</x-layout>

@vite('resources/js/register.js')