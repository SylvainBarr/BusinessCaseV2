<?php

namespace App\Controller;

use App\Entity\Nft;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Request;


class ApiNftController extends AbstractController
{

    public function __invoke(Request $request, FileUploader $fileUploader): Nft
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('Image requise !');
        }

        $nft = new Nft();
        $nft->setName($request->get('name'));
        $nft->setDateDrop($request->get('dateDrop'));
        $nft->setAnneeAlbum($request->get('anneeAlbum'));
        $nft->setIdentificationToken($request->get('identificationToken'));
        $nft->setGroupe($request->get('group'));
        $nft->setSlug($request->get('slug'));

        $nft->setImage($fileUploader->uploadFile($uploadedFile, '/image/'));

        return $nft;



    }

}
