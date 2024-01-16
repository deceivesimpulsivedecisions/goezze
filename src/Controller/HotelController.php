<?php

namespace App\Controller;

use App\Form\HotelType;
use App\Service\HotelService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hotel/', name: 'app_hotel_')]
class HotelController extends AbstractController
{
    #[Route('search', name: 'search')]
    public function search(Request $request): Response
    {
        $hotels = [];
        $form = $this->createForm(HotelType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hotelService = new HotelService();

            $response = $hotelService->search($form->getData());
            $hotels = $response['Status'] == 1? $response['Search']['HotelSearchResult']['HotelResults']:[];
        }
        return $this->render('hotel/index.html.twig', [
            'form' => $form->createView(),
            'hotels' => $hotels
        ]);
    }
}
