<?php
use Illuminate\Console\Command;
use App\Models\City;
use App\Models\User;
use App\Mail\WeatherForecastMail;
use Illuminate\Support\Facades\Mail;

class SendWeatherForecastMail extends Command
{
    protected $signature = 'send:weather-forecast {user_id}'; // Ajout du paramètre user_id
    protected $description = 'Send weather forecast for the favorite city of the user';

    public function handle()
    {
        // Récupérer l'utilisateur à partir de l'ID passé en argument
        $userId = $this->argument('user_id');
        $user = User::find($userId); // Trouver l'utilisateur par son ID

        if (!$user) {
            $this->error('User not found.');
            return;
        }

        // Récupérer la ville favorite de l'utilisateur
        $favoriteCity = City::where('user_id', $user->id)->where('is_favorite', true)->first();

        if (!$favoriteCity) {
            $this->error('No favorite city found for this user.');
            return;
        }

        // Récupérer les prévisions météo pour la ville favorite
        $weatherData = $this->getWeatherForecast($favoriteCity->name);

        // Préparer le fichier CSV
        $csvFile = $this->generateCsv($weatherData);

        // Envoi du mail
        Mail::to($user->email)->send(new WeatherForecastMail($csvFile));

        $this->info('Weather forecast sent to ' . $user->email);
    }

    // Méthode pour récupérer les prévisions météo
    private function getWeatherForecast($cityName)
    {
        // Appel à l'API OpenWeather pour récupérer les données météo
        // Cette fonction doit retourner les données au format attendu
        return [
            ['date' => now()->toDateString(), 'temperature' => 15], // Exemple
        ];
    }

    // Méthode pour générer le fichier CSV
    private function generateCsv($weatherData)
    {
        $csvFile = storage_path('app/weather_forecast.csv');
        $file = fopen($csvFile, 'w');

        // En-tête du fichier CSV
        fputcsv($file, ['Date', 'Temperature']);

        // Données météo
        foreach ($weatherData as $data) {
            fputcsv($file, [$data['date'], $data['temperature']]);
        }

        fclose($file);
        return $csvFile;
    }
}
