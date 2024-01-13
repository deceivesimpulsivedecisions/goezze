<?php

namespace App\Controller\Api;

use App\Entity\City;
use App\Repository\CityRepository;
use App\Service\HotelService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/hotel/', name: 'app_api_hotel_')]
class HotelController extends AbstractController
{
    #[Route('search', name: 'search')]
    public function index(HotelService $hotelService, EntityManagerInterface $entityManagerInterface, CityRepository $cityRepository): Response
    {
        $cities = $hotelService->list();
        
        foreach($cities as $city)
        {
            $newCity = $cityRepository->findOneBy(['cityCode' => $city['city_code']]);

            if(!is_null($newCity))
            {
                continue;
            }

            $newCity = new City();

            $newCity->setCityCode($city['city_code']);
            $newCity->setCityName($city['city_name']);
            $newCity->setCountryName($city['country_name']);
            $newCity->setCountryCode($city['country_code']);

            $entityManagerInterface->persist($newCity);
            $entityManagerInterface->flush($newCity);
        }


        return $this->render('api/hotel/index.html.twig', [
            'controller_name' => 'HotelController',
        ]);
    }
}
