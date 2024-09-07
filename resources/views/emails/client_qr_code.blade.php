<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre QR Code Client</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        .card img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 20px;
        }
        .card h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .card p {
            font-size: 16px;
            color: #555;
        }
        .qr-code {
            margin-top: 20px;
            width: 250px;  /* Largeur fixe pour garantir un carré */
            height: 250px; /* Hauteur égale à la largeur */
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }
        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 0; /* Assurez-vous qu'aucun arrondi n'est appliqué ici */
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="{{ $user->image_url }}" alt="Photo du Client">
        <h1>{{ $user->prenom }} {{ $user->nom }}</h1>
        <p>Voici votre QR code contenant vos informations de téléphone.</p>
        <div class="qr-code">
            <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code">
        </div>
    </div>
</body>
</html>
