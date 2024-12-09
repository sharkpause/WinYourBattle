<x-layout>
  <div class="container">
    @if(session('success'))
      <x-alert msg="{{ session('success') }}" classes="text-white success-alert"></x-alert>
    @endif

    <div class="d-flex flex-column min-vh-80 justify-content-center align-items-center"><div class="card col-8 border-radius-2-rem shadow">
    <div class="card-body m-3">
      <h4>Please check your email for our email verification link we've sent you.</h4>
      
      <a class="text-muted underline pointer-on-hover" id="wrong-email-link">Wrong email?</a>
      <div>
        <form action="{{ route('verification.change-email') }}" method="POST">
          @csrf
          @method('PUT')

          <input type="text" class="form-" name="email">
          @error('email')
            <p class="text-danger error-font-size">{{ $message }}</p>
          @enderror
          <button type="submit">Change email</button>
        </form>
      </div>

      <p class="mt-5 text-muted mb-1">Didn't get the email?</p>
      <form action="{{ route('verification.send') }}" method="POST" class="form-with-spinner">
          @csrf

          <button type="submit" class="width-100 btn btn-primary button-click-animation submit-button">
            <x-spinner></x-spinner>
            <span class="text-center w-100">Send the email again</span>
          </button>
      </form>
    </div>
  </div></div></div>
</x-layout>

@vite('resources/js/verifyEmail.js');