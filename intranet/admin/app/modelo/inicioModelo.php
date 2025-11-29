<?php

// Asegurar que sistema.php esté incluido (contiene la clase Conectar)
if (!class_exists('Conectar')) {
    $rutasSistema = [
        __DIR__ . '/../../config/sistema.php',
        __DIR__ . '/../../../config/sistema.php'
    ];
    
    $sistemaIncluido = false;
    foreach ($rutasSistema as $ruta) {
        if (file_exists($ruta)) {
            require_once($ruta);
            $sistemaIncluido = true;
            break;
        }
    }
    
    if (!$sistemaIncluido && !class_exists('Conectar')) {
        die('Error: No se pudo encontrar config/sistema.php. La clase Conectar no está disponible.');
    }
}

class Inicio extends Conectar
{
  public function __construct()
  {
    parent::__construct();
  }

  ///////////////////////////////////////////////////////////////////////
  // FUNCIONES PARA PDFs
  ///////////////////////////////////////////////////////////////////////

  public function for_pdf_agregar()
  {
    // Validar que se haya enviado un archivo
    if (empty($_FILES['img1']['name']) || !isset($_FILES['img1']['name']) || $_FILES['img1']['error'] !== UPLOAD_ERR_OK) {
      header('Location:' . ruta . '?pagina=pdf_agregar&id=' . $_POST['id'] . '&error=1');
      exit;
    }

    // Validar que sea un PDF
    $extension1 = strtolower(pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION));
    if ($extension1 !== 'pdf') {
      header('Location:' . ruta . '?pagina=pdf_agregar&id=' . $_POST['id'] . '&error=2');
    exit;
    }

    // Validar tamaño (10MB máximo)
    if ($_FILES['img1']['size'] > 10 * 1024 * 1024) {
      header('Location:' . ruta . '?pagina=pdf_agregar&id=' . $_POST['id'] . '&error=3');
      exit;
    }

    // Generar nombre único para el archivo
      $ran1 = substr(str_shuffle(str_repeat('0123456789', 5)), 0, 10);
      $img1 = $ran1 . '.' . $extension1;

    // Ruta de destino - Guardar en intranet/publico/img_data/ (como en producción)
    // Usar ruta absoluta basada en __DIR__ para asegurar que se guarde en el lugar correcto
    // __DIR__ = intranet/admin/app/modelo/
    // Necesitamos: intranet/publico/img_data/
    $base_dir = dirname(dirname(dirname(__DIR__))); // Sube 3 niveles: intranet/
    $destino = $base_dir . '/publico/img_data/' . $img1;
    
    // Crear directorio si no existe
    $dir_destino = $base_dir . '/publico/img_data/';
    if (!is_dir($dir_destino)) {
      mkdir($dir_destino, 0755, true);
    }

    // Mover archivo
    if (!move_uploaded_file($_FILES['img1']['tmp_name'], $destino)) {
      header('Location:' . ruta . '?pagina=pdf_agregar&id=' . $_POST['id'] . '&error=4');
      exit;
    }

    // Insertar en base de datos
    // Preparar fecha_eliminacion
    $fecha_subida = date('Y-m-d H:i:s'); // Fecha y hora actual
    
    // Si el admin estableció una fecha manualmente, usarla
    // Si no, calcular automáticamente: fecha_subida + 2 meses
    if (!empty($_POST['fecha_eliminacion'])) {
        $fecha_eliminacion = $_POST['fecha_eliminacion'];
    } else {
        // Calcular automáticamente: fecha_subida + 2 meses
        $fecha_eliminacion_obj = new DateTime($fecha_subida);
        $fecha_eliminacion_obj->modify('+2 months');
        $fecha_eliminacion = $fecha_eliminacion_obj->format('Y-m-d');
    }
    
    // Intentar insertar con fecha_subida, si el campo no existe, se omitirá
    try {
    $stmt = $this->datab
        ->prepare("insert into pdf (pdf,id_user,titulo,vista,estado,fecha_eliminacion,fecha_subida) values(?,?,?,?,?,?,?)");
    $stmt->bindParam(1, $img1);
    $stmt->bindParam(2, $_POST['id']);
    $stmt->bindParam(3, $_POST['titulo']);
      $vista = '0';
      $estado = '0';
      $stmt->bindParam(4, $vista);
      $stmt->bindParam(5, $estado);
      $stmt->bindParam(6, $fecha_eliminacion);
      $stmt->bindParam(7, $fecha_subida);
    $stmt->execute();
    } catch (PDOException $e) {
      // Si el campo fecha_subida no existe, insertar sin él
      if (strpos($e->getMessage(), 'fecha_subida') !== false) {
        $stmt = $this->datab
          ->prepare("insert into pdf (pdf,id_user,titulo,vista,estado,fecha_eliminacion) values(?,?,?,?,?,?)");
        $stmt->bindParam(1, $img1);
        $stmt->bindParam(2, $_POST['id']);
        $stmt->bindParam(3, $_POST['titulo']);
        $vista = '0';
        $estado = '0';
        $stmt->bindParam(4, $vista);
        $stmt->bindParam(5, $estado);
        $stmt->bindParam(6, $fecha_eliminacion);
        $stmt->execute();
      } else {
        throw $e;
      }
    }
    
    // Redirigir con mensaje de éxito
    header('Location:' . ruta . '?pagina=pdf&id=' . $_POST['id'] . '&success=pdf_agregado');
    exit;
  }

  public function for_pdf_editar()
  {
    $sql = $this->consultas(
      "select * from pdf where id ='" . $_POST['id'] . "'"
    );
   
    // Si se envía un nuevo archivo, procesarlo
    if (!empty($_FILES['img1']['name']) && isset($_FILES['img1']['name']) && $_FILES['img1']['error'] === UPLOAD_ERR_OK) {
      // Validar que sea un PDF
      $extension1 = strtolower(pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION));
      if ($extension1 !== 'pdf') {
        header('Location:' . ruta . '?pagina=pdf_editar&id=' . $_POST['id'] . '&error=2');
        exit;
      }

      // Validar tamaño (10MB máximo)
      if ($_FILES['img1']['size'] > 10 * 1024 * 1024) {
        header('Location:' . ruta . '?pagina=pdf_editar&id=' . $_POST['id'] . '&error=3');
        exit;
      }

      // Generar nombre único para el archivo
      $ran1 = substr(str_shuffle(str_repeat('0123456789', 5)), 0, 10);
      $img1 = $ran1 . '.' . $extension1;

      // Ruta de destino - Guardar en intranet/publico/img_data/ (como en producción)
      // Usar ruta absoluta basada en __DIR__ para asegurar que se guarde en el lugar correcto
      // __DIR__ = intranet/admin/app/modelo/
      // Necesitamos: intranet/publico/img_data/
      $base_dir = dirname(dirname(dirname(__DIR__))); // Sube 3 niveles: intranet/
      $destino = $base_dir . '/publico/img_data/' . $img1;
      
      // Crear directorio si no existe
      $dir_destino = $base_dir . '/publico/img_data/';
      if (!is_dir($dir_destino)) {
        mkdir($dir_destino, 0755, true);
      }

      // Mover archivo
      if (!move_uploaded_file($_FILES['img1']['tmp_name'], $destino)) {
        header('Location:' . ruta . '?pagina=pdf_editar&id=' . $_POST['id'] . '&error=4');
        exit;
      }
    } else {
      // Mantener el archivo existente
      $img1 = $sql[0]['pdf'];
    }

    // Preparar fecha_eliminacion
    // Si el admin estableció una fecha manualmente, usarla
    // Si no, calcular automáticamente: fecha_subida + 2 meses
    if (!empty($_POST['fecha_eliminacion'])) {
        $fecha_eliminacion = $_POST['fecha_eliminacion'];
    } else {
        // Obtener fecha_subida del PDF actual
        $fecha_subida_actual = isset($sql[0]['fecha_subida']) && !empty($sql[0]['fecha_subida']) 
            ? $sql[0]['fecha_subida'] 
            : date('Y-m-d H:i:s');
        // Calcular automáticamente: fecha_subida + 2 meses
        $fecha_eliminacion_obj = new DateTime($fecha_subida_actual);
        $fecha_eliminacion_obj->modify('+2 months');
        $fecha_eliminacion = $fecha_eliminacion_obj->format('Y-m-d');
    }

    $stmt = $this->datab->prepare("UPDATE pdf set 
		pdf=?,
		titulo=?,
		fecha_eliminacion=?
		where id=?
		");
    $stmt->bindParam(1, $img1);
    $stmt->bindParam(2, $_POST['titulo']); 
    $stmt->bindParam(3, $fecha_eliminacion);
    $stmt->bindParam(4, $_POST['id']);
    $stmt->execute();

    // Obtener id_user del PDF para redirigir correctamente
    $id_user = isset($sql[0]['id_user']) ? $sql[0]['id_user'] : '';
    if (!empty($id_user)) {
      header('Location:' . ruta . '?pagina=pdf&id=' . $id_user . '&success=pdf_editado');
    } else {
      header('Location:' . ruta . '?pagina=pdf&success=pdf_editado');
    }
    exit;
  }

  public function eliminar_pdf()
  {
    // Detectar si es petición AJAX (por header o por Accept)
    $is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ||
               (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    
    if ($is_ajax) {
      try {
        // Obtener el nombre del archivo PDF antes de eliminar
        $sql = $this->consultas("SELECT pdf FROM pdf WHERE id = '" . $_POST['id'] . "'");
        $pdf_filename = !empty($sql[0]['pdf']) ? $sql[0]['pdf'] : '';
        
        // Eliminar de la base de datos
        $stmt = $this->datab->prepare("DELETE FROM pdf WHERE id=?");
    $stmt->bindValue(1, $_POST['id']);
    $stmt->execute();

        // Intentar eliminar el archivo físico si existe
        if (!empty($pdf_filename)) {
          // Buscar en la ubicación correcta: intranet/publico/img_data/
          $base_dir = dirname(dirname(dirname(__DIR__))); // Sube 3 niveles: intranet/
          $file_path = $base_dir . '/publico/img_data/' . $pdf_filename;
          if (file_exists($file_path)) {
            @unlink($file_path);
          }
          // También buscar en la ubicación antigua por compatibilidad
          $file_path_old = dirname($base_dir) . '/publico/img_data/' . $pdf_filename;
          if (file_exists($file_path_old)) {
            @unlink($file_path_old);
          }
        }
        
        header('Content-Type: application/json');
        echo json_encode(['estado' => '1', 'mensaje' => 'PDF eliminado correctamente']);
        exit;
      } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['estado' => '0', 'mensaje' => 'Error al eliminar el PDF: ' . $e->getMessage()]);
        exit;
      }
    } else {
      // Si no es AJAX, redirigir como antes (compatibilidad)
      $id_user = isset($_POST['id_user']) ? $_POST['id_user'] : '';
      $stmt = $this->datab->prepare("DELETE FROM pdf WHERE id=?");
    $stmt->bindValue(1, $_POST['id']);
    $stmt->execute();
      if (!empty($id_user)) {
        header('Location:' . ruta . '?pagina=pdf&id=' . $id_user);
    } else {
        header('Location:' . ruta . '?pagina=pdf');
      }
    exit;
    }
  }

  public function mostrar_pdf_id($id)
  {
    $sql = $this->consultas("select * from pdf where id ='$id'");
    return $sql;
  }

  public function mostrar_pdf_pagi($bus, $id)
  {
    if ($bus == '0') {
      $b = '';
    } else {
      $b = "and titulo like '%" . $bus . "%'  ";
    }
    $sql = $this->consultas(
      "SELECT * FROM pdf where id_user='$id'   $b order by id desc  "
    );
    return $sql;
  }

  /**
   * Obtener PDFs próximos a eliminar (próximos 7 días)
   * Retorna array con información del PDF y del cliente
   */
  public function obtener_pdfs_proximos_eliminar()
  {
    // Calcular fecha límite (7 días desde hoy)
    $fecha_limite = date('Y-m-d', strtotime('+7 days'));
    $fecha_hoy = date('Y-m-d');
    
    // Consultar PDFs con fecha_eliminacion entre hoy y 7 días
    $sql = $this->consultas(
      "SELECT 
        p.id,
        p.titulo,
        p.fecha_eliminacion,
        p.id_user,
        c.razon_social,
        c.ruc
      FROM pdf p
      LEFT JOIN clientes c ON p.id_user = c.id
      WHERE p.fecha_eliminacion IS NOT NULL
        AND p.fecha_eliminacion >= '$fecha_hoy'
        AND p.fecha_eliminacion <= '$fecha_limite'
      ORDER BY p.fecha_eliminacion ASC
      "
    );

    return $sql;
  }

  ///////////////////////////////////////////////////////////////////////
  // FUNCIONES PARA CLIENTES
  ///////////////////////////////////////////////////////////////////////

  public function for_clientes_agregar()
  {
    $cli = $this->consultas("select * from clientes where ruc ='" . $_POST['ruc'] . "'");

    if (count($cli)) {
      header('Location:' . ruta . '?pagina=clientes&error=1');
      exit;
    } else {
      // Guardar clave en texto plano para que los clientes puedan verla
      $clave_plano = $_POST['clave'];

      $stmt = $this->datab
        ->prepare("insert into clientes (ruc,razon_social,representante,clave) values(?,?,?,?)");
      $stmt->bindParam(1, $_POST['ruc']);
      $stmt->bindParam(2, $_POST['razon_social']);
      $stmt->bindParam(3, $_POST['representante']);
      $stmt->bindParam(4, $clave_plano);
      $stmt->execute();

      header('Location:' . ruta . '?pagina=clientes&success=cliente_agregado');
      exit;
    }
  }

  public function for_clientes_editar()
  {
    $sql = $this->consultas(
      "select * from clientes where id ='" . $_POST['id'] . "'"
    );
      $claven = $sql[0]['clave'];

    // Si se proporciona una nueva clave, guardarla en texto plano
    if (!empty($_POST['clave']) && $_POST['clave'] !== $sql[0]['clave']) {
      $claven = $_POST['clave'];
    }

    $stmt = $this->datab->prepare("UPDATE clientes set 
		ruc=?,
		razon_social=?,
		representante=?,
		clave=? 
		where id=?
		");
    $stmt->bindParam(1, $_POST['ruc']);
    $stmt->bindParam(2, $_POST['razon_social']);
    $stmt->bindParam(3, $_POST['representante']);
    $stmt->bindParam(4, $claven);
    $stmt->bindParam(5, $_POST['id']);
    $stmt->execute();
    header('Location:' . ruta . '?pagina=clientes&success=cliente_editado');
    exit;
  }

  public function eliminar_clientes()
  {
    // Si es una petición AJAX, devolver JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      try {
        // Eliminar de la base de datos
        $stmt = $this->datab->prepare("DELETE FROM clientes WHERE id=?");
        $stmt->bindValue(1, $_POST['id']);
    $stmt->execute();

        header('Content-Type: application/json');
        echo json_encode(['estado' => '1', 'mensaje' => 'Empresa eliminada correctamente']);
        exit;
      } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['estado' => '0', 'mensaje' => 'Error al eliminar la empresa']);
        exit;
      }
    } else {
      // Si no es AJAX, redirigir como antes (compatibilidad)
      $stmt = $this->datab->prepare("DELETE FROM clientes WHERE id=?");
      $stmt->bindValue(1, $_POST['id']);
      $stmt->execute();
    header('Location:' . ruta . '?pagina=clientes');
    exit;
    }
  }

  public function mostrar_clientes_id($id)
  {
    $sql = $this->consultas("select * from clientes where id ='$id'");
    return $sql;
  }

  public function contar_clientes_pagi($bus)
  {
    if ($bus == '0') {
      $b = '';
    } else {
      $b = "where ruc like '%" . $bus . "%' or razon_social like '%" . $bus . "%' or representante like '%" . $bus . "%'";
    }
    $sql = $this->consultas("SELECT * from clientes $b order by id desc");
    return count($sql);
  }

  public function mostrar_clientes_pagi($offset, $per_page, $bus)
  {
    if ($bus == '0') {
      $b = '';
    } else {
      $b = "where ruc like '%" . $bus . "%' or razon_social like '%" . $bus . "%' or representante like '%" . $bus . "%'";
    }
    $sql = $this->consultas("SELECT * from clientes $b order by id desc LIMIT $offset, $per_page");
    return $sql;
  }

  ///////////////////////////////////////////////////////////////////////
  // FUNCIONES PARA PERFIL
  ///////////////////////////////////////////////////////////////////////

  public function for_perfil_editar()
  {
    $sql = $this->consultas(
      "select * from usuarios where id ='" . $_SESSION['id_geofal'] . "'"
    );
    $claven = $sql[0]['clave'];

    if (!empty($_POST['clave']) && $_POST['clave'] !== $sql[0]['clave']) {
      $claven = password_hash($_POST['clave'], PASSWORD_DEFAULT);
    }

    $stmt = $this->datab->prepare("UPDATE usuarios set 
      dni=?,
      clave=? 
		where id=?
      ");
    $stmt->bindParam(1, $_POST['dni']);
    $stmt->bindParam(2, $claven);
    $stmt->bindParam(3, $_SESSION['id_geofal']);
    $stmt->execute();
    header('Location:' . ruta . '?pagina=perfil');
    exit;
  }

  public function mostrar_user()
  {
    $sql = $this->consultas("select * from usuarios where id ='" . $_SESSION['id_geofal'] . "'");
    return $sql;
  }

  ///////////////////////////////////////////////////////////////////////
  // FUNCIÓN PAGINADOR (heredada o implementada aquí)
  ///////////////////////////////////////////////////////////////////////

  public function paginador($reload, $page, $total_pages, $adjacents, $ruta, $pagina, $numrows, $get_b, $tipo)
  {
    $prevlabel = "&lsaquo; Prev";
    $nextlabel = "Next &rsaquo;";
    $out = '<ul class="pagination">';
    
    // previous label
    if ($page == 1) {
      $out .= "<li class='page-item disabled'><span class='page-link'>$prevlabel</span></li>";
    } else {
      $out .= "<li class='page-item'><a class='page-link' href='" . $ruta . "?pagina=" . $pagina . "&p=" . ($page - 1) . "&b=" . $get_b . "'>$prevlabel</a></li>";
    }
    
    // first
    if ($page > ($adjacents + 1)) {
      $out .= "<li class='page-item'><a class='page-link' href='" . $ruta . "?pagina=" . $pagina . "&p=1&b=" . $get_b . "'>1</a></li>";
    }
    
    // interval
    if ($page > ($adjacents + 2)) {
      $out .= "<li class='page-item disabled'><span class='page-link'>...</span></li>";
    }
    
    // pages
    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($total_pages - $adjacents)) ? ($page + $adjacents) : $total_pages;

    for ($i = $pmin; $i <= $pmax; $i++) {
      if ($i == $page) {
        $out .= "<li class='page-item active'><span class='page-link'>$i</span></li>";
      } else {
        $out .= "<li class='page-item'><a class='page-link' href='" . $ruta . "?pagina=" . $pagina . "&p=$i&b=" . $get_b . "'>$i</a></li>";
      }
    }
    
    // interval
    if ($page < ($total_pages - $adjacents - 1)) {
      $out .= "<li class='page-item disabled'><span class='page-link'>...</span></li>";
    }
    
    // last
    if ($page < ($total_pages - $adjacents)) {
      $out .= "<li class='page-item'><a class='page-link' href='" . $ruta . "?pagina=" . $pagina . "&p=$total_pages&b=" . $get_b . "'>$total_pages</a></li>";
    }
    
    // next
    if ($page < $total_pages) {
      $out .= "<li class='page-item'><a class='page-link' href='" . $ruta . "?pagina=" . $pagina . "&p=" . ($page + 1) . "&b=" . $get_b . "'>$nextlabel</a></li>";
    } else {
      $out .= "<li class='page-item disabled'><span class='page-link'>$nextlabel</span></li>";
    }

    $out .= '</ul>';
    return $out;
  }

  ///////////////////////////////////////////////////////////////////////
  // OTRAS FUNCIONES (si existen en otros controladores)
  ///////////////////////////////////////////////////////////////////////

  public function eliminar_ordenes()
  {
    $stmt = $this->datab->prepare("delete from pdf where id=?");
    $stmt->bindValue(1, $_POST['id']);
    $stmt->execute();
    header('Location:' . ruta . '?pagina=ordenes');
    exit;
  }

  public function contar_ordenes_pagi($bus)
  {
    if ($bus == '0') {
      $b = '';
    } else {
      $b = "where titulo like '%" . $bus . "%'";
    }
    $sql = $this->consultas("SELECT * from pdf $b order by id desc");
    return $sql;
  }

  public function mostrar_ordenes_pagi($offset, $per_page, $bus)
  {
    if ($bus == '0') {
      $b = '';
      } else {
      $b = "where titulo like '%" . $bus . "%'";
    }
    $sql = $this->consultas("SELECT * from pdf $b order by id desc LIMIT $offset, $per_page");
    return $sql;
  }

  public function eliminar_compras()
  {
    $stmt = $this->datab->prepare("delete from compras where id=?");
    $stmt->bindValue(1, $_POST['id']);
    $stmt->execute();
    header('Location:' . ruta . '?pagina=compras');
    exit;
  }

  public function contar_compras_pagi($bus)
  {
    if ($bus == '0') {
      $b = '';
    } else {
      $b = "where titulo like '%" . $bus . "%'";
    }
    $sql = $this->consultas("SELECT * from compras $b order by id desc");
    return $sql;
  }

  public function mostrar_compras_pagi($offset, $per_page, $bus)
  {
    if ($bus == '0') {
      $b = '';
    } else {
      $b = "where titulo like '%" . $bus . "%'";
    }
    $sql = $this->consultas("SELECT * from compras $b order by id desc LIMIT $offset, $per_page");
    return $sql;
  }

  public function for_compras_agregar()
  {
    // Implementar según necesidad
    header('Location:' . ruta . '?pagina=compras');
    exit;
  }

  public function for_compras_editar()
  {
    // Implementar según necesidad
    header('Location:' . ruta . '?pagina=compras');
    exit;
  }

  public function for_productos_agregar()
  {
    // Implementar según necesidad
    header('Location:' . ruta . '?pagina=productos');
    exit;
  }

  public function for_productos_editar()
  {
    // Implementar según necesidad
    header('Location:' . ruta . '?pagina=productos');
    exit;
  }

  public function mostrar_noticias()
  {
    $sql = $this->consultas("select * from noticias");
    return $sql;
  }
}
