<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientQRCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $qrCodeBase64;

    /**
     * Create a new message instance.
     *
     * @param  $user
     * @param  $qrCodeBase64
     * @return void
     */
    public function __construct($user, $qrCodeBase64)
    {
        $this->user = $user;
        $this->qrCodeBase64 = $qrCodeBase64;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $html = view('emails.client_qr_code', [
            'user' => $this->user,
            'qrCodeBase64' => $this->qrCodeBase64
        ])->render();

        return $this->view('emails.client_qr_code')
                    ->attachData($html, 'client_card.html', [
                        'mime' => 'text/html',
                    ]);
    }
}
