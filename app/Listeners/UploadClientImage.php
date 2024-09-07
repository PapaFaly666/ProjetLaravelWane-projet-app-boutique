<?php

namespace App\Listeners;

use App\Events\ClientCreated;
use App\Services\CloudinaryService;

class UploadClientImage
{
    private $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function handle(ClientCreated $event)
    {
        $data = request()->all();

        // Upload de l'image
        if (isset($data['users']['image']) && $data['users']['image']->isValid()) {
            try {
                $uploadedImage = $this->cloudinary->uploadImage($data['users']['image']);
                $event->user->image_url = $uploadedImage;
                $event->user->save();
            } catch (\Exception $e) {
                throw new \Exception('Erreur lors du téléchargement de l\'image : ' . $e->getMessage());
            }
        }
    }
}

