<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homme</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    @include('layouts.header')

    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4">Welcome to the Weather App</h1>
            <p>By Evan Billard</p>
            <a href="{{ route('login') }}">
               Start now !
            </a>
        </div>
    </div>
</body>

</html>
