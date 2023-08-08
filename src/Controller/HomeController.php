<?php

namespace App\Controller;

use App\Repository\NftRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_admin_home')]
    public function index(UserRepository $userRepository, NftRepository $nftRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'lastUsers' => $userRepository->getLastUsers(6),
            'lastNfts' => $nftRepository->getLastNFTs(6),
        ]);
    }
}
