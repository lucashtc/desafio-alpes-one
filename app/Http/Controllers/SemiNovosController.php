<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SemiNovosModel;

class SemiNovosController extends Controller
{
    public function busca(Request $request){
        
        // Parametros obrigatorios
        if(!$request->veiculo){
            return response()->json(['msg' => 'veiculo não pode ser vazio'],500);
        }
        $semi = new SemiNovosModel();
        return $semi->filterCars($request);
    }
}
