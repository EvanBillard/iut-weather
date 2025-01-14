<!-- resources/views/weather/current.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Météo actuelle</title>
</head>
<body>
    <h1>Météo actuelle</h1>

    <!-- Affichage des erreurs s'il y en a -->
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Affichage des données météo si elles existent -->
    @isset($weather)
        <h2>{{ $weather['city'] }}</h2>
        <p>Température actuelle : {{ $weather['temperature'] }}°C</p>
        <p>Description : {{ $weather['description'] }}</p>
        <p>Date : {{ $weather['date'] }}</p>
    @else
        <p>Aucune donnée météo disponible.</p>
    @endisset
</body>
</html>
