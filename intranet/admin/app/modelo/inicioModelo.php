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
    if (!empty($_FILES['img1']['name']) and isset($_FILES['img1']['name'])) {
      $extension1 = pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION);
      $ran1 = substr(str_shuffle(str_repeat('0123456789', 5)), 0, 10);
      $img1 = $ran1 . '.' . $extension1;
      move_uploaded_file($_FILES['img1']['tmp_name'], '../publico/img_data/' . $img1);
    } else {
      header('Location:' . ruta . '?pagina=pdf_agregar&id=' . $_POST['id']);
      exit;
    }

    $stmt = $this->datab
      ->prepare("insert into pdf (pdf,id_user,titulo,vista,estado) values(?,?,?,?,?)");
    $stmt->bindParam(1, $img1);
    $stmt->bindParam(2, $_POST['id']);
    $stmt->bindParam(3, $_POST['titulo']);
    $vista = '0';
    $estado = '0';
    $stmt->bindParam(4, $vista);
    $stmt->bindParam(5, $estado);
    $stmt->execute();
    header('Location:' . ruta . '?pagina=pdf&id=' . $_POST['id']);
    exit;
  }

  public function for_pdf_editar()
  {
    $sql = $this->consultas(
      "select * from pdf where id ='" . $_POST['id'] . "'"
    );
   
    if (!empty($_FILES['img1']['name']) and isset($_FILES['img1']['name'])) {
      $extension1 = pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION);
      $ran1 = substr(str_shuffle(str_repeat('0123456789', 5)), 0, 10);
      $img1 = $ran1 . '.' . $extension1;
      move_uploaded_file($_FILES['img1']['tmp_name'], '../publico/img_data/' . $img1);
    } else {
      $img1 = $sql[0]['pdf'];
    }

    $stmt = $this->datab->prepare("UPDATE pdf set 
		pdf=?,
		titulo=? 
		where id=?
		");
    $stmt->bindParam(1, $img1);
    $stmt->bindParam(2, $_POST['titulo']); 
    $stmt->bindParam(3, $_POST['id']);
    $stmt->execute();
    
    // Obtener id_user del PDF para redirigir correctamente
    $id_user = isset($sql[0]['id_user']) ? $sql[0]['id_user'] : '';
    if (!empty($id_user)) {
      header('Location:' . ruta . '?pagina=pdf&id=' . $id_user);
    } else {
      header('Location:' . ruta . '?pagina=pdf');
    }
    exit;
  }

  public function eliminar_pdf()
  {
    $stmt = $this->datab->prepare("delete from pdf where id=?");
    $stmt->bindValue(1, $_POST['id']);
    $stmt->execute();
    header('Location:' . ruta . '?pagina=pdf&id=' . $_POST['id_user']);
    exit;
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
      $clave_hash = password_hash($_POST['clave'], PASSWORD_DEFAULT);

      $stmt = $this->datab
        ->prepare("insert into clientes (ruc,razon_social,representante,clave) values(?,?,?,?)");
      $stmt->bindParam(1, $_POST['ruc']);
      $stmt->bindParam(2, $_POST['razon_social']);
      $stmt->bindParam(3, $_POST['representante']);
      $stmt->bindParam(4, $clave_hash);
      $stmt->execute();

      header('Location:' . ruta . '?pagina=clientes');
      exit;
    }
  }

  public function for_clientes_editar()
  {
    $sql = $this->consultas(
      "select * from clientes where id ='" . $_POST['id'] . "'"
    );
    $claven = $sql[0]['clave'];

    if (!empty($_POST['clave']) && $_POST['clave'] !== $sql[0]['clave']) {
      $claven = password_hash($_POST['clave'], PASSWORD_DEFAULT);
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
    header('Location:' . ruta . '?pagina=clientes');
    exit;
  }

  public function eliminar_clientes()
  {
    $stmt = $this->datab->prepare("delete from clientes where id=?");
    $stmt->bindValue(1, $_POST['id']);
    $stmt->execute();
    header('Location:' . ruta . '?pagina=clientes');
    exit;
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
