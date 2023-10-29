<?php

namespace App\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as HttpRequest;


#[Route('/flight', name: 'flight_')]
class FlightController extends AbstractController
{
    public function generateToken(){
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];
        $options = [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $_ENV['AMADEUS_API_KEY'],
                'client_secret' => $_ENV['AMADEUS_API_SECRET']
            ]];
        $request = new Request('POST', 'https://test.api.amadeus.com/v1/security/oauth2/token', $headers);
        $res = $client->sendAsync($request, $options)->wait();
        $data = json_decode($res->getBody(), true);

        return $data['token_type'] .' ' . $data['access_token'];
    }
    #[Route('/airport/list', name: 'search_airport')]
    public function searchAirport(HttpRequest $httpRequest){
        $place = $httpRequest->query->get('term');
        $token = $this->generateToken();
        $client = new Client();
        $headers = [
            'Authorization' => $token
        ];
        $request = new Request('GET', 'https://test.api.amadeus.com/v1/reference-data/locations?subType=AIRPORT&keyword=' . $place .'&page%5Blimit%5D=10&page%5Boffset%5D=0&sort=analytics.travelers.score&view=FULL', $headers);
        $res = $client->sendAsync($request)->wait();
        $airports = json_decode($res->getBody(), true);

        $iataCode = [];

        foreach ($airports['data'] as $airport){
            $iataCode[] = ['id' => $airport['address']['cityCode'], 'text' => $airport['address']['cityName'] . ' - ' . $airport['address']['cityCode']];
        }

        return new JsonResponse($iataCode);
    }

    #[Route('/search/flights', name: 'search_flights')]
    public function searchFlights(HttpRequest $request){
        return $this->render('flight/list.html.twig');
    }

    #[Route('/search/flights/results', name: 'search_flights_result')]
    public function searchFlightsResult(HttpRequest $request){
        $from = $request->query->get('flight-from');
        $to = $request->query->get('flight-to');
        $date = $request->query->get('datepicker');

//        dd($from, $to, $date);

        $client = new Client();
        $headers = [
            'x-Password' => $_ENV['PROVAB_PASSWORD'],
            'x-DomainKey' => $_ENV['PROVAB_DOMAIN_KEY'],
            'x-Username' => $_ENV['PROVAB_USERNAME'],
            'x-system' => 'test',
            'Content-Type' => 'application/json'
        ];


        $body = '{
                      "AdultCount": "1",
                      "ChildCount": "0",
                      "InfantCount": "0",
                      "JourneyType": "OneWay",
                      "PreferredAirlines": [
                        ""
                      ],
                      "CabinClass": "Economy",
                      "Segments": [
                        {
                          "Origin": "' . $from .'",
                          "Destination": "' . $to .'",
                          "DepartureDate": "' . $date .'T00:00:00"
                        }
                      ]
                    }';
        $request = new Request('POST', 'http://test.services.travelomatix.com/webservices/index.php/flight/service/Search', $headers, $body);
        $res = $client->sendAsync($request)->wait();
        $result = json_decode($res->getBody(), true);
        return $this->render('flight/list_flights.html.twig', [
            'flights' => $result['Search']['FlightDataList']['JourneyList'][0],
        ]);
    }

}