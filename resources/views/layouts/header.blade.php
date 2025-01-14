<nav class="bg-blue-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <!-- <a href="{{ url('/') }}" class="text-white text-lg font-semibold">Weather App</a> -->
        <div>
            @guest
                <a href="{{ route('login') }}" class="text-white hover:text-gray-200">Login</a>
                <a href="{{ route('register') }}" class="ml-4 text-white hover:text-gray-200">Register</a>
            @else
                <a href="{{ route('dashboard') }}" class="text-white hover:text-gray-200">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <a href="{{ route('logout') }}" class="text-white hover:text-gray-200" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                </form>
            @endguest
        </div>
    </div>
</nav>