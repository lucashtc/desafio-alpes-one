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
    
    /**
     * retorna json com base na busca
     * @param Illuminate\Http\Request
     * @return array
     */
    public function filterCars(\Illuminate\Http\Request $request){

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

        $urlBusca .= $request->marca ? '/' . $request->marca : '';

        $urlBusca .= $request->modelo ? '/' . $request->modelo : '';
        $urlBusca .= $request->ano ? '/ano-' . $request->ano : '';

        if($request->preco){
            if(count(explode('-',$request->preco)) == 1){
                $urlBusca .= '/preco-' . $request->preco . '-';    
            }
            $urlBusca .= '/preco-' . $request->preco;
        }
        
        $urlBusca .= $request->has('revenda-origem')  ? '/revenda-origem' : '';
        $urlBusca .= $request->has('particular-origem')  ? '/particular-origem' : '';
        $urlBusca .= $request->has('novo-estado')  ? '/novo-estado' : '';
        $urlBusca .= $request->has('seminovo-estado')  ? '/seminovo-estado' : '';

        $urlBusca .= $request->registrosPagina ? '?registrosPagina=' . $request->registrosPagina : '';
        echo $urlBusca;
        $client = $this->newClient();
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
        return $result;
    }

    /**
     * Retorna detalhes do veiculo escolhido
     * @param string
     * @return \Illuminate\Http\JsonResponse
     */
    public function detalhes(string $id) {
        $client = $this->newClient();
        //echo $id;
        $response = $client->request('GET',$id);
        $html = $response->getBody();

        $crawler = new Crawler((string) $html);
        $result = [];
        $result['nome'] = $crawler->filter('.mb-0')->each(function(Crawler $crawler){
            return $crawler->text();
        });
        $result['nome'] = $this->trataCrawler($result['nome'],0);

        $result['valor'] = $crawler->filter('.price')->each(function(Crawler $crawler){
            return $crawler->text();
        });
        $result['valor'] = $this->trataCrawler($result['valor'],0);

        $detalhes = ['Ano/modelo','Tipo de transmissão','Portas','Tipo de combustível','Cor do veículo','Final da placa','Aceita troca'];
        foreach($detalhes as $d) {
            $result['detalhes'][$d] = $t = $crawler->filter('[title="'. $d .'"]')->each(function(Crawler $c){
                return $c->text();
            });
        }
        
        $result['acessorios'] = $crawler->filter('.description-print')->each(function(Crawler $c){
            return $c->text();
        });

        return $result;
    }

    /**
     * retona posição desejada caso encontre array > 1
     * @param array $crawler
     * @param int $i posição que deja que retorne
     * @return string|array
     */
    private function trataCrawler(array $crawler, $i) {
        if(count($crawler) > 0) {
            return $crawler[$i];
        }
        return $crawler;
    }
}
