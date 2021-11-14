<?php

namespace App\Http\Controllers;

use App\Models\Impuesto;
use Illuminate\Http\Request;

class TraineeController extends Controller
{
    /**
     * @Route("Route", name="RouteName")
     */
    public function operation() //Guarda los codigos de los IMPUESTOS
    {       

        for ($i=1; $i <= 14; $i++) {           
            $imp =  Impuesto::select()->where('id', $i);
            $imp->update([ 'codigo' => $i]);
        };
              


    }
    public function prueba() //Guarda los codigos de los IMPUESTOS
    {       

        for ($i=1; $i <= 14; $i++) {           
             
            return response()->json(['msg' => 'Testing Code']);
        };
              


    }
}
