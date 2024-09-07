<?php
namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;

class CloudinaryService{
    public function uploadImage($image)
    {
        try {
            return Cloudinary::upload($image->getRealPath())->getSecurePath();
        } catch (Exception $e) {
            throw new Exception('Erreur lors du téléchargement de l\'image : ' . $e->getMessage());
        }
    }
    

}