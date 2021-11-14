@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white"><h4>{{ __('Panel de control') }}</h4></div>

                <div class="card-body">
                    
                    <h1>Hello</h1> 
                    <div id="box_provincias">
                        <select  name="provincias" id="provincias">
                            <option value="1">Álava</option>
                            <option value="2">Albacete</option>
                            <option value="3">Alicante</option>
                            <option value="4">Almería</option>
                            <option value="5">Ávila</option>
                            <option value="6">Badajoz</option>
                            <option value="7">Baleares (Illes)</option>
                            <option value="8">Barcelona</option>
                            <option value="9">Burgos</option>
                            <option value="10">Cáceres</option>
                            <option value="11">Cádiz</option>
                            <option value="12">Castellón</option>
                            <option value="13">Ciudad Real</option>
                            <option value="14">Córdoba</option>
                            <option value="15">A Coruña</option>
                            <option value="16">Cuenca</option>
                            <option value="17">Girona</option>
                            <option value="18">Granada</option>
                            <option value="19">Guadalajara</option>
                            <option value="20">Guipúzcoa</option>
                            <option value="21">Huelva</option>
                            <option value="22">Huesca</option>
                            <option value="23">Jaén</option>
                            <option value="24">León</option>
                            <option value="25">Lleida</option>
                            <option value="26">La Rioja</option>
                            <option value="27">Lugo</option>
                            <option value="28">Madrid</option>
                            <option value="29">Málaga</option>
                            <option value="30">Murcia</option>
                            <option value="31">Navarra</option>
                            <option value="32">Ourense</option>
                            <option value="33">Asturias</option>
                            <option value="34">Palencia</option>
                            <option value="35">Las Palmas</option>
                            <option value="36">Pontevedra</option>
                            <option value="37">Salamanca</option>
                            <option value="38">Santa Cruz de Tenerife</option>
                            <option value="39">Cantabria</option>
                            <option value="40">Segovia</option>
                            <option value="41">Sevilla</option>
                            <option value="42">Soria</option>
                            <option value="43">Tarragona</option>
                            <option value="44">Teruel</option>
                            <option value="45">Toledo</option>
                            <option value="46">Valencia</option>
                            <option value="47">Valladolid</option>
                            <option value="48">Vizcaya</option>
                            <option value="49">Zamora</option>
                            <option value="50">Zaragoza</option>
                            <option value="51">Ceuta</option>
                            <option value="52">Melilla</option>
                        </select>
                    </div>
                    <div id="box_localidades"></div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    
    /* $.ajax({ 
        url: "http://sitelicon.eu/test/", 
        success: function(data) { 
            //$("#resultado").html(data); 
            $('#options').html(data);
            console.log("Terminada", data); 
        }, 
    }); */

    /*
  Cuando el documento esté cargado quiero que ejecute la llamada a Ajax para que devuelva
  la respuesta en Html con el valor inicial del select y muestre el resultado en el html
*/
// Cuando se produzca el evento change del select ejecutará la llamada y mostrará el resultado

$(document).ready(function () {
  var select = $('#provincias');
    getDataUrl(select.val());
    $('#provincias').change(function () {
        getDataUrl(select.val());
    });
    });

    /*
    Para solucionar el problema de cruce de dominios distintos (CORS),
    he optado por poner un parche provisionale que es añadir delante de la url
    lo siguiente: https://cors-anywhere.herokuapp.com/.
    Quedando la url de esta manera: 'https://cors-anywhere.herokuapp.com/http://sitelicon.eu/test/ajax_localidades.php'
    */
    function getDataUrl(value) {
    $.ajax({
        data: {id: value},
        type: 'GET',
        url: 'http://sitelicon.eu/test/ajax_localidades.php',
        async: true,
        dataType: 'html',
        beforeSend: function () {
        $('#box_localidades').text('Cargando localidades...');
        },
        success: function (data) {
        $('#box_localidades').html(data);
        },
        error: function () {
        alert('No se ha podido obtener la información');
        },
    });
}
</script>
@endsection
