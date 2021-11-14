@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white"><h4>{{ __('Panel de control') }}</h4></div>  

                <div class="card-body">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error )
                            <div class="alert alert-danger" role="alert">
                                <li>* {{ $error }}</li>            
                            </div>
                        @endforeach
                    @endif
 
                        <h1>
                            {{ __('Formulario de actualizacion ') }}
                        </h1>
                        <strong>ACTUALIZA TUS DATOS COMERCIALES</strong>

                        <div class="jumbotron border border-info">
                            @foreach ($datesUpdates as $datesUpdate) 
                            <form method="POST" action="{{ route('create_dates.update', $datesUpdate->id) }}"> 

                                @csrf
                                @method('PATCH') 
                                
                                <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">NOMBRE DE FANTASIA</label>
                                    <input type="text" class="form-control" value="{{$datesUpdate->nombre_fantasia}}" name="nombre_fantasia" placeholder="Ej: Ferre-Fix">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">DOMICILIO COMERCIAL</label>
                                    <input type="text" class="form-control" value="{{$datesUpdate->domicilio_comercial}}" name="domicilio_comercial" placeholder="Ej: Conrad 869 Localidad...">
                                </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputAddress">RAZON SOCIAL</label>
                                    <input type="text" class="form-control" value="{{$datesUpdate->razon_social}}" name="razon_social" placeholder="Ej: Sergio Hernan Laccatier"> 
                                </div>
                                <div class="form-group">
                                    <label for="inputCity">CONDICION ANTE EL IVA</label>
                                    <select id="inputState" class="form-control" value="{{$datesUpdate->condicion_iva}}" name="condicion_iva">
                                         <option value="{{$impuestos[$datesUpdate->condicion_iva -1]->codigo}}">{{ $impuestos[$datesUpdate->condicion_iva -1]->description}}</option>
                                        @foreach ($impuestos as $impuesto)
                                            <option value="{{$impuesto->codigo}}">{{$impuesto->description}}</option> 
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">                                    
                                    <div class="form-group col-md-6">
                                        <label for="inputCity">TIPO DE FACTURA A EMITIR</label>
                                        <select id="inputState" class="form-control" value="{{$datesUpdate->code_tipo_fac}}" name="code_tipo_fac">
                                            <option value="1"
                                            @if ($datesUpdate->code_tipo_fac == 1)
                                                selected
                                            @endif
                                            >FACTURA  "A" </option> 
                                            <option value="6"
                                            @if ($datesUpdate->code_tipo_fac == 6)
                                                selected
                                            @endif
                                            >FACTURA  "B" </option> 
                                            <option value="11"
                                            @if ($datesUpdate->code_tipo_fac == 11)
                                                selected
                                            @endif
                                            >FACTURA  "C" </option>  
                                        </select>                                
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputZip">PUNTO DE VENTA</label>
                                        <input type="text" class="form-control" value="{{$datesUpdate->punto_venta}}" name="punto_venta" placeholder="Ej: 4">
                                        <small id="emailHelp" class="form-text text-muted">Solo numeros sin anteponer ceros.</small>
                                    </div>
                                </div>
                                <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputCity">CUIT</label>
                                    <input type="text" class="form-control" value="{{$datesUpdate->cuit}}" name="cuit" placeholder="Ej: 26107446339">
                                    <small id="emailHelp" class="form-text text-muted">Solo numeros sin guiones ni espacios.</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputCity">IIBB</label>
                                    <input type="text" class="form-control" value="{{$datesUpdate->IIBB}}" name="IIBB" placeholder="Ej: 000-000000-0">                                
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputZip">FECHA INIC. DE ACTIVIDAD</label>
                                    <input type="date" class="form-control" value="{{$datesUpdate->fecha_Init_actividad}}" name="fecha_Init_actividad">
                                </div>
                                </div>
                                <div class="form-group">
                                     
                                </div>
                                <button type="submit" class="btn btn-info text-white">CAMBIAR</button>
                               
                            </form>
                            @endforeach
                        </div> 
                   
                    <br>
                    <div id="msg"> 
                        <span id="loader"></span>       
                    </div>  
                                 
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){ 

        $("#btnToken").click(function(){ 
        $('#loader').append('<img src="https://www.jose-aguilar.com/scripts/jquery/loading/images/loader.gif">');              
            
            $.ajax({
                // la URL para la petición
                url : 'http://mifacturea.test/mail_token', //dev
                //url : 'http://3.21.240.248/mail_token', //Product-test
                type : 'GET',                    
                dataType : 'json', 

                success : function(data) { 
                    console.log(data)
                        
                    $('#loader').hide();
                    
                    $('#msg').append('<div id="success-alert" class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>' + data.msg + '</div>');

                },                    
                error : function(xhr, status) {
                    alert('Error!');
                },

                // código a ejecutar sin importar si la petición falló o no
                complete : function(xhr, status) {
                    console.log('End/ Query');
                }
            });
        });
    });
</script>
@endsection
