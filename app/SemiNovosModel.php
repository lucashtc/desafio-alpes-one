<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class SemiNovosModel extends Model
{
    const url = "https://seminovos.com.br";

    public function  newClient(){
        return new Client(['base_uri' => self::url]);
    }
    
    public function filterCars(){

        $client = $this->newClient();
        
        $response = $client->request('GET', 'carro/jeep');
        echo  $response->getBody() ;
    }
}
