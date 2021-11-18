<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicituds', function (Blueprint $table) {
            $table->id();
            $table->string('name_benef');
            $table->string('rut_benef');
            $table->string('carrera_benef');
            $table->enum('type_benef',['nuevo', 'antiguo']);
            $table->enum('tipo_estamento',['academico','no academico','ex funcionario','excepcion especial'])->nullable();
            $table->enum('estado_curricular',['matriculado', 'no matriculado'])->nullable();
            //estado 0:recepcionado, 1:Visualizado 2:Aprobado, 3:Rechazado 4:pendiente 5:sin estado
            $table->tinyInteger('status_dpe')->default(0);
            $table->tinyInteger('status_cobranza')->default(5);
            $table->tinyInteger('status_dge')->default(5);

            $table->json('documentacion')->nullable();
            $table->string('comentario_funcionario')->nullable();
            $table->string('comentario_dpe')->nullable();
            $table->string('comentario_cobranza')->nullable();
            $table->string('comentario_dge')->nullable();

            //relacion con users
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('solicituds');
    }
}
