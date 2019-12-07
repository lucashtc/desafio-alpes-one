<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SemiNovosModel;
use Facade\FlareClient\Http\Response;

class SemiNovosController extends Controller
{
    const contentJson = array('Content-Type' => 'application/json;charset=utf8');

    /**
     * retorna busca de veiculos
     * @return \Illuminate\Http\JsonResponse
     */
    public function busca(Request $request): \Illuminate\Http\JsonResponse {
        
        // Parametros obrigatorios
        if(!$request->veiculo){
            return response()->json(['msg' => 'veiculo não pode ser vazio'],500,self::contentJson);
        }
        $semi = new SemiNovosModel();
        $result =  $semi->filterCars($request);
        return Response()->json($result,200,self::contentJson);
    }


    /**
     * retorna json dos detalhes do produto
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detalhes(Request $request): \Illuminate\Http\JsonResponse {
        $id = $request->veiculo;
        if (!$id){
            return Response()->json(['msg' => 'id não pode ser vazio'],500,self::contentJson);
        }
        $semi = new SemiNovosModel();
        $detalhes = $semi->detalhes($id);

        return Response()->json($detalhes,200,self::contentJson);
    }
}
