<?php

namespace App\Controller;

use App\Repository\DestinationRepository;
use App\Repository\PackageCategoryRepository;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(DestinationRepository $destinationRepository, PackageCategoryRepository $categoryRepository, PackageRepository $packageRepository): Response
    {
        $packages = $packageRepository->findBy(['trending' => true], null, 5);
        $categories = $categoryRepository->findBy(['trending' => true], null, 5);
        $destinations = $destinationRepository->findBy(['trending' => true], null, 5);
        return $this->render('default/index.html.twig', [
            'destinations' => $destinations,
            'categories' => $categories,
            'packages' => $packages
        ]);
    }
}
