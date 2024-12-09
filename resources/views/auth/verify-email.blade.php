<x-layout>
  <div class="container"><div class="d-flex flex-column min-vh-80 justify-content-center align-items-center"><div class="card col-8 border-radius-2-rem shadow">
    <div class="card-body m-3">
      <h4>Please check your email for our email verification link we've sent you.</h4>
      <p class="mt-5 text-muted">Didn't get the email?</p>
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