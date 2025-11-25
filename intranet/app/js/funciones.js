function visto(ruta, id) {

 



  var datos = {

    id: id,

    pro_visto:'ok'

  };

  

  $.ajax({

    type: "POST",

    url: ruta + "ordenes",

    data: datos,

    dataType: "html",

    success: function (data) {

     

      $("#vista_ok" + id).html(

        '<img src="' + ruta + 'admin/publico/img/check.png" width="30">'

      );

    },

  });

}



function estado(ruta, id,estado) {

 

  var datos = {

    id: id,

    estado: estado,

    pro_estado:'ok'

  };

  

  $.ajax({

    type: "POST",

    url: ruta + "ordenes",

    data: datos,

    dataType: "html",

    success: function (data) {

     

      if(estado=='1'){

        

        $("#estado_ok" + id).html(

          '<div class="btn btn-outline-success btn-sm" style="color: #5cb85c; border:solid 1px #5cb85c"> APROBADO</div>'

        );

      }else{

         

        $("#estado_ok" + id).html(

          '<div class="btn btn-outline-success btn-sm" style="color: #d9534f; border:solid 1px #d9534f;"> OBSERVADO</div>'

        );

      }

     



    },

  });

}

