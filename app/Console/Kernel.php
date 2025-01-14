<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Charge toutes les commandes du dossier Commands
        $this->load(__DIR__.'/Commands');

        // Inclure d'autres fichiers de commandes si nécessaire
        require base_path('routes/console.php');
    }

}
