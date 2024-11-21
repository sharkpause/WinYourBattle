<x-layout>
    <div class="container"><div class="d-flex flex-column min-vh-80 justify-content-center align-items-center"><div class="card col-8 border-radius-2-rem shadow">
      <div class="card-body m-3">
        <h4>Please check your email for our email verification link we've sent you.</h4>
        <p class="mt-5 text-muted">Didn't get the email?</p>
        <form action="{{ route('verification.send') }}" method="POST">
            @csrf

            <button class="btn btn-primary form-control button-click-animation">Send again</button>
        </form>
      </div>
    </div></div></div>
  </x-layout>