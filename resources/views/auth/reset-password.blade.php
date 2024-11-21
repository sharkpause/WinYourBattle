<x-layout>
    <div class="container">
      @if(session('status'))
        <x-alert msg="{{ session('status') }}" classes="alert-elem success-alert text-white"></x-alert>
      @endif
      @if(session('email'))
        <x-alert msg="{{ session('email') }}" classes="alert-danger alert-elem" ></x-alert>
      @endif
    
      <div class="d-flex flex-column min-vh-80 justify-content-center align-items-center"><div class="card col-4 border-radius-2-rem shadow">
          <div class="card-body m-3">
            <h3 class="mb-5">Reset password</h3>
            <form method="POST" action="{{ route('password.update') }}" id="form">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ request()->query('email') }}">
        
                <div class="form-group mb-4">
                  <input type="password" name="password" class="form-control ps-0 underline-form @error('password') error-underline @enderror" placeholder="Password">
                  @error('password')
                      <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
  
                <div class="form-group mb-5">
                  <input type="password" name="password_confirmation" class="form-control ps-0 underline-form @error('password') error-underline @enderror" placeholder="Confirm Password">
                  @error('password') @enderror
                </div>
        
                <button type="submit" class="width-100 btn btn-primary button-click-animation" id="submitButton">
                  <span id="spinnerElem" class="float-start"></span>
                  <span class="text-center">Reset my password</span>
                </button>
        
              </form>
          </div>
    </div></div></div>
  
    @vite(['resources/js/alert.js', 'resources/js/submitButtonUnclickable.js'])
  
  </x-layout>