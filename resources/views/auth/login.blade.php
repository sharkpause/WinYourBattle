<x-layout>
  <div class="container">
    @if(session('success'))
        <x-alert msg="{{ session('success') }}" classes="alert-elem success-alert container text-white"></x-alert>
    @endif
    
    <div class="d-flex flex-column min-vh-80 justify-content-center align-items-center"><div class="card col-4 border-radius-2-rem shadow">
    <div class="card-body m-3">
    <h3 class="mb-5">Log in</h3>
    <form method="POST" action="{{ route('login') }}" class="form-with-spinner">
        @csrf

        <div class="form-group mb-4">
          <input type="text" name="email" class="form-control ps-0 underline-form @error('email') error-underline @enderror" placeholder="Email">
          @error('email')
            <p class="text-danger">{{ $message }}</p>
          @enderror
        </div>

        <div class="form-group position-relative">
          <input id="password-input" type="password" name="password" class="form-control ps-0 underline-form @error('password') error-underline @enderror" placeholder="Password">
          <button type="button" class="no-styling position-absolute top-50 end-0 translate-middle-y me-2">
            <i class="fa-solid fa-eye" id="show-password-button" data-state="0"></i>
          </button>
        </div>
        @error('password')
          <p class="text-danger">{{ $message }}</p>
        @enderror

        <div class="form-group mt-4 mb-4">
            <input class="form-check-input" type="checkbox" name="remember">
            <label class="form-check-label" for="remember">
              Remember me
            </label>
        </div>

        <div class="mt-2 mb-2">
          <a class="text-muted" href="{{ route('password.request') }}">I forgot my password</a>
          <br class="mb-2">
          <a class="text-muted" href="{{ route('register') }}">I don't have an account</a>
        </div>

        @error('login_failed')
            <p class="text-danger error-font-size">{{ $message }}</p>
        @enderror

        <button type="submit" class="width-100 btn btn-primary button-click-animation submit-button">
          <x-spinner></x-spinner>
          <span class="text-center w-100">Log in</span>
        </button>
    </form>

    <div class="row justify-content-center mt-2"><div class="col-12 d-flex gap-2">
      <button class="no-border bg-white col flex-grow-1 text-center shadow border-radius-0_375-rem button-click-animation"
              onclick="window.location.href='{{ route('google.login') }}'" id="google-oauth-button">
        <div class="d-flex align-items-center justify-content-center position-relative">
          <x-spinner class="position-absolute"></x-spinner>
          <img src="{{ asset('storage/assets/Google_Logo.png') }}" height="40px">
        </div>
      </button>
      <button class="no-border bg-white col flex-grow-1 text-center shadow border-radius-0_375-rem button-click-animation"
              onclick="window.location.href='{{ route('github.login') }}'" id="github-oauth-button">
        <div class="d-flex align-items-center justify-content-center position-relative">
          <x-spinner class="position-absolute"></x-spinner>
          <img src="{{ asset('storage/assets/Github_Logo.png') }}" height="25px">
        </div>
      </button>
    </div></div>

  </div></div></div>

</x-layout>

@vite(['resources/js/login.js'])