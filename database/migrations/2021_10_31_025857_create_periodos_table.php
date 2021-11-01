<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodos', function (Blueprint $table) {
            $table->id();
            $table->integer('anio');

            //estado 0:no disponible, 1:disponible
            $table->tinyInteger('status')->default(1);

            //relacion a usuario
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            //lo crea el funcionario de RRHH, admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periodos');
    }
}
