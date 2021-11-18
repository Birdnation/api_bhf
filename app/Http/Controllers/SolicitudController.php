<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'documentacion.*' => 'mimes:pdf,jpg,jpeg,doc,docx,png',
        ]);

        $periodo = Periodo::where('anio', $request->anio)->first();
        if (!$periodo || $periodo->status === 0){
            return response()->json(["error"=>"el periodo es obligatorio"],422);
        }
        $aux = 0;
        foreach ($request->documentacion as $file)
        {
            $name = $aux.time().'-'.$request->rut_benef.'.'.$file->extension();
            $file->move(public_path('\storage\docs'), $name);
            $archivos[] = $name;
            $aux++;
        }

        $getAllUserSameRut = Solicitud::where('rut_benef', $request->rut_benef)->get();
        foreach ($getAllUserSameRut as $solicitud){
            if (($solicitud->status_dpe != 3) && ($solicitud->status_cobranza != 3) && ($solicitud->status_dge != 3)){
                if ($solicitud->periodo->status == 1)
                    return response()->json([
                        'mensaje' => 'Alumno con solicitud pendiente en sistema',
                    ]);
            }
        }

        Solicitud::create([
           'name_benef' => $request->name_benef,
           'rut_benef' => $request->rut_benef,
           'carrera_benef' => $request->carrera_benef,
            'type_benef' => $request->type_benef,
            'documentacion' => json_encode($archivos),
            'user_id' => $request->user_id,
            'periodo_id' => $periodo->id,
            'comentario_funcionario' => $request->comentario_funcionario,
        ]);

        return response()->json([
            'mensaje' => 'Solicitud creada con exito',
        ]);

    }

    public function createEspecial (Request $request){

        $request->validate([
            'name_benef' => 'string|required',
            'rut_benef' => 'string|required',
            'carrera_benef' => 'string|required',
            'type_benef' => 'required',
            'documentacion.*' => 'mimes:pdf,jpg,jpeg,doc,docx,png',
            'rut_funcionario' => 'required',
            'email_funcionario' => 'email|required|unique:users,email',
            'name_funcionario' => 'required',
            'fono_funcionario' => 'required',
            'tipo_estamento' => 'required'
        ]);

        $periodo = Periodo::where('anio', $request->anio)->first();
        if (!$periodo || $periodo->status === 0){
            return response()->json(["error"=>"el periodo es obligatorio"],422);
        }
        $aux = 0;
        foreach ($request->documentacion as $file)
        {
            $name = $aux.time().'-'.$request->rut_benef.'.'.$file->extension();
            $file->move(public_path('\storage\docs'), $name);
            $archivos[] = $name;
            $aux++;
        }

        $getAllUserSameRut = Solicitud::where('rut_benef', $request->rut_benef)->get();
        foreach ($getAllUserSameRut as $solicitud){
            if (($solicitud->status_dpe != 3) && ($solicitud->status_cobranza != 3) && ($solicitud->status_dge != 3)){
                if ($solicitud->periodo->status == 1)
                    return response()->json([
                        'mensaje' => 'Alumno con solicitud pendiente en sistema',
                    ]);
            }
        }

        $getUser = User::where('rut', $request->rut_funcionario)->first();
        if (!$getUser){
            $getUser = User::create([
                'name' => strtoupper($request->name_funcionario),
                'email' => $request->email_funcionario,
                'rut' => $request->rut_funcionario,
                'telefono' => $request->fono_funcionario,
            ]);
        } else {
            $getUser->name = strtoupper($request->name_funcionario);
            $getUser->email = $request->email_funcionario;
            $getUser->telefono = $request->fono_funcionario;
            $getUser->save();
        }

        Solicitud::create([
            'name_benef' => $request->name_benef,
            'rut_benef' => $request->rut_benef,
            'carrera_benef' => $request->carrera_benef,
            'type_benef' => $request->type_benef,
            'documentacion' => json_encode($archivos),
            'user_id' => $getUser->id,
            'periodo_id' => $periodo->id,
            'comentario_funcionario' => $request->comentario_funcionario,
            'comentario_dpe' => $request->comentario_dpe,
            'tipo_estamento' => $request->tipo_estamento,
            'status_dpe' => 2,
        ]);

        return response()->json([
            'mensaje' => 'Solicitud creada con exito',
        ]);
    }

    public function showSolicitudAuth (Request $request){
        $user = Auth::user();
        $perPage = request('limit', 5);
        return response()->json($user->solicitudes()->orderBy('created_at', 'DESC')->paginate($perPage));
    }

    public function getSolicitud (String $id){
        $getSolicitud = Solicitud::where('id', $id)->with('user')->with('periodo')->first();
        return response()->json($getSolicitud);
    }

    public function getAllSolicitudDPE(Request $request){
        $perPage = request('limit', 5);
        return response()->json(User::with('solicitudesPendientesDPE')->paginate($perPage));
    }

    public function cambiarEstadoDPE (String $id, Request $request){
        $getSolicitud = Solicitud::where('id', $id)->first();
        if (!$getSolicitud){
            return response()->json(['mensaje'=> 'no existe solicitud con id '.$id]);
        }else{
            $getSolicitud->status_dpe = $request->status_dpe;
            $getSolicitud->comentario_dpe = $request->comentario_dpe;
            $getSolicitud->tipo_estamento = $request->tipo_estamento;
            $getSolicitud->save();
            return response()->json(['mensaje'=> 'cambio con exito']);
        }
    }
}
