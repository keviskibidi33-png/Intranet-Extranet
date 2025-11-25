 
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////

function eliminar_todo(ruta, id, que_eliminar) {
  Swal.fire({
    icon: "error",
    title: "Alerta",
    text: "Estas seguro de eliminar",
    showCloseButton: true,
    showCancelButton: true,
    confirmButtonText: "Aceptar",
    cancelButtonText: "Cancelar"
  }).then(result => {
    if (result.value) {

      var datos = {
        id: id,
        eliminar: que_eliminar
      };



      $.ajax({
        type: "POST",
        url: ruta,
        data: datos,
        dataType: "json",
        success: function (data) {

            $('#eli' + id).slideUp()
          if (data.estado == "1") {
            Swal.fire({
              title: 'Alerta',
              html: 'El registro se elimino con éxito',
              timer: 1000,
              timerProgressBar: true,
              onBeforeOpen: () => {
                Swal.showLoading()
                timerInterval = setInterval(() => {
                  const content = Swal.getContent()
                  if (content) {
                    const b = content.querySelector('b')
                    if (b) {
                      b.textContent = Swal.getTimerLeft()
                    }
                  }
                }, 100)
              },
              onClose: () => {
                clearInterval(timerInterval)
              }
            }).then((result) => {
              /* Read more about handling dismissals below */
              if (result.dismiss === Swal.DismissReason.timer) {
              
              }
            })

          }
        }
      });
    }
  });
}
//////////////////////////////////////////////////////////////////////
