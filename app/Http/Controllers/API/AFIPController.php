<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Afip;
use App\Models\DateFisco;
use App\Models\Impuesto;
use DateTime;
use Illuminate\Http\Request;
use Faker\Provider\Barcode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Mockery\Undefined;

/*
Imprenta Autorizada AFIP.
Código 001 FACTURA A.
Código 002 NOTA DE DEBITO A.
Código 003 NOTA DE CREDITO A.
Código 004 Recibos A.
Código 005 Notas de Venta al contado A.
Código 006 Facturas B.
Código 007 Notas de Debito B.
Código 008 Notas de Credito B.
Código 011 FACTURA C
Código 012 NOTA DE DEBITO C
Código 013 NOTA DE CREDITO C
 */

// # Formato de impresion de al FACTURA
// https://groups.google.com/g/pyafipws/c/6uhybAm3ZbA/m/2Gg6k8BuAQAJ

// # Codigo Documentos AFIP 
//http://biblioteca.afip.gob.ar/pdfp/RG_100_AFIP_ART1_V2.pdf

 // # CUIT/CUIL DE PRUEBA 
  //https://www.afip.gob.ar/ws/ws_sr_padron_a4/datos-prueba-padron-a4.txt 

  // # Tipo DOC
  //$tipos_doc = array('DNI' => 96, 'LC' => 90, 'LE' => 89, 'CUIT' => 80);

class AFIPController extends Controller
{
     
    private $myCuit; // Defino el cuit Global
    private $idUser; // ID User Global
    private $tipos_doc = array(99 => 'CONSUMIDOR FINAL', 96 => 'DNI', 90 => 'LC', 89 => 'LE', 80 => 'CUIT');
    private $condicion_vta = array(1 => 'Contado', 2 => 'Cuenta Corriente', 3 => 'Cheque', 4 => 'Depesito bancario', 5 => 'Tarjeta de Cred/deb.', 6 => 'Otros');
    private $concept_vta = array(1 => 'Productos', 2 => 'Servicios', 3 => 'productos y Servicios');
    private $category_invoice = array(
        1 => 'A', 2 => 'NDA', 3 => 'NCA',
        6 => 'B', 7 => 'NDB', 8 => 'NCB',
        11 => 'C', 12 => 'NDC', 13 => 'NCC'
    );

    public function __construct()
    {        
         
        $this->middleware(function ($request, $next) { //Asi defino el cuit Global

            $idUser = Auth::user()->id;
            $datesFisco = DateFisco::where('user_id', $idUser)->get(); 

            $this->myCuit = $datesFisco[0]->cuit;
            return $next($request);
             
        });  
        
        $this->middleware(function ($request, $next) { //Asi defino el ID USER Global
 
            $this->idUser = Auth::user()->id;
            return $next($request);
        });   
          
    }
    
    public function myDatesFisco($id){
        $dates_fiscos = DateFisco::where('user_id', $id)->get();
        return $dates_fiscos;
    }

    public function create_invoice_C(Request $request){
        
        $afip = new Afip(array(

            'CUIT' => $this->myCuit, //Cuit del vendedor 
            'production' => false
            
        )); 


        $myDateComerce = DateFisco::findOrFail($this->idUser);   

        $request->date_emition == null ? $date_cte = intval(date('Ymd')) : $date_cte = $this->format_date($request->date_emition);

         
        # Este switch define que hacer cuando es producto, servicio o ambas
        switch ($request->type_concept) 
        
        {
            case 1:
                $date_init = null;
                $date_end = null;
                $date_expir = null;  
                //echo 'Entro aca es '.$request->type_concept;                              
                break;

            case 2:
            case 3:
                $date_init = $this->format_date($request->date_init);
                $date_end = $this->format_date($request->date_end);
                $date_expir = $this->format_date($request->date_expir); 
                //echo 'Entro aca es '. $request->type_concept;
                break;          
        } 

        # Este switch define que tipode comprobantes es: Factura, Nota de Credito o Nota de Debito
        switch ($this->category_invoice[$myDateComerce->code_tipo_fac]) 
        
        {
            case "A":
            case "B":
            case "C":
                $voucher = "FACTURA";                                              
                break;

            case "NDA":
            case "NDB":
            case "NDC":              
                $voucher = "NOTA DE DEBITO";
                break; 

            case "NCA":
            case "NCB":
            case "NCC":              
                $voucher = "NOTA DE CREDITO";
                break;          
        }

       
        //Devuelve el número del último comprobante creado para el punto de venta 1 y el tipo de comprobante 6 (Factura B)   
        $last_voucher = $afip->ElectronicBilling->GetLastVoucher(1, 11);
        
        $valfac = $last_voucher + 1;        

        $data = array(  
            'CantReg' 	=> 1,  // Cantidad de comprobantes a registrar
            'PtoVta' 	=> 1,//$myDateComerce->punto_venta,  // Punto de venta *
            'CbteTipo' 	=> 6,//$myDateComerce->code_tipo_fac,  // Tipo de comprobante (ver tipos disponibles) *
            'Concepto' 	=> 1,//$request->type_concept,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios *
            'DocTipo' 	=> 99,//$this->tipos_doc[$request->type_doc], // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles) *
            'DocNro' 	=> 0,//$request->indetification_client,  // Número de documento del comprador (0 consumidor final) *
            'CbteDesde' => $valfac,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno *
            'CbteHasta' => $valfac,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno *
            'CbteFch' 	=> intval(date('Ymd')),//$date_cte, //intval(date('Ymd')), (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo *
            'ImpTotal' 	=> 100, //$request->total_amount, // Importe total del comprobante * 
            'ImpTotConc'=> 0,            
            'ImpNeto' => 100, //$request->total_amount, // Importe neto gravado, al colocar este valor > que  0 tienen que exitir tambien IVA
            /*'ImpOpEx' 	=> 0,   // Importe exento de IVA
            'ImpIVA' 	=> 0,  //Importe total de IVA
            'ImpTrib' 	=> 0,   //Importe total de tributos */
            //'FchServDesde' 	=> $date_init, // (Opcional) Fecha de inicio del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
            //'FchServHasta' 	=> $date_end, // (Opcional) Fecha de fin del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
            //'FchVtoPago' 	=> $date_expir, // (Opcional) Fecha de vencimiento del servicio (yyyymmdd), obligatorio para Concepto 2 y 3   
            'MonId' 	=> 'PES',//$request->currencies_types, //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos, 'DOL' para dolares) *
            'MonCotiz' 	=> 1,     // Cotización de la moneda usada (1 para pesos argentinos)  
            
        );
        

        $res = $afip->ElectronicBilling->CreateVoucher($data);
        /* echo $res['CAE']; //CAE asignado el comprobante
        echo $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)
        echo " <br><br> ";
        echo '<pre>'; print_r($res); */
        
        // # Buscamos en la tabla de nuestra DB
        $condition_front_iva = Impuesto::findOrFail($request->id_impuesto); 

        // # codigo factura
        $myDateComerce->code_tipo_fac > 10 ? $code_type_invoice = "0".$myDateComerce->code_tipo_fac :
        $code_type_invoice = "00".$myDateComerce->code_tipo_fac; // El que va abajo de la letra en el cuadro central         
 
        $dates_invoice_c = [
            

            // # Info Izquierda:  Datos de la empresa que emite la fac.
            "razon_social" => $myDateComerce->razon_social, // del comercio que emite
            "domicilio" => $myDateComerce->domicilio_comercial, // fiscal
            "condicion_iva_vendedor" => $myDateComerce->condicion_iva, //Condicion ante el iva

            // # Info Centro: Catergoria de comprobante
            "type_invoice" => $this->category_invoice[$myDateComerce->code_tipo_fac], // A, B, C, ND, NC
            "code_type_invoice" => $code_type_invoice, // Eje A: 001 - C: 011 - NCB: 008

            // # Info Derecha, sobre el comerciante vendedor
            "type_voucher" => $voucher, // FACTURA, Nota de Cred, Nota de Deb
            "pto_venta" => "00".$myDateComerce->punto_venta,
            "numero_cvte" => 6, //$ ,
            "date_emision" => $this->format_date_normal($date_cte),
            "cuit" => $myDateComerce->cuit,
            "iibb" => $myDateComerce->IIBB,
            "date_init_actividad" => $myDateComerce->fecha_Init_actividad,

            // # Info Client
            "tipo_doc" => $this->tipos_doc[$request->type_doc], //DNi, CUIT, CUIL, etc
            "number_document" =>  $request->indetification_client, // Nº de documento
            "date_expir" => $date_expir, // fecha que vende la factura
            "firstname" => $request->firstname,
            "lastname" => $request->lastname,
            "address_comerce" => $request->address_comerce,
            "condition_iva_buy" => $condition_front_iva->description, // contado, cheque, deposito, cta cte, etc
            "condition_sale" => $this->condicion_vta[$request->condition_sale], // responsable monot, resp inscripto, etc

            // # DETALLE DE LA VENTA
            "details" => $request->items, // # El subtotal y el precio unitario se calculan en front                    
            "total_amount" => $request->total_amount, 

            // # Pie de la factura
            "cae" =>  $res['CAE'],
            "expire_cae" => $res['CAEFchVto'],
            "code_QR" => ""

        ];

        
        //echo "<pre>"; print_r($dates_invoice_c);

        return response()->json($$dates_invoice_c);
        
        
    }

    public function statusService(){ 
          
        $afip = new Afip(array( 

            'CUIT' => $this->myCuit, //Cuit del vendedor             
            'production' => true));

            $server_status = $afip->ElectronicBilling->GetServerStatus(); 

           return response()->json([
                $server_status
            ]);           
        
    }

    public function pointSale(){
        $afip = new Afip(array(

            'CUIT' => $this->myCuit, //Cuit del vendedor             
            'production' => false));

            $sales_points= $afip->ElectronicBilling->GetSalesPoints();

            return $sales_points;
    }

    public function typeVoucher(){
        $afip = new Afip(array(

            'CUIT' => $this->myCuit, //Cuit del vendedor             
            'production' => false));

            $voucher_types = $afip->ElectronicBilling->GetVoucherTypes();

            return $voucher_types;
    }

    public function typeConcepts(){
        $afip = new Afip(array(

            'CUIT' => $this->myCuit, //Cuit del vendedor             
            'production' => false));

            $concept_types = $afip->ElectronicBilling->GetConceptTypes();

            return $concept_types;
    }

    public function typeDocuments(){
        $afip = new Afip(array(

            'CUIT' => $this->myCuit, //Cuit del vendedor             
            'production' => false));

            $document_types = $afip->ElectronicBilling->GetDocumentTypes();

            return $document_types;
    }

    public function datesBussin(Request $request){
        
        if (!is_numeric($request->cuitl)){             
                         
            return response()->json([
                'msg' => 'Invalid date: '. $request->cuitl.' not have number format'
            ]); 
        }
         
        $afip = new Afip(array(

            'CUIT' => $this->myCuit, //Cuit del vendedor             
            'production' => false));

            $taxpayer_details = $afip->RegisterScopeFour->GetTaxpayerDetails($request->cuitl);

            if($taxpayer_details){
                return $taxpayer_details;
            }else{
                return response()->json([
                    'msg' => 'Ups!, Not looking dates CUIT/L'
                ]);             
                             
            }                                   
    }   

    public function barCode(){
         
        $cuitRequest  = 27-13451246-1;
        $tipo_venta = 99;
        $CAE = 71341962299440;
        $CAEFchVto = '2021-09-02';

        $cuit = str_replace("-","",$cuitRequest); 
        $tipoCompro = str_pad($tipo_venta, 2, "0", STR_PAD_LEFT); 
        $puntoVenta = str_pad(1, 4, "0", STR_PAD_LEFT); 
        $cae = $CAE; 
        $fechaVtoCae = str_replace("-","",$CAEFchVto); 

        $codigo = $cuit.$tipoCompro.$puntoVenta.$cae.$fechaVtoCae; 
        $codigoBar = $this->verificadorBase10($codigo);   
 
        //return $codigoBar;       
        return "<img height='100' src='https://barcodeapi.org/api/128/$codigoBar'>";       
         
        
    }

    // Función que genera el QR para la impresión de las facturas
	//function qr($fecha,$brandCuit,$ptoventa,$tipoCmp,$numero,$total,$moneda,$ctz,$tipoDocRec,$nroDocRec,$tipoCodAut,$codAut)
	function qr()
	{

        $cuitRequest  = $this->myCuit;
        $tipo_venta = 99;
        $CAE = 71341962299440;
        $CAEFchVto = '2021-09-02';

        $cuit = str_replace("-","",$cuitRequest); 
        $tipoCompro = str_pad($tipo_venta, 2, "0", STR_PAD_LEFT); 
        $puntoVenta = str_pad(1, 4, "0", STR_PAD_LEFT); 
        $cae = $CAE; 
        $fechaVtoCae = str_replace("-","",$CAEFchVto); 

		$version = 1;
		$array = array(
			'version' => 1,
			'fecha' => $fechaVtoCae, // Fecha CAE
			'cuit' => $cuit,
			'ptoVta' => $puntoVenta,
			'tipoCmp' => $tipoCompro,
			'nroCmp' => 10,
			'importe' => 1500,
			'moneda' => 'ARS',
			'ctz' => 1, // 1
			'tipoDocRec' => 'CUIT',
			'nroDocRec' => $cuit,
			'tipoCodAut' => 'E', // E
			'codAut' => $cae // CAE
		);
		$QR = "https://www.afip.gob.ar/fe/qr/?p=".base64_encode(json_encode($array));
        return "<img src='https://api.qrserver.com/v1/create-qr-code/?size=110x110&data=$QR'>";
	}


    public function invoice(){

        $afip = new Afip(array(

            'CUIT' => $this->myCuit, //Cuit del vendedor 
            'production' => false
            
           ));

        //Devuelve el número del último comprobante creado para el punto de venta 1 y el tipo de comprobante 6 (Factura B)   
        $last_voucher = $afip->ElectronicBilling->GetLastVoucher(1,6);

        echo $last_voucher.'<br>';
        $valfac = $last_voucher + 1;

        echo "<br>";

        $data = array(
            'CantReg' 	=> 2,  // Cantidad de comprobantes a registrar
            'PtoVta' 	=> 1,  // Punto de venta
            'CbteTipo' 	=> 6,  // Tipo de comprobante (ver tipos disponibles) 
            'Concepto' 	=> 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
            'DocTipo' 	=> 99, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
            'DocNro' 	=> 0,  // Número de documento del comprador (0 consumidor final)
            'CbteDesde' 	=> $valfac,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
            'CbteHasta' 	=> $valfac,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
            'CbteFch' 	=> intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
            'ImpTotal' 	=> 121, // Importe total del comprobante
            'ImpTotConc' 	=> 0,   // Importe neto no gravado
            'ImpNeto' 	=> 100, // Importe neto gravado
            'ImpOpEx' 	=> 0,   // Importe exento de IVA
            'ImpIVA' 	=> 21,  //Importe total de IVA
            'ImpTrib' 	=> 0,   //Importe total de tributos
            'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
            'MonCotiz' 	=> 1,     // Cotización de la moneda usada (1 para pesos argentinos)  
            'Iva' 		=> array( // (Opcional) Alícuotas asociadas al comprobante
                array(
                    'Id' 		=> 5, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles) 
                    'BaseImp' 	=> 100, // Base imponible
                    'Importe' 	=> 21 // Importe 
                )
            ), 
        );

         $res = $afip->ElectronicBilling->CreateVoucher($data);
        echo $res['CAE']; //CAE asignado el comprobante
        echo $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)

        echo '<pre>'; print_r($res);         
        
    }

    // # Barcode |||||| 
    private function verificadorBase10($codigo){
        $splitCodigo = str_split($codigo);
        $bandera = true;
        $sumaPar = 0;
        $sumaImpar = 0;
        foreach ($splitCodigo as $value) {
        switch ($bandera) {
        case true:
        $sumaImpar += $value;
        $bandera = false;
        break;
        case false:
        $sumaPar += $value;
        $bandera = true;
        break;
        }
        }
        $etapa2 = $sumaImpar * 3;
        $etapa4 = $etapa2 + $sumaPar;
        $res = ($etapa4 % 10);
        if($res == 0){
            $codigoFinal = 0;
            $cadenaFinal = ''.$codigo.$codigoFinal.'';

        }
        if($res != 0){
            $codigoFinal = 10 - $res;
            $cadenaFinal = ''.$codigo.$codigoFinal.'';
        }

        return $cadenaFinal;
    }



    // +++++++++++++++ # My Functions ++++++++++++++++++ //


    public function format_date($date)
    {         
        //$date = "2021-10-11";
        $format_date=DateTime::createFromFormat('Y-m-d', $date);
        $day=$format_date->format('d');
        $month=$format_date->format('m');
        $year=$format_date->format('Y');

        return $year.$month.$day;

    }

    public function format_date_normal($date)
    {         
        //$date = "2021-10-11";
        $format_date=DateTime::createFromFormat('Ymd', $date);
        $day=$format_date->format('d');
        $month=$format_date->format('m');
        $year=$format_date->format('Y');

        return $day."-".$month."-".$year;

    }

    public function datesPerson(Request $request){

        $identified_client = $request->cuitl;                
        
        if (!is_numeric($identified_client) && strlen($identified_client) != 11 ){             
                         
            //return response()->json(["message" => "Ups, Error en el formato del CUIT"]);
            return 1;
        }
         
        $afip = new Afip(array(

            'CUIT' => $this->myCuit, //Cuit del vendedor             
            'production' => false));

            $taxpayer_details = $afip->RegisterScopeFive->GetTaxpayerDetails($identified_client);

            if($taxpayer_details){

                if(property_exists($taxpayer_details,  'errorConstancia')){
                    return $taxpayer_details->errorConstancia->error;    
                }
                if(property_exists($taxpayer_details,  'datosGenerales')){

                    $impuestosAFIP = $taxpayer_details->datosRegimenGeneral->impuesto;

                    for ($i=0; $i < count($impuestosAFIP); $i++) { 
                        if($impuestosAFIP[$i]->idImpuesto == $request->condition_front_iva){

                            return 2; //Error

                        }else{
                            return $taxpayer_details;
                        }
                       // echo $impuestosAFIP[$i]->idImpuesto; //ASi navegamos para sacar el id de Impuesto
                    }
                }
            }

            if(!$taxpayer_details){
                return 3; //No Existe
            }

            
                                            
    }

    // # Consulta original de los clientes en el padrons
    /* public function datesPerson(Request $request){
        
        if (!is_numeric($request->cuitl)){             
                         
            return response()->json([
                'msg' => 'Invalid date: '. $request->cuitl.' not have number format"
            ]); 
        }
         
        $afip = new Afip(array(

            'CUIT' => $this->myCuit, //Cuit del vendedor             
            'production' => false));

            $taxpayer_details = $afip->RegisterScopeFive->GetTaxpayerDetails($request->cuitl);

            if($taxpayer_details){
                return $taxpayer_details;
            }else{
                return response()->json([
                    'msg' => 'Ups!, Not looking dates Persons'
                ]);             
                             
            }                                   
    } */




    
}
