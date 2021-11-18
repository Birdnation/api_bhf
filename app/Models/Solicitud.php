<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Solicitud extends Model
{
    use HasFactory, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name_benef',
        'rut_benef',
        'carrera_benef',
        'type_benef',
        'status_dpe',
        'status_cobranza',
        'status_dge',
        'documentacion',
        'comentario_funcionario',
        'comentario_dpe',
        'comentario_cobranza',
        'comentario_dge',
        'user_id',
        'periodo_id',
        'tipo_estamento',
        'estado_curricular'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }
}
