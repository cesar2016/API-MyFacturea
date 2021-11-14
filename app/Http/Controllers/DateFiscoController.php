<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DateFisco;
use App\Models\Impuesto;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Contracts\Service\Attribute\Required;

class DateFiscoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idUser = Auth::user()->id; 
         
        $dates_fiscos = DateFisco::where('user_id', $idUser)->get(); 
        return view('/myDates', ['dates_fiscos' => $dates_fiscos]);  

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {          

        $request->validate([

            "nombre_fantasia" => "required",
            "domicilio_comercial" => "required",
             "razon_social" => "required",
            "condicion_iva" => "required",
            "cuit" => "required|unique:date_fiscos,cuit|integer|digits:11",
            "IIBB" => "required",
            "fecha_Init_actividad" => "required",
            "code_tipo_fac" => "required",
            "punto_venta" => "required"
        ]);

        $idUser = Auth::user()->id;
 
        $dates = new DateFisco();      

        $dates->user_id = $idUser;
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

            return redirect()->back()->with('success', 'Bien! registro creado con exito!');
            

        }else{
            return response()->json(['msg'=>'Error update dates']); 
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
        $impuestos = Impuesto::all(); 
        $datesUpdates = DateFisco::findOrFail($id)->get();
        
        return view('editDates', ["impuestos" => $impuestos], ["datesUpdates" => $datesUpdates]);
        
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

        $request->validate([

            "nombre_fantasia" => "required",
            "domicilio_comercial" => "required",
             "razon_social" => "required",
            "condicion_iva" => "required",
            "cuit" => "required|integer|digits:11",
            "IIBB" => "required",
            "fecha_Init_actividad" => "required",
            "code_tipo_fac" => "required",
            "punto_venta" => "required"
        ]);

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

            return redirect('create_dates');
            

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
