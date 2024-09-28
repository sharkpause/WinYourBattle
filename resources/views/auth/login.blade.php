<x-layout>
  <div class="container"><div class="d-flex flex-column min-vh-80 justify-content-center align-items-center"><div class="card col-4 border-radius-2-rem">
    <div class="card-body m-3">
    <h3 class="mb-5">Log in</h3>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group mb-4">
          <input type="text" name="email" class="form-control ps-0 @error('email') error-underline @enderror" placeholder="Email">
          @error('email')
            <p class="text-danger">{{ $message }}</p>
          @enderror
        </div>

        <div class="form-group mb-4">
          <input type="password" name="password" class="form-control ps-0 @error('password') error-underline @enderror" placeholder="Password">
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

        @error('login_failed')
            <p class="text-danger error-font-size">{{ $message }}</p>
        @enderror

        <button type="submit" class="width-100 btn btn-primary">Log in</button>
    </form>
    </div>
  </div></div></div>
</x-layout>

{{--
--}}