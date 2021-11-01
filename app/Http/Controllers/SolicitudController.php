<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{

    /**
     * @param Request $request
     * Parametros obligatorios en el request:
     * name_benef, rut_benef, carrera_benef, type_benef, documentacion, user_id, anio
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'name_benef' => 'string|required',
            'rut_benef' => 'string|required',
            'carrera_benef' => 'string|required',
            'type_benef' => 'required',
            'documentacion.*' => 'mimes:pdf,jpg,jpeg,doc,docx',
        ]);

        $periodo = Periodo::where('anio', $request->anio)->first();
        $aux = 0;
        foreach ($request->documentacion as $file)
        {
            $name = $aux.time().'-'.$request->rut_benef.'.'.$file->extension();
            $file->move(public_path('\storage\docs'), $name);
            $archivos[] = $name;
            $aux++;
        }

        Solicitud::create([
           'name_benef' => $request->name_benef,
           'rut_benef' => $request->rut_benef,
           'carrera_benef' => $request->carrera_benef,
            'type_benef' => $request->type_benef,
            'documentacion' => json_encode($archivos),
            'user_id' => $request->user_id,
            'periodo' => $periodo->id,
        ]);

        return response()->json([
            'mensaje' => 'Solicitud creada con exito',
        ]);

    }
}
