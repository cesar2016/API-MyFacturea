<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateFiscosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_fiscos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); 
            $table->string('nombre_fantasia');
            $table->string('domicilio_comercial');
            $table->string('razon_social');
            $table->string('condicion_iva');
            $table->string('cuit');
            $table->string('IIBB');
            $table->string('fecha_Init_actividad');
            $table->integer('code_tipo_fac');
            $table->integer('punto_venta');

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
        Schema::dropIfExists('date_fiscos');
    }
}
