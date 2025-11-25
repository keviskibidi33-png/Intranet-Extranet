<?php 

$date2 = new DateTime($m_gastos_furgones_active['fecha']);

$fecha_gf2= $date2->format('d/m/Y')

?>

<?php echo  $fecha_gf2?>



<?php session_start();?>



<a href="<?php echo ruta?>admin/publico/img/foto.jpg" data-fancybox ><img src="<?php echo ruta?>admin/publico/img/foto.jpg" width="300" /></a>





<?php print_r($iden)?><hr>



<?php echo $iden[3]?>



<?php for($i=1;$i<=5;$i++){ ?> <?php }?>





<?php



echo abs(-5);



print_r($_GET);



print_r($_POST);





$cadena_fecha =$res['fecha_vencimiento']; echo $newDate = date("d/m/Y", strtotime($cadena_fecha));





$foto=$_FILES["foto"]["name"];

$temp=$_FILES["foto"]["tmp_name"];

$tamano=$_FILES["foto"]["size"];

$tipo=$_FILES["foto"]["type"];

////////////////codigo///////////////////////

$codigo_v= substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,50);



 $codigo= substr(str_shuffle(str_repeat('0123456789',5)),0,7);



////////////fin////codigo///////////////////////



?>

<?php echo $url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>



<script>



</script>



<?php 

header('Location: sub_paqueteslist.php');

exit;

?>



<script>

top.document.location.href="imprimir_b.php?nv=<?php echo $id ?>";

top.document.location.href="index.php";

window.history.back();

location.reload(); 

location.replace("<?php echo $_POST["url"];?>");

javascript:history.back() 

onclick="location.href='unit_01.htm'"

</script>



<a href="javascript:history.back()" target="_top"></a>



 <input value="<?php echo $url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" name="url" type="hidden">





<?php

//////////////input:radio[name=color]

?>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>



<script>

$(document).ready(function(){  

  

$("#formu_footer").submit(function () {  



		

if($("#nombre").val().length < 4) {  

Swal.fire({

  title: 'Alerta',

  text: "Escriba su nombre",

  type: 'error',

  confirmButtonColor: '#1ad5ba',

  confirmButtonText: 'Entendido'

})

$('#nombre').focus();

return false;  

}

 

 if($("#correo").val().length < 1) {  

Swal.fire({

  title: 'Alerta',

  text: "Escriba su correo",

  type: 'error',

  confirmButtonColor: '#1ad5ba',

  confirmButtonText: 'Entendido'

}) 

$('#correo').focus();

return false;  

 }



if($("#correo").val().indexOf('@', 0) == -1 || $("#correo").val().indexOf('.', 0) == -1) {  

Swal.fire({

  title: 'Alerta',

  text: "Correo invalido",

  type: 'error',

  confirmButtonColor: '#1ad5ba',

  confirmButtonText: 'Entendido'

}) 

$('#correo').focus();

return false;   

}

		

if($("#telefono").val().length < 4 || isNaN($("#telefono").val())) {  

Swal.fire({

  title: 'Alerta',

  text: "Escriva un teléfono valido",

  type: 'error',

  confirmButtonColor: '#1ad5ba',

  confirmButtonText: 'Entendido'

}) 

$('#correo').focus();

return false;  

}



if($("#tc").is(':checked')) { } else {  

Swal.fire({

  title: 'Alerta',

  text: "Acepte los términos y condiciones",

  type: 'error',

  confirmButtonColor: '#1ad5ba',

  confirmButtonText: 'Entendido'

}) 

$('#tc').focus();

return false;    

}     

	 



}); 

 

});  

</script>



<script>

$("#formulario").submit(function () {  

      		 if($("#nombre").val().length < 4) {  

            //$(".aler_nom").show() 

			$(".aler_nom").show();

			$(".aler_nom").delay(3000).fadeOut(600);

        	 return false;  

        	}  

		});



</script>

<script>

 $(document).ready(function(){  

    $("#formulario").submit(function () {  

        if($("#nom").val().length < 4) {  

            alert("El nombre debe tener más de 3 caracteres");  

            return false;  

        }  

        if($("#ape").val().length < 4) {  

            alert("Los apellidos deben tener más de 3 caracteres");  

            return false;  

        }  

        if($("#tel").val().length < 4 || isNaN($("#tel").val())) {  

            alert("El teléfono debe tener más de 3 caracteres y solo números");  

            return false;  

        }  

        if($("#cor").val().length < 1) {  

            alert("La dirección e-mail es obligatoria");  

            return false;  

        }  

        if($("#cor").val().indexOf('@', 0) == -1 || $("#cor").val().indexOf('.', 0) == -1) {  

            alert("La dirección parece incorrecta");  

            return false;  

        }  

        if($("#localidad").val().length < 1) {  

            alert("La localidad es obligatoria");  

            return false;  

        }  

        if($("#provincia option:selected").val() == "") {  

            alert("La provincia es obligatoria");  

            return false;  

        }  

        if($("#localidad").val().length < 1) {  

            alert("La localidad es obligatoria");  

            return false;  

        }  

        if($("#boletin").is(':checked')) { } else {  

            alert("Indique si desea apuntarse al boletín de noticias");  

            return false;  

        }  

        if($("#visitas").is(':checked')) { } else {  

            alert("Indique cada cuanto nos visitas");  

            return false;  

        }  

        return false;  

    });  

});  

</script>



<?php 

//////////////////////////////contar numeros



 $uo = mysql_num_rows($sed); {

		





if($uo=='0'){

	

	?>

	

	

	<?php

}else{

	

	

	

	

	

	}}

	

?>











 <?php

 

    $uo = mysql_num_rows($sed); 

	if($uo=='0'){}

	

	

	 ?>

     

 <!-- desactivar el vton enter   -->

     <script type="text/javascript">

$(document).ready(function() {

    $("form").keypress(function(e) {

        if (e.which == 13) {

            return false;

        }

    });

});

</script>

     

     

     

     <script>

$(document).ready(function() {

$('.editar_color').submit(function(event){

		

		

event.preventDefault();

		

var id=$(this).attr("id");	

var  titulo = $('#titulo'+id).val();

var  color = $('#color'+id).val();



$.ajax({

type: "POST",

url: "pro_edi_autotarea.php",

data: "id="+id+"&titulo="+titulo+"&color="+color, 

dataType: "html",

success: function(data){                   

	$("#carga_autotareas").load('carga_autotarea.php?idus=<?php $_GET['idus']?>')                                     

}

});





});

});   

			

</script>

<script>

 $('.open_po_eli').click(function(){

			 

			var id=$(this).attr("id");

			$('#po_eli'+id).fadeIn();

			$('#fondoeli'+id).fadeIn();

		});

</script>



<script>



$(document).ready(function() {

    $('#guardar_cu').click(function(){



$.ajax({

                    type: "POST",

                    url: "pro_nueva_cuenta.php",

                    data: "id="+id+"detalle"+detalle+"notificar"+notificar+"f_final"+f_final,

                    dataType: "html",

                    beforeSend: function(){

                          //imagen de carga

                          $(".resultado").html("<p align='center'><img src='images/p.gif' /></p>");

                    },

                    error: function(){

                          alert("error petición ajax");

                    },

                    success: function(data){                                                    

                         // $(".resultado").empty();

                         

                                                             

                    }

       });

	     });

		   });

		   

			

</script>





 setInterval(function(){

	

	$('#ver_video').slideDown()	

	}, 4000);





<script>

 $(document).ready(function(){ 

 

  

    $("#formu_markana").submit(function () {  

        if($("#for_nom").val().length < 4) {  

           $('#for_nom').css({"border":"solid 1px #FF0004"});

		   $('#for_nom').css({"background-color":"#ffeded"});

            return false;  

        }else{

			 $('#for_nom').css({"border":"solid 1px #d7d7d7"});

			 $('#for_nom').css({"background-color":"#fff"});

			}  

        

		

		if($("#for_ape").val().length < 4) {  

           $('#for_ape').css({"border":"solid 1px #FF0004"});

		   $('#for_ape').css({"background-color":"#ffeded"});

            return false;  

        }else{

			 $('#for_ape').css({"border":"solid 1px #d7d7d7"});

			 $('#for_ape').css({"background-color":"#fff"});

			}  

        

        

        if($("#for_cor").val().length < 1) {  

       $('#for_cor').css({"border":"solid 1px #FF0004"});

	   $('#for_cor').css({"background-color":"#ffeded"});

            return false;  

        }else{

	 $('#for_cor').css({"border":"solid 1px #d7d7d7"});

	 $('#for_cor').css({"background-color":"#fff"});

			} 

			

			   

        if($("#for_cor").val().indexOf('@', 0) == -1 || $("#for_cor").val().indexOf('.', 0) == -1) {  

     $('#for_cor').css({"border":"solid 1px #FF0004"});

	 $('#for_cor').css({"background-color":"#ffeded"});

            return false;  

        } else{

			 $('#for_cor').css({"border":"solid 1px #d7d7d7"});

			 $('#for_cor').css({"background-color":"#fff"});

			}   

		

		

		

        if($("#for_tel").val().length < 4 || isNaN($("#for_tel").val())) {  

           $('#for_tel').css({"border":"solid 1px #FF0004"});

		   $('#for_tel').css({"background-color":"#ffeded"});

            return false;  

        } else{

			 $('#for_tel').css({"border":"solid 1px #d7d7d7"});

			 $('#for_tel').css({"background-color":"#fff"});

			}   

			

			

        

			

     

    });  

});  

</script>















<div style=" background-position:center; background-size:cover;width:200px; height:300px; background-image:url(http://ximg.es/500/09f/fff.png)"></div>







<?php $texto=strip_tags($blog['detalle']);



if (mb_strlen($texto) > 60){

	

  echo mb_substr($texto,0,60,'utf-8').'... +';

}else{

  echo mb_substr($texto,0,60,'utf-8');

}

?>





<style>



@font-face {

    font-family:"AlegreSans";

    src: url(../Fonts/AlegreSans-Regular.ttf);

}

  

  

  

.otro_placeholder::-webkit-input-placeholder,

.area_pasarela::-webkit-input-placeholder

{

  color:    #fff;

}



.otro_placeholder:-moz-placeholder,

.area_pasarela:-moz-placeholder 

{

  color:    #fff;

}



.otro_placeholder::-moz-placeholder,

.area_pasarela::-moz-placeholder 

{

  color:    #fff;

}



.otro_placeholder:-ms-input-placeholder,

.area_pasarela:-ms-input-placeholder 

{

  color:    #fff;

}



  

  </style>

  

  

<?php if(mysql_num_rows($sqldes)=='0'){?>





<div class="alert alert-danger" role="alert">...</div>





<div style=" width:100%;background-color:#FAFDFF; border:solid 1px #D8F0FF; padding:5px; margin:5px 0 0 0; border-radius:5px; color:#666 ; font-size:18px">  



       <div style=" display:block; width:100%">No Destinos en esta Categoría</div>

     </div>

<?php 

	



}

  

  ?>

  



  

  <script type="text/javascript">

$(document).on("ready",function(){

	$("#ir").on("click", function(){

		

		

		

		$("html,body").animate({ scrollTop : $("#destino").offset().top  }, 1500 );

		

		

			});

});

</script>







<script>

$(function () {

$(window).scroll(function () {

if (

scrollTop : $("#destino").offset().top

{	



$('body').hide()

				    



}else{

    $('.nav').removeClass('sticky'); 

		$('body').show()

	} 

});

});









$(function () {

$(window).scroll(function () {

if ($(this).scrollTop() >390) {	



$('body').hide()

				    



}else{

    $('.nav').removeClass('sticky'); 

		$('body').show()

	} 

});

});



</script>



<script>





function sco(){

if ($(this).scrollTop() >90) {	

/////////////////////////////////////////////

$('.btn_menu').addClass('fix')

$('.conte_fix_header').addClass('active')

/////////////////////////////////////////////			    

}else{

/////////////////////////////////////////////

$('.btn_menu').removeClass('fix')

$('.conte_fix_header').removeClass('active')

/////////////////////////////////////////////

}}

sco()

$(window).scroll(function () {

sco()

});



</script>





<script>



$("#res").text('todo ok').slideDown('slow').delay(2000).fadeOut();



</script>





<?php 



 $fecha=$nov['fecha'];

$test = explode("/",$fecha); 

  

 $y = $test[0]; 

 $m = $test[1]; 

  $d = $test[2]; 





$sqlfee=mysql_query("SELECT

 *

FROM

  `meses` where id = '$m'

");



 while($fee=mysql_fetch_array($sqlfee)){ 

 

 $me=$fee['mes'];

$date = "$d de $me del $y"; 

 echo"$date"; 

}





?>



<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

     <input name="telefono" type="text"  id="tel" size="50" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required/>

     

     

     <input type="text" pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" name="email" required/>

     

     

     

     <input class="email" type="text" name="email" value="Escriba su Correo" onfocus="if (this.value == 'Escriba su Correo') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Escriba su Correo';}" required />

     

     

     

    <style>

	

	

/* Let's get this party started */

::-webkit-scrollbar {

    width: 10px;

}

 

/* Track */

::-webkit-scrollbar-track {

    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 

    -webkit-border-radius: 10px;

    border-radius: 10px;

}

 

/* Handle */

::-webkit-scrollbar-thumb {

    -webkit-border-radius: 10px;

    border-radius: 10px;

    background: #428bca; 

    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 

}

::-webkit-scrollbar-thumb:window-inactive {

	background:#5CA2DE; 

}

	

	

	

	

	

	

	

	

	

	.grises{



-moz-filter: grayscale(100%);

-o-filter:grayscale(100%);

-ms-filter:grayscale(100%);

filter: grayscale(100%);

-webkit-filter: grayscale(100%); /* For Webkit browsers */

filter: gray; /* For IE 6 - 9 */

-webkit-transition: all .6s ease; /* Fade to color for Chrome and Safari */

filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale"); /* Firefox 10+, Firefox on Android */

-webkit-transition: all 0.5s ease;

-moz-transition: all 0.5s ease;

-ms-transition: all 0.5s ease;

-o-transition: all 0.5s ease;

transition: all 0.5s ease;

}

.grises:hover { 

-webkit-filter: grayscale(0%);

-moz-filter: grayscale(0%);

-ms-filter: grayscale(0%);

-o-filter: grayscale(0%);

filter: none;



-webkit-transition: all 0.5s ease;

-moz-transition: all 0.5s ease;

-ms-transition: all 0.5s ease;

-o-transition: all 0.5s ease;

transition: all 0.5s ease;









}

	

	

	

	

.trancicion_hover{	

-webkit-transition-duration: 0.8s;

-moz-transition-duration: 0.8s;

-o-transition-duration: 0.8s;

transition-duration: 0.8s;	

	

}

	

	</style> 

     

     

     

     

     

  <?php 



 $fecha=$pr['fecha'];

$test = explode("/",$fecha); 

  

 $y = $test[0]; 

 $m = $test[1]; 

  $d = $test[2]; 





$sqlfee=mysql_query("SELECT

 *

FROM

  `meses` where id = '$m'

");



 while($fee=mysql_fetch_array($sqlfee)){ 

 

 $me=$fee['mes'];

$date = "$d de $me del $y"; 

 echo"$date"; 

}





?>   

     

     

  

  

     

   <script>  

     

     

     $(".conte_caru").hover(function(){

    

       $(this).children('.caru_mas').fadeToggle();

		 

    });	

	

	</script>

    

    

    

    <?php 

	///convertir guion a slach

	$cadena=str_replace("-","/",$cadena);

	?>

    

    

    

      

   <script>  	

	  function validateForm() {

    var x = document.forms["myForm"]["selec"].value;

    if (x == null || x == "") {

        alert("Elige una Categoría");

        return false;

    }

}



///////////////////////////





$('.nav .bt_dos').click(function(){

    $('.bt_dos').removeClass("bt_dos_ative");

    $(this).addClass("bt_dos_ative");

});













	</script>   

    

   

  

  

  <?php 

  $url_head=ruta;

  $titulo_head = $mostrar_producto['titulo'];

  $detalle_head = strip_tags($mostrar_producto['detalle']);

  $img_head = ruta . "admin/publico/img_data/" . $mostrar_producto['img'];

  ?>

<!-- web -->

  <meta name="title" content="<?php echo $titulo_head; ?>" />

  <meta name="description" content="<?php echo $detalle_head; ?>" />

  <link rel="image_src" href="<?php echo $img_head ?>" />

  <meta name="robots" content="noarchive">



  <!-- facebook -->

  <meta property="og:url" content="<?php echo ruta ?>?pagina=ayuda&b=<?php echo $_GET['b'] ?>" />

  <meta property="og:type" content="article" />

  <meta property="og:title" content="<?php echo $titulo_head; ?>" />

  <meta property="og:description" content="<?php echo $detalle_head; ?>" />

  <meta property="og:image" content="<?php echo $img_head; ?>" />

  <meta property="og:site_name" content="<?php echo $url_head; ?>" />



  <!-- Meta Twitter -->

  <meta name="twitter:card" content="summary" />

  <meta name="twitter:title" content="<?php echo $titulo_head; ?>">

  <meta name="twitter:description" content="<?php echo $detalle_head; ?>">

  <meta name="twitter:image" content="<?php echo $img_head; ?>">



  <!-- Meta Google+ -->

  <meta itemprop="name" content="<?php echo $titulo_head; ?>">

  <meta itemprop="description" content="<?php echo $detalle_head; ?>">

  <meta itemprop="image" content="<?php echo $img_head; ?>">









<script>





 onclick="window.open('some.htm','_blank');"



</script>







 <input type="text" class="form-control" id="search" placeholder="Filtrar por Apellido" list="bus_ape" >

            <datalist id="bus_ape">

             

             <select size="10">



             <option value="uno">

              <option value="dos">

         

            </select></datalist>

            

            

           

           

           

           

           

           <div id="google_translate_element"></div><script type="text/javascript">

function googleTranslateElementInit() {

  new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'en,es,fr,it,ja,ru,zh-TW', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');

}

</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>



testin 

http://www.intodns.com/





