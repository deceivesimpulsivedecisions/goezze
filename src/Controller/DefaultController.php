<?php

namespace App\Controller;

use App\Repository\DestinationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(DestinationRepository $destinationRepository): Response
    {
        $destinations = $destinationRepository->findAll();
        return $this->render('default/index.html.twig', [
            'destinations' => $destinations,
        ]);
    }
}
