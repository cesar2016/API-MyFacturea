<!DOCTYPE html>
<html lang="es">
<head>
	<title>Facturea &reg;</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{ asset('my/images/icons/favicon.ico') }}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('my/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('my/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
<!--===============================================================================================-->	  
    <link href="{{ asset('my/css/util.css') }}" rel="stylesheet">
    <link href="{{ asset('my/css/main.css') }}" rel="stylesheet">
<!--===============================================================================================-->
 
 <style>
p {

    white-space: nowrap;
    overflow: hidden;    
    font-family: 'Source Code Pro', sans-serif ;  
    font-size: 28px;
    color: rgba(246, 248, 239, 0.7);
}

/* Animation */
p {
    animation: animated-text 4s steps(29,end) 1s 1 normal both,
            animated-cursor 600ms steps(29,end) infinite;
}

/* text animation */

@keyframes animated-text{
    from{width: 0;}
    to{width: 390px;}
}

/* cursor animations */

@keyframes animated-cursor{
  from{border-right-color: rgba(244, 248, 244, 0.75);}
  to{border-right-color: transparent;}
}
.autor {
     
    font-size: 13px;
}
.d-autor {
    text-align: center;
    margin-top: 10px;
}

.box {
    display: flex;
    align-items: center;
    justify-content: center;   
    
    
      }

.one {
    text-align: center;     
    margin: 5px; 
}

.two {
    text-align: center;
    margin: 5px; 
    
}

.three {
    text-align: center;
    margin: 5px;
}
 

a:hover {
  color: rgb(212, 72, 8);
}
  
.linkes {
         
    margin: 5px;
    font-size: 28px;
    padding: 5px;
    background-color: rgb(245, 245, 240);
}
 
 </style>
 
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('https://laopinion.com/wp-content/uploads/sites/3/2020/10/shutterstock_1544535644.jpg?quality=80&strip=all&w=1200');">
			<div class="wrap-login100  p-b-50">
                <p class="login100-form-title p-b-41"><img src="{{ asset('my/images/logo-inicio.png') }}" width="300" height="300" class="img-thumbnail"></p>
                <span class="login100-form-title p-b-41">
                     <p>f a c t u r e a &copy;</p>
                </span>
                <div class="box">
                    @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                                <a style="text-decoration: none;" href="{{ url('/home') }}"><span class="linkes">Home</span> </a>
                            @else
                                <a style="text-decoration: none;" href="{{ route('login') }}"> <span class="linkes">Ingresar</span></a>

                                @if (Route::has('register'))
                                    <a style="text-decoration: none;" href="{{ route('register') }}"> <span class="linkes">Registro</span></a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div> 
                <div class="d-autor">
                <a style="text-decoration: none; color: rgb(248, 158, 83)" href="https://www.linkedin.com/in/cesar-sanchez-dev/" >
                    <small class="autor">* Desarrolled By Cesar Sanchez <?php echo date('Y') ?> - ARG - &copy;</small>
                </a>
            </div>
			</div>
		</div>
	</div> 
	<div id="dropDownSelect1"></div>
 
	<script src="{{ asset('my/js/main.js') }}"></script>

</body>
</html>