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
        
        $user = User::first(); 
        
        if (!$user) {
            $this->error('No user found.');
            return;
        }

       
        $favoriteCity = City::where('user_id', $user->id)->where('is_favorite', true)->first();

        if (!$favoriteCity) {
            $this->error('No favorite city found for this user.');
            return;
        }

       
        $weatherData = $this->getWeatherForecast($favoriteCity->name);

       
        $csvFile = $this->generateCsv([
            ['date' => now()->toDateString(), 'temperature' => $weatherData['temperature'], 'city' => $favoriteCity->name]
        ]);

      
        try {
            Mail::to($user->email)->send(new WeatherForecastMail($csvFile));
            $this->info('Weather forecast sent to ' . $user->email);
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
        }
    }

    
    private function getWeatherForecast($cityName)
    {
        $apiKey = env('OPENWEATHER_API_KEY');  
        $response = Http::get('http://api.openweathermap.org/data/2.5/weather', [
            'q' => $cityName,
            'appid' => $apiKey,
            'units' => 'metric',
        ]);

        
        if ($response->successful()) {
            $data = $response->json();
            return [
                'temperature' => $data['main']['temp'] ?? 'N/A',  
            ];
        }

        
        return [
            'temperature' => 'N/A',
        ];
    }

    
    private function generateCsv($weatherData)
    {
        $csvFile = storage_path('app/weather_forecast.csv');
        $file = fopen($csvFile, 'w');

        
        fputcsv($file, ['Date', 'Temperature', 'City']);  

       
        foreach ($weatherData as $data) {
            fputcsv($file, [$data['date'], $data['temperature'], $data['city']]);
        }

        fclose($file);
        return $csvFile;
    }
}
