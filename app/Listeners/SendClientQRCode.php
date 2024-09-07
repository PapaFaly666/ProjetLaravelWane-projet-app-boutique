<?php

namespace App\Listeners;

use App\Events\ClientCreated;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mail\ClientQRCodeMail;

class SendClientQRCode
{
    public function handle(ClientCreated $event)
    {
        // Générer le QR code
        try {
            $qrCode = QrCode::format('png')->size(200)->generate($event->client->telephone);
            $qrCodeBase64 = base64_encode($qrCode);
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la génération du QR code : ' . $e->getMessage());
        }

        // Envoyer l'email avec le QR code
        try {
            Mail::to($event->user->email)->send(new ClientQRCodeMail($event->user, $qrCodeBase64));
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }
    }
}
