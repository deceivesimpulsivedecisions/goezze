<?php

namespace App\Controller;

use App\Repository\AirportsRepository;
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
    public function searchAirport(HttpRequest $httpRequest, AirportsRepository $airportsRepository){
        $place = $httpRequest->query->get('term');

        $airports = $airportsRepository->findAirports($place);
        $iataCode = [];

        foreach ($airports as $airport){
            $iataCode[] = ['id' => $airport->getAirportCode(), 'text' => $airport->getAirportCode() . ' - ' . $airport->getAirportName()];
        }

        return new JsonResponse($iataCode);
    }

    #[Route('/search/flights', name: 'search_flights')]
    public function searchFlights(HttpRequest $httpRequest){
        return $this->render('flight/list.html.twig');
    }

    #[Route('/get/fare-details', name: 'fetchFareDetails')]
    public function fetchFareDetails(HttpRequest $request){
        $token = $request->request->get('itineraryId');

        $client = new Client();
        $headers = [
            'x-Password' => $_ENV['PROVAB_PASSWORD'],
            'x-DomainKey' => $_ENV['PROVAB_DOMAIN_KEY'],
            'x-Username' => $_ENV['PROVAB_USERNAME'],
            'x-system' => 'test',
            'Content-Type' => 'application/json'
        ];
        $body = '{
            "ResultToken": "'. $token .'"
        }';
        $request = new Request('POST', 'http://test.services.travelomatix.com/webservices/index.php/flight/service/FareRule', $headers, $body);
        $res = $client->sendAsync($request)->wait();
        $data = json_decode($res->getBody(), true);

        return $this->json($data);
    }

    #[Route('/flight/review-details', name: 'reviewDetails')]
    public function updatedFareQuote(HttpRequest $request){
        $token = $request->query->get('itineraryId');

        $client = new Client();
        $headers = [
            'x-Password' => $_ENV['PROVAB_PASSWORD'],
            'x-DomainKey' => $_ENV['PROVAB_DOMAIN_KEY'],
            'x-Username' => $_ENV['PROVAB_USERNAME'],
            'x-system' => 'test',
            'Content-Type' => 'application/json'
        ];
        $body = '{
            "ResultToken": "'. $token .'"
        }';
        $request = new Request('POST', 'http://test.services.travelomatix.com/webservices/index.php/flight/service/UpdateFareQuote', $headers, $body);
        $res = $client->sendAsync($request)->wait();

        $requestExtra = new Request('POST', 'http://test.services.travelomatix.com/webservices/index.php/flight/service/ExtraServices', $headers, $body);
        $resExtra = $client->sendAsync($requestExtra)->wait();


        $data = json_decode($res->getBody(), true);
        $dataExtra = json_decode($resExtra->getBody(), true);

        return $this->render('flight/review_flight_details.html.twig', [
            'details' => $data['Status'] === 1 ? $data['UpdateFareQuote'] : $data,
            'extra' => $dataExtra
        ]);
    }

    #[Route('/search/flights/results', name: 'search_flights_result')]
    public function searchFlightsResult(HttpRequest $request){
        $from = $request->query->get('flight-from');
        $to = $request->query->get('flight-to');
        $fromDate = $request->query->get('from-date');
        $toDate = $request->query->get('to-date');
        $adult = $request->query->get('adult');
        $children = $request->query->get('children');
        $infant = $request->query->get('infant');
        $tripStatus = $request->query->get('tripStatus');

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
                    "AdultCount": "'.$adult.'",
                    "ChildCount": "'.$children.'",
                    "InfantCount": "'.$infant.'",
                    "JourneyType": "'.$tripStatus.'",
                    "PreferredAirlines": [
                    ""
                    ],
                    "CabinClass": "Economy",
                    "Segments": [
                    {
                    "Origin": "'.$from.'",
                    "Destination": "'.$to.'",
                    "DepartureDate": "'.$fromDate.'T00:00:00",
                    "ReturnDate": "'.$toDate.'T00:00:00"
                    }
                    ]
                }';

        $request = new Request('POST', 'http://test.services.travelomatix.com/webservices/index.php/flight/service/Search', $headers, $body);
        $res = $client->sendAsync($request)->wait();
        $result = json_decode($res->getBody(), true);
        $groupedFlights = [];
        if($result['Status'] == 1) {
            $flightDetails = $result['Search']['FlightDataList']['JourneyList'];


            $groupedFlights['oneway'][] = $this->groupAndSortFlights($flightDetails[0]);
            if (isset($flightDetails[1])) {
                $groupedFlights['return'][] = $this->groupAndSortFlights($flightDetails[1]);
            }
        }
        return $this->render('flight/list_flights.html.twig', [
            'groupedFlights' => $groupedFlights
        ]);
    }

    public function groupAndSortFlights($flights)
    {
        foreach ($flights as $flight) {
            $flightNumbers = [];
            if(count($flight['FlightDetails']['Details'][0]) > 1){
                foreach ($flight['FlightDetails']['Details'][0] as $detail){
                    $flightNumbers[] = $detail['OperatorCode'] . $detail['FlightNumber'];
                }
                $flightNumber = implode('-', $flightNumbers);
            } else {
                $flightNumber = $flight['FlightDetails']['Details'][0][0]['OperatorCode'] . $flight['FlightDetails']['Details'][0][0]['FlightNumber'];
            }

            if (!isset($groupedFlights[$flightNumber])) {
                // If the FlightNumber is not in the groupedFlights array, create a new entry
                $groupedFlights[$flightNumber] = [];
            }

            // Add the flight to the corresponding group
            $groupedFlights[$flightNumber][] = $flight;
        }

        foreach ($groupedFlights as &$group) {
            usort($group, function($a, $b) {
                $priceA = $a['Price']['TotalDisplayFare'];
                $priceB = $b['Price']['TotalDisplayFare'];

                if ($priceA == $priceB) {
                    return 0;
                }

                return ($priceA < $priceB) ? -1 : 1;
            });
        }

        return $groupedFlights;
    }

    #[Route('/commit-booking', name: 'commitBooking')]
    public function commitBooking(HttpRequest $request){
        $data = $request->request->all();
        $randomString = bin2hex(random_bytes(8)); // 8 bytes will result in a 16-character hexadecimal string

        // Trim the string to 15 characters
        $randomString = substr($randomString, 0, 15);
        $passengers = [];
        foreach ($data['Title'] as $key => $title) {
            $passenger = [
                "IsLeadPax"=> "1",
                'Title' => $title,
                'FirstName' => $data['FirstName'][$key],
                'LastName' => $data['LastName'][$key],
                "Gender"=> 1,
                'DateOfBirth' => $data['DateOfBirth'][$key],
                "PassportNumber"=> "",
                "CountryCode"=> "IN",
                "CountryName"=> "India",
                "ContactNo"=> "9372850180",
                "City"=> "Bangalore",
                "PinCode"=> "560100",
                "AddressLine1"=> "2nd Floor, Venkatadri IT Park, HP Avenue,, Konnappana Agrahara, Electronic city",
                "Email"=> "nitinvarghese829@gmail.com",
                "PaxType"=> 1
            ];

            $passengers[] = $passenger;
        }

        $passengerData = [
            "AppReference" => $randomString,
            "SequenceNumber" => "0",
            "ResultToken" => $data['ResultToken'],
            "Passengers" => $passengers
        ];

        $client = new Client();
        $headers = [
            'x-Password' => $_ENV['PROVAB_PASSWORD'],
            'x-DomainKey' => $_ENV['PROVAB_DOMAIN_KEY'],
            'x-Username' => $_ENV['PROVAB_USERNAME'],
            'x-system' => 'test',
            'Content-Type' => 'application/json'
        ];
        $body = json_encode($passengerData, JSON_PRETTY_PRINT);
        $request = new Request('POST', 'http://test.services.travelomatix.com/webservices/index.php/flight/service/CommitBooking', $headers, $body);
        $res = $client->sendAsync($request)->wait();
        echo $res->getBody();
        dd();

    }

}