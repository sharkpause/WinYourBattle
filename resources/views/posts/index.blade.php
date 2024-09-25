<x-layout>
    <h1>Home</h1>
    @auth
        <p>You are currently logged in</p>
    @endauth

    @guest
        <p>You are currently not logged in</p>   
    @endguest
</x-layout>