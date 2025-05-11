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

    public function getAllSpectacles(){
        $reponses = $this->client->request('GET', 'http://172.16.124.35/api/Spectacles/GetAllSpectacles');
         if ($reponses->getStatusCode() === 200){
             return $reponses->toArray();
         }
         return null;
    }
    public function getPlanifications($id){
        $reponses = $this->client->request('GET', 'http://172.16.124.35/api/Spectacles/GetPlanifications/',[
            'query'=>[
                'id'=>$id
            ],
        ]);
        if ($reponses->getStatusCode() === 200){
            return $reponses->toArray();
        }
        return null;
    }

    public function checkBillet(int $idBillet, int $idSpectacle): bool
    {
        $response = $this->client->request('POST', 'http://adresse-api/api/Spectacles/CheckBillet', [
            'json' => [
                'idBillet' => $idBillet,
                'idSpectacle' => $idSpectacle,
            ],
        ]);

        // Vérifie que la requête a bien fonctionné
        if ($response->getStatusCode() !== 200) {
            throw new \Exception("Erreur lors de la vérification du billet.");
        }

        // Puisque l’API retourne un booléen pur, utilise `getContent()` et cast en bool
        return filter_var($response->getContent(), FILTER_VALIDATE_BOOLEAN);
    }
}
