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

      // Actualizar el icono usando Font Awesome en lugar de imagen
      $("#vista_ok" + id).html(

        '<i class="fa fa-check-circle" style="color: #28a745; font-size: 24px; display: inline-block; font-style: normal; font-variant: normal; text-rendering: auto; -webkit-font-smoothing: antialiased;" aria-hidden="true" title="Visto"></i>'

      );
      
      // También actualizar el estado a "Descargado" si no estaba ya
      var estadoElement = $("#estado_ok" + id);
      if (estadoElement.find('.badge').length > 0) {
        estadoElement.html(
          '<span class="badge" style="background-color: #28a745; color: #fff; padding: 6px 12px; border-radius: 4px; font-weight: 600; display: inline-block;">Descargado</span>'
        );
      }

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

