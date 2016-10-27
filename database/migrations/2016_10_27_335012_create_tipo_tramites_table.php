<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoTramitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_tramites', function (Blueprint $table) {
            $table->increments('id');
            $table->text('nombre');
            $table->integer('tramite_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('tramite_id')
               ->references('id')
               ->on('tramites');
            
        });

        
          
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_tramites');
    }
}