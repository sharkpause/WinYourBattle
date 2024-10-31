<x-layout>
  <div class="container">
    @if(session('status'))
        <x-alert msg="{{ session('status') }}" classes="alert-elem success-alert container text-white"></x-alert>
    @endif
    
    <div class="d-flex flex-column min-vh-80 justify-content-center align-items-center"><div class="card col-4 border-radius-2-rem shadow">
    <div class="card-body m-3">
    <h3 class="mb-5">Log in</h3>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group mb-4">
          <input type="text" name="email" class="form-control ps-0 underline-form @error('email') error-underline @enderror" placeholder="Email">
          @error('email')
            <p class="text-danger">{{ $message }}</p>
          @enderror
        </div>

        <div class="form-group mb-4">
          <input type="password" name="password" class="form-control ps-0 underline-form @error('password') error-underline @enderror" placeholder="Password">
          @error('password')
            <p class="text-danger">{{ $message }}</p>
          @enderror
        </div>

        <div class="form-group mb-4">
            <input class="form-check-input" type="checkbox" name="remember">
            <label class="form-check-label" for="remember">
              Remember me
            </label>
        </div>

        <div class="mt-2 mb-2">
          <a class="text-muted" href="{{ route('password.request') }}">I forgot my password</a>
        </div>

        @error('login_failed')
            <p class="text-danger error-font-size">{{ $message }}</p>
        @enderror

        <button type="submit" class="width-100 btn btn-primary">Log in</button>
    </form>
    </div>
  </div></div></div>

  @vite('resources/js/alert.js')
</x-layout>