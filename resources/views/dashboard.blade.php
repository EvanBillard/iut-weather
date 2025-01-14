<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <!-- Form to search for weather by city -->
    <form method="GET" action="{{ url('/weather') }}">
        <label for="city">Enter city name:</label>
        <input type="text" name="city" id="city" placeholder="Paris" required>
        <button type="submit">Get the weather</button>
    </form>

    <!-- Display error message if it exists -->
    @if (session('error'))
        <p style="color: red;">
            {{ session('error') }}
        </p>
    @else
        <!-- Display current weather data if available -->
        @if (isset($forecastData) && $forecastData->isNotEmpty())
            <h1>Weather Forecast for {{ $cityName }}</h1> <!-- Utilisation de $cityName ici -->

            <h2>Weather Forecast at 12:00</h2>
            <div style="display: flex; flex-wrap: wrap;">
                <!-- Display the forecast for 12:00 -->
                @foreach ($forecastData as $day)
                    <div style="border: 1px solid #ccc; border-radius: 5px; padding: 10px; margin: 5px; width: 200px;">
                        <p>Date: {{ \Carbon\Carbon::parse($day['dt_txt'])->format('l, F j') }}</p>
                        <p>Time: {{ \Carbon\Carbon::parse($day['dt_txt'])->format('g:i A') }}</p>
                        <p>Temperature: {{ $day['main']['temp'] }} °C</p>
                        <p>Weather: {{ $day['weather'][0]['description'] }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Add Save City button -->
            <form method="POST" action="{{ route('cities.store') }}">
                @csrf
                <input type="hidden" name="city_to_save" value="{{ $cityName }}">
                <button type="submit" style="background-color: #3490dc; color: white; border-radius: 5px; border: 2px solid black; cursor: pointer;">
                    Save this city
                </button>
            </form>
        @else
            <p>No weather data available for 12:00.</p>
        @endif
    @endif

    <!-- Display saved cities -->
    <h2>Saved Cities</h2>
    <ul>
        @foreach ($cities as $city)
            <li>
                <!-- Button to show weather for the saved city -->
                <form method="GET" action="{{ url('/weather') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="city" value="{{ $city->name }}">
                    <button type="submit" style="background-color:rgb(10, 199, 79); color: white; border-radius: 5px; border: 2px solid black; cursor: pointer;">
                        {{ $city->name }}
                    </button>
                </form>

                <!-- Mark city as favorite -->
                @if (!$city->is_favorite)
                    <form method="POST" action="{{ route('cities.setFavorite', $city->id) }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="background-color: #3490dc; color: white; border-radius: 5px; border: 2px solid black; cursor: pointer;">
                            Set as Favorite
                        </button>
                    </form>
                @else
                    <span style="color:rgb(40, 171, 66)">(Favorite)</span>
                @endif

                <!-- Delete city -->
                <form method="POST" action="{{ route('cities.destroy', $city->id) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background-color:rgb(234, 87, 87); color: white; border-radius: 5px; border: 2px solid black; cursor: pointer;">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>

    <h2>Email Notifications</h2>
<form method="POST" action="{{ route('toggle.email.notifications') }}">
    @csrf
    <button type="submit" style="background-color: {{ Auth::user()->email_notifications_enabled ? 'rgb(234, 87, 87)' : 'rgb(10, 199, 79)' }}; color: white; border-radius: 5px; border: 2px solid black; cursor: pointer;">
        {{ Auth::user()->email_notifications_enabled ? 'Disable Email Notifications' : 'Enable Email Notifications' }}
    </button>
</form>


</x-app-layout>
