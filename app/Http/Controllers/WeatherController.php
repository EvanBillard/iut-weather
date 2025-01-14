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

        // Appel à l'API météo pour les prévisions sur 5 jours
        $response = Http::get('http://api.openweathermap.org/data/2.5/forecast', [
            'q' => $city,
            'appid' => env('OPENWEATHER_API_KEY'),
            'units' => 'metric',
        ]);

        // Vérifier si l'API a retourné une réponse correcte
        if ($response->failed()) {
            return redirect()->route('dashboard')->with('error', 'City not found or API error. Please try again.');
        }

        $forecastData = $response->json();

        // Vérifier si la clé 'city' existe dans la réponse
        $cityName = isset($forecastData['city']) ? $forecastData['city']['name'] : 'Unknown City';

        // Récupérer les villes enregistrées pour les passer à la vue
        $cities = City::all();

        // Filtrer les prévisions pour ne garder que celles de 12:00
        $forecastAtNoon = collect($forecastData['list'])->filter(function ($day) {
            return \Carbon\Carbon::parse($day['dt_txt'])->format('H:i') == '12:00';
        });

        return view('dashboard', [
            'forecastData' => $forecastAtNoon,  // Passer les prévisions filtrées
            'cities' => $cities,  // Passer la liste des villes enregistrées
            'cityName' => $cityName,  // Passer le nom de la ville à la vue
        ]);
    }
}
