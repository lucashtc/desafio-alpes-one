<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class SemiNovosModel extends Model
{
    const url = "https://seminovos.com.br";
    private $urlFilter;

    /**
     * create instace of GuzzleHttp\Client 
     * @return GuzzleHttp\Client
     */
    private function newClient(){
        return new Client(['base_uri' => self::url]);
    }

    private function createSearch($filter){

    } 
    
    public function filterCars(){

        $client = $this->newClient();
        $response = $client->request('GET', 'carro/peugeot/207-passion/ano-2007-2012/preco-35000-300000/particular-origem/revenda-origem/novo-estado/seminovo-estado');

        if($response->getStatusCode() != 200){
            return response()->json(['msg' => 'error']);
        }

        $html = $response->getBody();
        $crawler = new Crawler((string) $html);
        $crawler->filter('.card-title')->each(function(Crawler $node, $i){
            echo $node->text();
        });
    }
}
