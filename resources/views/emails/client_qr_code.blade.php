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
            width: 100%;  /* Occuper toute la largeur du parent */
            padding-top: 100%; /* Crée un carré avec un rapport 1:1 */
            position: relative;
        }
        .qr-code img {
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 50%;
            object-fit: contain;  /* Ajuste l'image tout en conservant les proportions */
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
