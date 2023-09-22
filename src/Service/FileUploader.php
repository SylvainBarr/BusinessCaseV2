<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{

    /**
     * FileUploader constructor.
     * @param string $pathUploadsDir complete path to default directory of uploaded files
     * @param string $uploadsDir default directory of uploaded files
     * See : config/services.yaml and .env
     */
    public function __construct(
        private readonly string $publicUploadsDir,
        private readonly string $uploadsDir
    ) { }

    public function uploadFile(UploadedFile $uploadedFile, string $namespace = ''): string
    {
        $destination = $this->publicUploadsDir.$namespace;
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
        $uploadedFile->move($destination, $newFilename);
        return '/'.$this->uploadsDir.$namespace.'/'.$newFilename;
    }

}
