<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService  {

    private $targetDirectory;
    private $slugger; 

    public function __construct(SluggerInterface $slugger, $targetDirectory) {
        $this->slugger = $slugger;
        $this->targetDirectory = $targetDirectory;
    }

    public function upload($file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeName = $this->slugger->slug($originalFilename).  '-' . uniqid() . '.' . $file->guessExtension();
        $file->move($this->getTargetDirectory(), $safeName);

        return $safeName;
    }

    public function getTargetDirectory() : string
    {
        return $this->targetDirectory;
    }
}

