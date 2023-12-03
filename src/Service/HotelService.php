<?php 

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
class HotelService{

    // private $baseUrl = 'http://test.services.travelomatix.com/webservices/index.php/hotel_v3/service/HotelCityList';
    private $baseUrl = 'http://test.services.travelomatix.com/webservices/index.php/hotel_v3/service/Search';
    private $username = 'test309878';
    private $password = 'test@309';
    private $domainKey = 'TMX5743091695358667';
    private $system = 'test';

    public function list()
    {
        $client = new Client();

        $headers = [
            'x-Username' => $this->username,
            'x-DomainKey' => $this->domainKey,
            'x-system' => $this->system,
            'x-Password' => $this->password,        
            'Accept' => '*/*',
            'Accept-Encoding' => 'gzip, deflate'
        ];

        $filters = [
            'CheckInDate' => "27-06-2018",
            'NoOfNights' => 1,
            "CountryCode" => 'EG',
            "CityId" => 1,
            "GuestNationality" => 'IN',
            "NoOfRooms" => 1,
            "RoomGuests" => [
                [
                    "NoOfAdults" => 2,
                    "NoOfChild" => 0
                ]
            ]
        ];

        $request = new Request('POST', $this->baseUrl."?limit=10", $headers);
        $res = $client->sendAsync($request, $filters)->wait();
        $data = json_decode($res->getBody(), true);

        dd($data);
    }
    
}