<x-layout>

    <x-navbarSpace></x-navbarSpace>

    <div class="container">
        <h1>Please check your email for our email verification link we've sent you.</h1>
        <p class="mt-5">Didn't get the email?</p>
        <form action="{{ route('verification.send') }}" method="POST">
            @csrf

            <button class="btn btn-primary">Send again</button>
        </form>
    </div>
</x-layout>