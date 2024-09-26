<x-layout>
    <h1 class="mb-5">Register Form</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group mb-4">
          <label for="username">Username</label>
          <input type="text" name="username" class="form-control @error('username') border border-danger @enderror"placeholder="Username">
          @error('username')
              <p class="text-danger">{{ $message }}</p>
          @enderror
        </div>

        <div class="form-group mb-4">
          <label for="email">Email</label>
          <input type="text" name="email" class="form-control @error('email') border border-danger @enderror" placeholder="exampleemail@example.com">
          @error('email')
              <p class="text-danger">{{ $message }}</p>
          @enderror
        </div>

        <div class="form-group mb-4">
          <label for="password">Password</label>
          <input type="password" name="password" class="form-control @error('password') border border-danger @enderror" placeholder="Password">
          @error('password')
              <p class="text-danger">{{ $message }}</p>
          @enderror
        </div>

        <div class="form-group mb-4">
          <label for="password_confirmation">Confirm Password</label>
          <input type="password" name="password_confirmation" class="form-control @error('password') border border-danger @enderror" placeholder="Confirm Password">
          @error('password') @enderror
        </div>

        <button type="submit" class="btn btn-primary">Log in</button>

      </form>

</x-layout>