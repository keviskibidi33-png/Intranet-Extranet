/**
 * Sistema de Notificaciones para PDFs próximos a eliminar
 * Carga notificaciones automáticamente y actualiza el contador
 * Soporta deslizar para ocultar y reaparición según urgencia
 */

(function() {
  'use strict';

  // URL del endpoint de notificaciones
  const NOTIFICACIONES_URL = ruta + '?pagina=notificaciones';
  
  // Clave para localStorage
  const STORAGE_KEY = 'geofal_notificaciones_ocultas';
  
  // Tiempos de reaparición según urgencia (en milisegundos)
  const TIEMPOS_REAPARICION = {
    urgent: 1 * 60 * 60 * 1000,      // 1 hora para urgentes (≤1 día)
    warning: 2 * 60 * 60 * 1000,      // 2 horas para advertencias (≤3 días)
    info: 3 * 60 * 60 * 1000          // 3 horas para informativas (≤7 días)
  };

  /**
   * Obtener notificaciones ocultas desde localStorage
   */
  function obtenerNotificacionesOcultas() {
    try {
      const stored = localStorage.getItem(STORAGE_KEY);
      return stored ? JSON.parse(stored) : {};
    } catch (e) {
      console.error('Error al leer notificaciones ocultas:', e);
      return {};
    }
  }

  /**
   * Guardar notificación como oculta
   */
  function ocultarNotificacion(notifId, urgencia) {
    const ocultas = obtenerNotificacionesOcultas();
    const tiempoReaparicion = TIEMPOS_REAPARICION[urgencia] || TIEMPOS_REAPARICION.info;
    ocultas[notifId] = {
      timestamp: Date.now(),
      urgencia: urgencia,
      reapareceEn: Date.now() + tiempoReaparicion
    };
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(ocultas));
    } catch (e) {
      console.error('Error al guardar notificación oculta:', e);
    }
  }

  /**
   * Verificar si una notificación debe mostrarse
   */
  function debeMostrarNotificacion(notifId) {
    const ocultas = obtenerNotificacionesOcultas();
    if (!ocultas[notifId]) {
      return true; // No está oculta
    }
    
    const ahora = Date.now();
    const notifOculta = ocultas[notifId];
    
    // Si ya pasó el tiempo de reaparición, limpiar y mostrar
    if (ahora >= notifOculta.reapareceEn) {
      delete ocultas[notifId];
      try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(ocultas));
      } catch (e) {
        console.error('Error al limpiar notificación:', e);
      }
      return true;
    }
    
    return false; // Aún está oculta
  }

  /**
   * Limpiar notificaciones vencidas del localStorage
   */
  function limpiarNotificacionesVencidas() {
    const ocultas = obtenerNotificacionesOcultas();
    const ahora = Date.now();
    let modificado = false;
    
    for (const notifId in ocultas) {
      if (ocultas[notifId].reapareceEn <= ahora) {
        delete ocultas[notifId];
        modificado = true;
      }
    }
    
    if (modificado) {
      try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(ocultas));
      } catch (e) {
        console.error('Error al limpiar notificaciones:', e);
      }
    }
  }

  /**
   * Cargar notificaciones desde el servidor
   */
  function cargarNotificaciones() {
    // Limpiar notificaciones vencidas primero
    limpiarNotificacionesVencidas();
    
    $.ajax({
      url: NOTIFICACIONES_URL,
      method: 'GET',
      dataType: 'json',
      success: function(response) {
        if (response.estado === '1') {
          // Filtrar notificaciones ocultas
          const notificacionesFiltradas = response.notificaciones.filter(function(notif) {
            return debeMostrarNotificacion(notif.id);
          });
          
          mostrarNotificaciones(notificacionesFiltradas, notificacionesFiltradas.length);
        } else {
          mostrarSinNotificaciones();
        }
      },
      error: function(xhr, status, error) {
        console.error('Error al cargar notificaciones:', error);
        mostrarErrorNotificaciones();
      }
    });
  }

  /**
   * Inicializar funcionalidad de swipe en una notificación
   */
  function inicializarSwipe($notifItem, notifId, urgencia) {
    let startX = 0;
    let currentX = 0;
    let isDragging = false;
    let startTime = 0;
    let hasMoved = false;
    let moveThreshold = 10; // Mínimo movimiento para considerar swipe (no click)
    let swipeThreshold = 80; // Píxeles necesarios para activar el swipe completo
    const $wrapper = $notifItem.closest('.notification-item-wrapper');
    const $deleteAction = $wrapper.find('.notification-delete-action');
    const $link = $notifItem;
    let clickPrevented = false;
    
    // Función para manejar el final del swipe
    function handleSwipeEnd() {
      if (!isDragging) return;
      const diff = currentX - startX;
      const timeDiff = Date.now() - startTime;
      const absDiff = Math.abs(diff);
      
      // Si se deslizó lo suficiente en cualquier dirección, ocultar
      if (absDiff >= swipeThreshold && timeDiff < 800 && hasMoved) {
        clickPrevented = true;
        ocultarNotificacionPorSwipe($wrapper, notifId, urgencia);
      } else {
        // Volver a la posición original con animación suave
        $notifItem.css({
          'transform': 'translateX(0)',
          'transition': 'transform 0.3s ease-out'
        });
        $deleteAction.css('opacity', '0');
        clickPrevented = false;
        
        // Remover la transición después de la animación
        setTimeout(function() {
          if ($notifItem.hasClass('swiping')) {
            $notifItem.css('transition', '');
          }
        }, 300);
      }
      
      isDragging = false;
      $notifItem.removeClass('swiping');
      hasMoved = false;
    }
    
    // Eventos táctiles (móvil)
    $notifItem.on('touchstart', function(e) {
      startX = e.originalEvent.touches[0].clientX;
      startTime = Date.now();
      isDragging = true;
      hasMoved = false;
      clickPrevented = false;
      $notifItem.addClass('swiping');
    });
    
    $notifItem.on('touchmove', function(e) {
      if (!isDragging) return;
      const touchX = e.originalEvent.touches[0].clientX;
      const diff = touchX - startX;
      const absDiff = Math.abs(diff);
      
      // Solo prevenir default si hay movimiento significativo
      if (absDiff > moveThreshold) {
        e.preventDefault();
        hasMoved = true;
        currentX = touchX;
        
        // Permitir swipe en ambas direcciones
        $notifItem.css('transform', 'translateX(' + diff + 'px)');
        
        // Mostrar acción de eliminar solo si se desliza hacia la izquierda
        if (diff < 0) {
          $deleteAction.css('opacity', Math.min(1, Math.abs(diff) / swipeThreshold));
        } else {
          $deleteAction.css('opacity', '0');
        }
      }
    });
    
    $notifItem.on('touchend', function(e) {
      if (hasMoved) {
        e.preventDefault();
      }
      handleSwipeEnd();
    });
    
    // Eventos de mouse (desktop) - mejorados para no interferir con clicks
    $notifItem.on('mousedown', function(e) {
      if (e.button !== 0) return; // Solo botón izquierdo
      startX = e.clientX;
      startTime = Date.now();
      isDragging = true;
      hasMoved = false;
      clickPrevented = false;
      // No agregar clase swiping hasta que haya movimiento
    });
    
    // Usar namespaces para eventos únicos por elemento
    const eventNamespace = 'swipe_' + notifId;
    
    $(document).on('mousemove.' + eventNamespace, function(e) {
      if (!isDragging) return;
      const diff = e.clientX - startX;
      const absDiff = Math.abs(diff);
      
      // Solo considerar movimiento si supera el umbral mínimo
      if (absDiff > moveThreshold) {
        // Agregar clase swiping solo cuando hay movimiento real
        if (!$notifItem.hasClass('swiping')) {
          $notifItem.addClass('swiping');
        }
        
        hasMoved = true;
        currentX = e.clientX;
        
        // Permitir swipe en ambas direcciones
        $notifItem.css('transform', 'translateX(' + diff + 'px)');
        
        // Mostrar acción de eliminar solo si se desliza hacia la izquierda
        if (diff < 0) {
          $deleteAction.css('opacity', Math.min(1, Math.abs(diff) / swipeThreshold));
        } else {
          $deleteAction.css('opacity', '0');
        }
      }
    });
    
    $(document).on('mouseup.' + eventNamespace, function(e) {
      const diff = currentX - startX;
      const absDiff = Math.abs(diff);
      
      // Solo prevenir el click si hubo un swipe significativo
      if (isDragging && hasMoved && absDiff >= swipeThreshold) {
        e.preventDefault();
        e.stopPropagation();
      }
      
      handleSwipeEnd();
      
      // Limpiar eventos después de un pequeño delay para permitir que el click se procese si no hubo swipe
      setTimeout(function() {
        $(document).off('mousemove.' + eventNamespace + ' mouseup.' + eventNamespace);
      }, 50);
    });
    
    // Click directo en el botón de eliminar
    $deleteAction.on('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      ocultarNotificacionPorSwipe($wrapper, notifId, urgencia);
    });
    
    // Prevenir click en el enlace solo si hubo swipe significativo
    $link.on('click', function(e) {
      const diff = currentX - startX;
      const absDiff = Math.abs(diff);
      
      // Solo prevenir el click si hubo un swipe completo (no solo movimiento mínimo)
      if (clickPrevented || (hasMoved && absDiff >= swipeThreshold)) {
        e.preventDefault();
        e.stopPropagation();
        clickPrevented = false;
        return false;
      }
      // Si no hubo swipe, permitir el click normal
    });
    
    // Prevenir drag del enlace cuando se está haciendo swipe
    $link.on('dragstart', function(e) {
      if (hasMoved || isDragging) {
        e.preventDefault();
        return false;
      }
    });
    
    // Prevenir que el navegador arrastre la imagen del enlace
    $link.on('selectstart', function(e) {
      if (hasMoved || isDragging) {
        e.preventDefault();
        return false;
      }
    });
  }

  /**
   * Ocultar notificación mediante swipe
   */
  function ocultarNotificacionPorSwipe($wrapper, notifId, urgencia) {
    const $notifItem = $wrapper.find('.notification-item');
    $notifItem.addClass('notification-hiding');
    
    setTimeout(function() {
      $wrapper.slideUp(300, function() {
        $wrapper.remove();
        ocultarNotificacion(notifId, urgencia);
        
        // Actualizar contador
        const notificacionesRestantes = $('#notificationList .notification-item-wrapper').length;
        const $notificationCount = $('#notificationCount');
        const $notificationSubtitle = $('#notificationSubtitle');
        const $notificationFooter = $('#notificationFooter');
        
        if (notificacionesRestantes <= 0) {
          $notificationCount.hide();
          $notificationSubtitle.text('No hay notificaciones');
          $notificationFooter.hide();
          $('#notificationList').html(
            '<div class="notification-empty">' +
            '<i class="fa fa-check-circle"></i>' +
            '<p>No hay notificaciones visibles</p>' +
            '</div>'
          );
        } else {
          $notificationCount.text(notificacionesRestantes);
          $notificationSubtitle.text(notificacionesRestantes === 1 ? '1 notificación' : notificacionesRestantes + ' notificaciones');
        }
      });
    }, 100);
  }

  /**
   * Mostrar notificaciones en el dropdown
   */
  function mostrarNotificaciones(notificaciones, total) {
    const $notificationList = $('#notificationList');
    const $notificationCount = $('#notificationCount');
    const $notificationSubtitle = $('#notificationSubtitle');
    const $notificationFooter = $('#notificationFooter');

    // Actualizar contador
    if (total > 0) {
      $notificationCount.text(total).show();
      $notificationSubtitle.text(total === 1 ? '1 notificación' : total + ' notificaciones');
    } else {
      $notificationCount.hide();
      $notificationSubtitle.text('No hay notificaciones');
    }

    // Limpiar lista
    $notificationList.empty();

    if (total === 0) {
      $notificationList.html(
        '<div class="notification-empty">' +
        '<i class="fa fa-check-circle"></i>' +
        '<p>No hay PDFs próximos a eliminar</p>' +
        '</div>'
      );
      $notificationFooter.hide();
      return;
    }

    // Agregar cada notificación
    notificaciones.forEach(function(notif) {
      const diasTexto = notif.dias_restantes === 0 ? 'Hoy' : 
                        notif.dias_restantes === 1 ? 'Mañana' : 
                        notif.dias_restantes + ' días';
      
      const urgenciaClass = notif.dias_restantes <= 1 ? 'notification-urgent' : 
                           notif.dias_restantes <= 3 ? 'notification-warning' : 
                           'notification-info';
      
      const urgenciaType = notif.dias_restantes <= 1 ? 'urgent' : 
                          notif.dias_restantes <= 3 ? 'warning' : 
                          'info';

      const notificacionHTML = 
        '<div class="notification-item-wrapper" data-notif-id="' + notif.id + '" data-urgencia="' + urgenciaType + '">' +
        '<div class="notification-delete-action">' +
        '<i class="fa fa-times"></i>' +
        '<span>Deslizar para ocultar</span>' +
        '</div>' +
        '<a href="' + notif.url + '" class="notification-item ' + urgenciaClass + '">' +
        '<div class="notification-item-icon">' +
        '<i class="fa fa-file-pdf-o"></i>' +
        '</div>' +
        '<div class="notification-item-content">' +
        '<div class="notification-item-title">' + escapeHtml(notif.titulo) + '</div>' +
        '<div class="notification-item-company">' + escapeHtml(notif.razon_social) + 
        (notif.ruc ? ' (' + escapeHtml(notif.ruc) + ')' : '') + '</div>' +
        '<div class="notification-item-date">' +
        '<i class="fa fa-calendar"></i> ' +
        'Se elimina ' + diasTexto + ' (' + notif.fecha_eliminacion + ')' +
        '</div>' +
        '</div>' +
        '</a>' +
        '</div>';

      const $notifElement = $(notificacionHTML);
      $notificationList.append($notifElement);
      
      // Inicializar swipe
      inicializarSwipe($notifElement.find('.notification-item'), notif.id, urgenciaType);
    });

    $notificationFooter.show();
  }

  /**
   * Mostrar mensaje cuando no hay notificaciones
   */
  function mostrarSinNotificaciones() {
    const $notificationList = $('#notificationList');
    const $notificationCount = $('#notificationCount');
    const $notificationSubtitle = $('#notificationSubtitle');
    const $notificationFooter = $('#notificationFooter');

    $notificationCount.hide();
    $notificationSubtitle.text('No hay notificaciones');
    $notificationList.html(
      '<div class="notification-empty">' +
      '<i class="fa fa-check-circle"></i>' +
      '<p>No hay PDFs próximos a eliminar</p>' +
      '</div>'
    );
    $notificationFooter.hide();
  }

  /**
   * Mostrar error al cargar notificaciones
   */
  function mostrarErrorNotificaciones() {
    const $notificationList = $('#notificationList');
    $notificationList.html(
      '<div class="notification-error">' +
      '<i class="fa fa-exclamation-triangle"></i>' +
      '<p>Error al cargar notificaciones</p>' +
      '</div>'
    );
  }

  /**
   * Escapar HTML para prevenir XSS
   */
  function escapeHtml(text) {
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return text ? text.replace(/[&<>"']/g, function(m) { return map[m]; }) : '';
  }

  // Cargar notificaciones al cargar la página
  $(document).ready(function() {
    // Cargar notificaciones inmediatamente
    cargarNotificaciones();

    // Cargar notificaciones cuando se abra el dropdown
    $('#notificationDropdown').on('show.bs.dropdown', function() {
      cargarNotificaciones();
    });

    // Recargar notificaciones cada 5 minutos
    setInterval(cargarNotificaciones, 5 * 60 * 1000);
    
    // Verificar reaparición de notificaciones cada minuto
    setInterval(function() {
      limpiarNotificacionesVencidas();
      cargarNotificaciones();
    }, 60 * 1000);
  });

})();

