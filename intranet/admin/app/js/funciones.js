 
//////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////


function eliminar_todo(ruta, id, que_eliminar) {
  // Determinar el tipo de elemento a eliminar para el mensaje
  let tipoElemento = '';
  let mensajeConfirmacion = '';
  if (que_eliminar === 'eliminar_pdf') {
    tipoElemento = 'PDF';
    mensajeConfirmacion = '¿Estás seguro de eliminar este PDF? Esta acción no se puede deshacer.';
  } else if (que_eliminar === 'eliminar_clientes') {
    tipoElemento = 'empresa';
    mensajeConfirmacion = '¿Estás seguro de eliminar esta empresa? Esta acción eliminará todos los PDFs asociados y no se puede deshacer.';
  } else {
    tipoElemento = 'registro';
    mensajeConfirmacion = '¿Estás seguro de eliminar este registro? Esta acción no se puede deshacer.';
  }

  Swal.fire({
    icon: "warning",
    title: "Confirmar eliminación",
    text: mensajeConfirmacion,
    showCloseButton: true,
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6"
  }).then(result => {
    if (result.isConfirmed) {
      // Mostrar loading
      Swal.fire({
        title: 'Eliminando...',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      var datos = {
        id: id,
        eliminar: que_eliminar
      };

      $.ajax({
        type: "POST",
        url: ruta,
        data: datos,
        dataType: "json",
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        success: function (data) {
          if (data.estado == "1" || data.estado == 1) {
            // Eliminar el elemento de la tabla con animación
            const elemento = $('#eli' + id);
            if (elemento.length) {
              elemento.fadeOut(300, function() {
                $(this).remove();
                
                // Verificar si la tabla quedó vacía
                const tbody = elemento.closest('tbody');
                if (tbody && tbody.find('tr').length === 0) {
                  // Obtener el número de columnas de la tabla
                  const thead = tbody.closest('table').find('thead tr');
                  const columnCount = thead.length > 0 ? thead.find('th').length : 3;
                  tbody.html('<tr><td colspan="' + columnCount + '" style="text-align: center; padding: 40px; color: var(--gray-500);">No hay registros</td></tr>');
                }
              });
            }

            // Mostrar notificación de éxito
            Swal.fire({
              icon: 'success',
              title: '¡Eliminado!',
              text: data.mensaje || 'El registro se eliminó correctamente',
              timer: 2000,
              timerProgressBar: true,
              showConfirmButton: false
            });
          } else {
            // Error en la eliminación
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.mensaje || 'No se pudo eliminar el registro',
              confirmButtonText: 'Aceptar'
            });
          }
        },
        error: function(xhr, status, error) {
          // Error de conexión o servidor
          Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor. Por favor, intenta nuevamente.',
            confirmButtonText: 'Aceptar'
          });
        }
      });
    }
  });
}

//////////////////////////////////////////////////////////////////////

