<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\Request;

class PeriodoController extends Controller
{
    public function create(Request $request)
    {
        //logica para validar un periodo

        //logica para crear un periodo

        //logica para retornar un codigo 201
    }

    public function getPeriodos (Request $request){

        $periodoActivo = Periodo::where('status', 1)->get();

        return response()->json($periodoActivo);

    }


}
