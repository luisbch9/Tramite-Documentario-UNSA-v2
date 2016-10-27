<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTramitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('area_id')->unsigned()->nullable()->default(NULL);
            $table->integer('empleado_id')->unsigned()->nullable()->default(NULL);
            $table->integer('persona_id')->unsigned()->nullable()->default(NULL);
            $table->boolean('entregado');
            $table->integer('prioridad');
            $table->timestamps();

            $table->foreign('area_id')
                ->references('id')
                ->on('areas');

            $table->foreign('empleado_id')
                ->references('id')
                ->on('empleados')->onDelete('cascade')->onUpdate('cascade');
                

            $table->foreign('persona_id')
                ->references('id')
                ->on('personas');



        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tramites');
    }
}