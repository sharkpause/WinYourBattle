<x-layout>
  <div class="container">
    @if(session('success'))
      <x-alert msg="{{ session('success') }}" classes="text-white success-alert"></x-alert>
    @endif

    <div class="d-flex flex-column min-vh-80 justify-content-center align-items-center"><div class="card col-8 border-radius-2-rem shadow">
    <div class="card-body m-3">
      <h4>Please check your email inbox for the email verification link we've sent you.</h4>
      
      <a class="text-muted underline pointer-on-hover" id="wrong-email-link">Wrong email? Click me to change it!</a>
      <div id="change-email-container" class="d-none">
        <form action="{{ route('verification.change-email') }}" method="POST" class="form-with-spinner">
          @csrf
          @method('PUT')

          <div class="d-flex">
            <input type="text" class="form-control" name="email">
            <button type="submit" class="btn btn-primary button-click-animation submit-button">
              <x-spinner></x-spinner>
              <span class="text-nowrap">Change Email</span>
            </button>
          @error('email')
            <p class="text-danger error-font-size">{{ $message }}</p>
          @enderror
          </div>
        </form>
      </div>

      <div id="send-email-again-container">
        <p class="mt-5 text-muted mb-1">Didn't get the email?</p>
        <form action="{{ route('verification.send') }}" method="POST" class="form-with-spinner">
            @csrf

            <button type="submit" class="width-100 btn btn-primary button-click-animation submit-button">
              <x-spinner></x-spinner>
              <span class="text-center w-100">Send the email again</span>
            </button>
        </form>
      </div>
    </div>
  </div></div></div>
</x-layout>

@vite('resources/js/verifyEmail.js');