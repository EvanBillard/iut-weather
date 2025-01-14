<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\City;
use App\Models\User;
use App\Mail\WeatherForecastMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class SendWeatherForecastMail extends Command
{
    protected $signature = 'send:weather-forecast';
    protected $description = 'Send weather forecast for the favorite city of the user';

    public function handle()
    {
        // Récupérer l'utilisateur par défaut ou un utilisateur spécifique (par exemple, le premier utilisateur)
        $user = User::first();  // Remplace cette ligne par une logique spécifique si nécessaire

        // Si l'utilisateur n'est pas trouvé
        if (!$user) {
            $this->error('No user found.');
            return;
        }

        // Récupérer la ville favorite de l'utilisateur
        $favoriteCity = City::where('user_id', $user->id)->where('is_favorite', true)->first();

        if (!$favoriteCity) {
            $this->error('No favorite city found for this user.');
            return;
        }

        // Récupérer la météo pour la ville favorite
        $weatherData = $this->getWeatherForecast($favoriteCity->name);

        // Préparer le fichier CSV avec la ville, la température et la date
        $csvFile = $this->generateCsv([
            ['date' => now()->toDateString(), 'temperature' => $weatherData['temperature'], 'city' => $favoriteCity->name]
        ]);

        // Envoi du mail
        try {
            Mail::to($user->email)->send(new WeatherForecastMail($csvFile));
            $this->info('Weather forecast sent to ' . $user->email);
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
        }
    }

    // Méthode pour récupérer les prévisions météo directement via l'API OpenWeather
    private function getWeatherForecast($cityName)
    {
        $apiKey = env('OPENWEATHER_API_KEY');  // Assurez-vous que la clé API est dans .env
        $response = Http::get('http://api.openweathermap.org/data/2.5/weather', [
            'q' => $cityName,
            'appid' => $apiKey,
            'units' => 'metric',
        ]);

        // Vérifier si la requête a réussi
        if ($response->successful()) {
            $data = $response->json();
            return [
                'temperature' => $data['main']['temp'] ?? 'N/A',  // Température en degrés Celsius
            ];
        }

        // Retourner une valeur par défaut si l'API échoue
        return [
            'temperature' => 'N/A',
        ];
    }

    // Méthode pour générer le fichier CSV
    private function generateCsv($weatherData)
    {
        $csvFile = storage_path('app/weather_forecast.csv');
        $file = fopen($csvFile, 'w');

        // En-tête du fichier CSV
        fputcsv($file, ['Date', 'Temperature', 'City']);  // Nouvelle colonne 'City'

        // Données météo
        foreach ($weatherData as $data) {
            fputcsv($file, [$data['date'], $data['temperature'], $data['city']]);
        }

        fclose($file);
        return $csvFile;
    }
}
