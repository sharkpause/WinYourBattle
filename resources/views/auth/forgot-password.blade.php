<x-layout>    
  <div class="container">
    @if(session('status'))
        <x-alert msg="{{ session('status') }}" classes="alert-elem success-alert text-white"></x-alert>
    @endif

    <div class="d-flex flex-column min-vh-80 justify-content-center align-items-center"><div class="card col-4 border-radius-2-rem shadow">
    <div class="card-body m-3">
    
    <h3>Password Reset</h3>
    <p class="text-muted mb-5">Please enter your email address and we'll send an email to direct you to a password reset form</p>
    <form method="POST" action="{{ route('password.request') }}" class="form-with-spinner">
        @csrf

        <div class="form-group mb-4">
          <input type="text" name="email" class="form-control ps-0 underline-form @error('email') error-underline @enderror" placeholder="Email">
          @error('email')
            <p class="text-danger">{{ $message }}</p>
          @enderror
        </div>

        <button type="submit" class="width-100 btn btn-primary button-click-animation submit-button mt-1">
          <x-spinner></x-spinner>
          <span class="text-center w-100">Send the email</span>
        </button>
    </form>
    </div>
  </div></div></div>

  @vite('resources/js/forgotPassword.js')
</x-layout>