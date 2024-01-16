<?php 

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
class HotelService{

    private $baseUrl = 'http://test.services.travelomatix.com/webservices/index.php/hotel_v3/service/HotelCityList';
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


        $request = new Request('GET', $this->baseUrl."?page%5Blimit%5D=10&page%5Boffset%5D=0", $headers);
        $res = $client->sendAsync($request)->wait();
        $data = json_decode($res->getBody(), true);

        return $data['HotelCityList'];
    }

    public function search($filters)
    {
        $filters['CheckInDate'] = $filters['CheckInDate']->format('d-m-Y');

        $client = new Client();

        $headers = [
            'x-Username' => $this->username,
            'x-DomainKey' => $this->domainKey,
            'x-system' => $this->system,
            'x-Password' => $this->password,
            'Accept' => '*/*',
            'Accept-Encoding' => 'gzip, deflate'

        ];

        $request = new Request('POST', $_ENV['HOTEL_SEARCH'], $headers,json_encode($filters));
        $res = $client->sendAsync($request)->wait();
        return json_decode($res->getBody(), true);
    }
    
}