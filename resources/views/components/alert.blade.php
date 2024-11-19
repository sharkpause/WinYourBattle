@props(['msg' => 'Unknown alert', 'classes' => 'alert-primary'])

<div class="container">
    <div class="mx-auto alert fixed-top sharp-corners text-center fs-5 alert-elem {{ $classes }}" role="alert">
        {{ $msg }}
    </div>
</div>