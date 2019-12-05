<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SemiNovosModel;

class SemiNovosController extends Controller
{
    public function test(){
        $semi = new SemiNovosModel();
        return $semi->filterCars();
    }
}
