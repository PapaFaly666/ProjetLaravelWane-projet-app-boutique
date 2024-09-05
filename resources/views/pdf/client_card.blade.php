<!DOCTYPE html>
<html>
<head>
    <title>Carte Client</title>
    <style>
        .card {
            width: 100%;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
            border-radius: 50%;
        }
        .content {
            text-align: center;
        }
        .content img {
            margin: 20px 0;
            max-width: 100px;
        }
        .qr-code {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>Carte Client</h1>
            @if($clientImage)
                <img src="{{ $clientImage }}" alt="Photo du client">
            @endif
        </div>
        <div class="content">
            <h2>{{ $client->surnom }}</h2>
            <p>Nom: {{ $client->nom }}</p>
            <p>Prénom: {{ $client->prenom }}</p>
            <p>Téléphone: {{ $client->telephone }}</p>
            <p>Adresse: {{ $client->adresse }}</p>
            <div class="qr-code">
                <img src="{{ $qrCodeImage }}" alt="QR Code">
            </div>
        </div>
    </div>
</body>
</html>
