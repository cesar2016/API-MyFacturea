@extends('layouts.app') 

@section('content')
<div class="jumbotron"> 
 
 
    <table class="table">
        <thead>
            <tr>
            <th scope="col">#ID User</th>
            <th scope="col">Nombre Famtasi</th>
            <th scope="col">dmicilio comercial</th>
            <th scope="col">Razon Social</th>
            <th scope="col">Cond. IVA</th>
            <th scope="col">CUIT</th>
            <th scope="col">IIBB</th>
            <th scope="col">Fecha Ini. Actividad</th>
            <th scope="col">Tipo Fac</th>
            <th scope="col">Pto. Vta</th>
            <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dates_fiscos as $dates_fisco) 
            <tr>
            <th scope="row">{{ $dates_fisco->user_id }}</th>
            <td>{{ $dates_fisco->nombre_fantasia }}</td>
            <td>{{ $dates_fisco->domicilio_comercial }}</td>
            <td>{{ $dates_fisco->razon_social }}</td>
            <th>{{ $dates_fisco->condicion_iva }}</th>
            <td>{{ $dates_fisco->cuit }}</td>
            <td>{{ $dates_fisco->IIBB }}</td>
            <td>{{ $dates_fisco->fecha_Init_actividad }}</td>
            <td>{{ $dates_fisco->code_tipo_fac }}</td>
            <td>{{ $dates_fisco->punto_venta }}</td>
            <td>
                <a href="{{ url('create_dates', $dates_fisco->user_id) }}" class="btn btn-outline-primary btn-sm" role="button" aria-pressed="true"><i class="fa fa-pencil"></i></a>
                <a href="/myDates" class="btn btn-outline-danger btn-sm" role="button" aria-pressed="true"><i class=" fa fa-trash"></i></a> 

            </td>
            </tr>
        @endforeach 
        
            
        </tbody>
    </table> 
     
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
