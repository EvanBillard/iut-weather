<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeatherForecastMail extends Mailable
{
    use SerializesModels;

    public $csvFile;

    /**
     * Create a new message instance.
     *
     * @param string $csvFile
     */
    public function __construct($csvFile)
    {
        $this->csvFile = $csvFile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.weather_forecast')
                    ->attach($this->csvFile, [
                        'as' => 'weather_forecast.csv',
                        'mime' => 'text/csv',
                    ])
                    ->subject('Weather Forecast');
    }
}
