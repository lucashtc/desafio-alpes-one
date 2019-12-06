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
    
    public function filterCars($request){

        $client = $this->newClient();
        $urlBusca = '';
        
        // Define tipo de veiculo
        // Parametros obrigatorios
        if($request->veiculo == 'carro'){
            $urlBusca = '/carro';
        }
        if($request->veiculo == 'moto'){
            $urlBusca = '/moto';
        }
        if($request->veiculo == 'caminhao'){
            $urlBusca = '/caminhao';
        }

        $urlBusca .= $request->marca;

        $urlBusca .= $request->modelo ? '/' . $request->modelo : '';
        $urlBusca .= $request->ano ? '/ano-' . $request->ano : '';

        if($request->preco){
            if(count(explode('-',$request->preco)) == 1){
                $urlBusca .= '/preco-' . $request->preco . '-';    
            }
            $urlBusca .= '/preco-' . $request->preco;
        }
        $urlBusca .= $request->estado ? '/estado-' . $request->estado : '';
    
        $urlBusca .= $request->origem ? '/estado-' . $request->origem : '';
        
        if($request->financiamento){
            $urlBusca .= $request->financiamento == 'com financiamento' ? '/financiamento-' . '1' : '';
            $urlBusca .= $request->financiamento == 'sem financiamento' ? '/financiamento-' . '2' : '';
        }
        if($request->troca){
            $urlBusca .= $request->troca == 'com financiamento' ? '/financiamento-' . '1' : '';
            $urlBusca .= $request->troca ? '/troca-' . '4' : '';
        }
 
        $urlBusca .= $request->registrosPagina ? '?registrosPagina=' . $request->registrosPagina : '';
        
        $response = $client->request('GET', $urlBusca);

        if($response->getStatusCode() != 200){
            return response()->json(['msg' => 'error'],500);
        }

        $html = $response->getBody();
        $crawler = new Crawler((string) $html);
        
        $result = $crawler->filter('.card')->each(function(Crawler $node, $i){
            $carName = $node->filter('.card-title')->each(function(Crawler $nodeCar,$i){
                return $nodeCar->text();
            });
            $id = $node->filterXPath('//a')->attr('href');
            $id = explode('?',$id);
            $id = count($id) > 0 ? $id[0] : '';
            $carName = count($carName) > 0 ? $carName[0] : '';
            return ['car_name' => $carName,'id' => $id];
        });
        return response()->json($result,200,array('Content-Type' => 'application/json;charset=utf8'));
    }

    public function detalhes($id) {

    }
}
