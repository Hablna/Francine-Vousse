<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(OffreRepository $offreRepository): Response
    {
        //obtenir les offres creer
        $offres = $offreRepository->findAll();

        return $this->render('home/index.html.twig', [
            'offres' => $offres,
        ]);
    }
}
