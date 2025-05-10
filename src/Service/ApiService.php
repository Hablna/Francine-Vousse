<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
class ApiService
{
    private $client;

    public function __construct(HttpClientInterface $client){
        $this->client = $client;
    }

    public function getPlanifications(){
        $reponses = $this->client->request('GET', 'http://172.16.124.35/api/Spectacles/GetAllSpectacles');

        return $reponses->toArray();
    }
}
