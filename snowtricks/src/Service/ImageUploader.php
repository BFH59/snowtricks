<?php

namespace App\Service;

use App\Entity\Image;

class ImageUploader
{

    /**
     * specific service for image upload to use image object as parameter
     * @param Image $image
     * @return Image $image
     */
    public function uploadImage(Image $image): Image
    {
        $file = $image->getFile();
        if ($file !== null) {
            $name = md5(uniqid()) . '.' . $file->guessExtension();
            $path = 'uploads/image';
            $file->move($path, $name);
            $image->setPath($path);
            $image->setName($name);
        }
        return $image;
    }

}