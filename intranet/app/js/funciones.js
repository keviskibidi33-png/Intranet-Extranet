function visto(ruta, id) {
  // Prevenir múltiples llamadas
  if (window.vistoProcesando && window.vistoProcesando[id]) {
    return;
  }
  window.vistoProcesando = window.vistoProcesando || {};
  window.vistoProcesando[id] = true;

  var datos = {
    id: id,
    pro_visto: 'ok'
  };

  $.ajax({
    type: "POST",
    url: ruta + "ordenes",
    data: datos,
    dataType: "html",
    success: function (data) {
      // Actualizar el icono usando Font Awesome en lugar de imagen
      var iconoHtml = '<i class="fa fa-check-circle" style="color: #28a745; font-size: 24px; display: inline-block;" aria-hidden="true" title="Visto"></i>';
      $("#vista_ok" + id).html(iconoHtml);
      
      // También actualizar el estado a "Descargado" si no estaba ya
      var estadoElement = $("#estado_ok" + id);
      if (estadoElement.length > 0) {
        // Verificar si tiene badge o cualquier contenido
        var contenidoActual = estadoElement.html().trim();
        // Actualizar si contiene "No leído" o cualquier badge rojo
        if (contenidoActual.indexOf('No leído') !== -1 || 
            contenidoActual.indexOf('badge') !== -1 || 
            contenidoActual.indexOf('dc3545') !== -1 ||
            contenidoActual.indexOf('OBSERVADO') !== -1) {
          estadoElement.html(
            '<span class="badge" style="background-color: #28a745; color: #fff; padding: 6px 12px; border-radius: 4px; font-weight: 600; display: inline-block;">Descargado</span>'
          );
        }
      }
      
      // Liberar el bloqueo después de un breve delay
      setTimeout(function() {
        if (window.vistoProcesando) {
          delete window.vistoProcesando[id];
        }
      }, 1000);
    },
    error: function(xhr, status, error) {
      console.error('Error al marcar como visto:', error);
      // Liberar el bloqueo en caso de error
      if (window.vistoProcesando) {
        delete window.vistoProcesando[id];
      }
    }
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

