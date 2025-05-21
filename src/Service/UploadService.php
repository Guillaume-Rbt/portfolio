<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService
{

    private $imagesDirectory;
    private $filesDirectory;
    private $slugger;

    public function __construct(SluggerInterface $slugger, String $imagesDirectory, String $filesDirectory)
    {
        $this->slugger = $slugger;
        $this->imagesDirectory = $imagesDirectory;
        $this->filesDirectory = $filesDirectory;
    }

    public function uploadImage($file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeName = $this->slugger->slug($originalFilename) .  '-' . uniqid() . '.' . $file->guessExtension();
        $file->move($this->imagesDirectory, $safeName);

        return $safeName;
    }


    public function uploadDocument($file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeName = $this->slugger->slug($originalFilename) .  '-' . uniqid() . '.' . $file->guessExtension();
        $file->move($this->filesDirectory, $safeName);

        return $safeName;
    }
}
