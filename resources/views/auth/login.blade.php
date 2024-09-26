<x-layout>
    <h1 class="mb-5">Log in Form</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group mb-4">
          <label for="email">Email</label>
          <input type="text" name="email" class="form-control" placeholder="exampleemail@example.com">
        </div>

        <div class="form-group mb-4">
          <label for="password">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Password">
        </div>

        <div class="form-group mb-4">
            <input class="form-check-input" type="checkbox" name="remember">
            <label class="form-check-label" for="remember">
              Remember me
            </label>
        </div>

        @error('login_failed')
            <p class="text-danger">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn btn-primary">Log in</button>

      </form>

</x-layout>