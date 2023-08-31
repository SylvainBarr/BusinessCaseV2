<?php

namespace App\Controller;

use App\Entity\Nft;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiNftController extends AbstractController
{
    #[Route('/api/nfts/create', name: 'app_api_nfts', methods: ["POST"])]
    public function createNft(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        $data = $request->getContent();

        $nft = $serializer->deserialize($data, Nft::class, 'json');

        // Validez l'entité Nft
        $errors = $validator->validate($nft);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        // Gérez le téléchargement et le stockage de l'image
        $imageFile = $request->files->get('image');
        if ($imageFile) {
            $imageFileName = uniqid().'.'.$imageFile->getClientOriginalExtension();
            $imageFile->move($this->getParameter('nft_image_directory'), $imageFileName);
            $nft->setImage($imageFileName);
        }

        // Enregistrez l'entité Nft en base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($nft);
        $entityManager->flush();

        return $this->json($nft, Response::HTTP_CREATED);
    }
}
