<?php

namespace App\Http\Controllers;

use App\Models\DateFisco;
use App\Models\Impuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $idUser = Auth::user()->id;

        $impuestos = Impuesto::all(); 
        $dates = DateFisco::where('user_id', $idUser)->first();

        return view('home', ['impuestos' => $impuestos], ['dates' => $dates]);
    }
}
