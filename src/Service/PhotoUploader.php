<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class PhotoUploader
{
    public function __construct(
        #[Autowire('%animals_photos_directory%')] private readonly string $directory,
        private readonly SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $this->slugger->slug($original) . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move($this->directory, $filename);

        return $filename;
    }

    public function remove(?string $filename): void
    {
        if ($filename && is_file($path = $this->directory . '/' . $filename)) {
            unlink($path);
        }
    }
}
