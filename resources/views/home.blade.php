@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white"><h4>{{ __('Panel de control') }}</h4></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif 
                        <h1>
                            {{ __('Bienvenido a Facturea-API ') }}
                        </h1>

                    <div class="content">
                         <button id="btnToken" type="button" class="btn btn-primary">SOLICITAR NUEVO TOKEN</button>
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
                //url : 'http://mifacturea.test/mail_token', //dev
                url : 'http://3.21.240.248/mail_token', //Product-test
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
