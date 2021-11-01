<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Periodo extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'anio',
        'status',
        'user_id',
    ];

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }
}
