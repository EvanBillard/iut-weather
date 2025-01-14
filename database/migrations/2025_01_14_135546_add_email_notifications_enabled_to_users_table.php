<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajout de la colonne email_notifications_enabled
            $table->boolean('email_notifications_enabled')->default(false)->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Suppression de la colonne email_notifications_enabled
            $table->dropColumn('email_notifications_enabled');
        });
    }
};
