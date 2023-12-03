<?php

namespace App\Controller\Api;

use App\Service\HotelService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/hotel/', name: 'app_api_hotel_')]
class HotelController extends AbstractController
{
    #[Route('search', name: 'search')]
    public function index(HotelService $hotelService): Response
    {
        dd($hotelService->list());
        return $this->render('api/hotel/index.html.twig', [
            'controller_name' => 'HotelController',
        ]);
    }
}
