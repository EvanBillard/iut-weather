<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\City;  // Assurez-vous d'importer le modèle City

class WeatherController extends Controller
{
    public function currentWeather(Request $request)
    {
        $city = $request->input('city', 'Paris');

       
        $response = Http::get('http://api.openweathermap.org/data/2.5/forecast', [
            'q' => $city,
            'appid' => env('OPENWEATHER_API_KEY'),
            'units' => 'metric',
        ]);

        
        if ($response->failed()) {
            return redirect()->route('dashboard')->with('error', 'City not found or API error. Please try again.');
        }

        $forecastData = $response->json();

      
        $cityName = isset($forecastData['city']) ? $forecastData['city']['name'] : 'Unknown City';

       
        $cities = City::all();

       
        $forecastAtNoon = collect($forecastData['list'])->filter(function ($day) {
            return \Carbon\Carbon::parse($day['dt_txt'])->format('H:i') == '12:00';
        });

        return view('dashboard', [
            'forecastData' => $forecastAtNoon,  
            'cities' => $cities, 
            'cityName' => $cityName, 
        ]);
    }
}
