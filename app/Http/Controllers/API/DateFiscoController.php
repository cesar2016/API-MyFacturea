<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DateFisco;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DateFiscoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {          
        
        $userId =  $request->user()->id;

        $dates = new DateFisco();      

        $dates->user_id = $userId;
        $dates->nombre_fantasia = $request->nombre_fantasia;
        $dates->domicilio_comercial = $request->domicilio_comercial;
        $dates->razon_social = $request->razon_social;
        $dates->condicion_iva = $request->condicion_iva;
        $dates->cuit = $request->cuit;
        $dates->IIBB = $request->IIBB;
        $dates->fecha_Init_actividad = $request->fecha_Init_actividad;
        $dates->code_tipo_fac = $request->code_tipo_fac;
        $dates->punto_venta = $request->punto_venta;

        $datesInsert = $dates->save();

        if($datesInsert == true){
            return response()->json(['msg'=>'Succes Insert Dates']);
        }else{
            return response()->json(['msg'=>'Error Insert dates']); 
        }
 
         
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return DateFisco::findOrFail($id);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $userId =  $request->user()->id;

        $datesFisco = DateFisco::select()
        ->where('id', $id)
        ->where('user_id', $userId)
        ->update([
            "nombre_fantasia" => $request->nombre_fantasia,
            "domicilio_comercial" => $request->domicilio_comercial,
            "razon_social" => $request->razon_social,
            "condicion_iva" => $request->condicion_iva,
            "cuit" => $request->cuit,
            "IIBB" => $request->IIBB,
            "fecha_Init_actividad" => $request->fecha_Init_actividad,
            "code_tipo_fac" => $request->code_tipo_fac,
            "punto_venta" => $request->punto_venta,

        ]);        
        

        if($datesFisco == true){
            return response()->json(['msg'=>'Succes upadate Dates']);
        }else{
            return response()->json(['msg'=>'Error update dates']); 
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
