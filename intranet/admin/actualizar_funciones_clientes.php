<?php
/**
 * Script para actualizar las funciones de clientes
 * Elimina teléfono, dirección, correo y agrega hash de contraseñas
 */

$archivo = __DIR__ . '/app/modelo/inicioModelo.php';
$contenido = file_get_contents($archivo);

// Actualizar for_clientes_agregar()
$patron_agregar = '/public function for_clientes_agregar\(\)\s*\{[^}]*->prepare\("insert into clientes \(ruc,razon_social,telefono,direccion,representante,clave,correo\) values\(\?,\?,\?,\?,\?,\?,\?\)"\);[^}]*bindParam\(7, \$_POST\[\'correo\'\]\);[^}]*execute\(\);[^}]*require \'include\/phpmiler[^}]*Send\(\);/s';

$reemplazo_agregar = 'public function for_clientes_agregar()
  {
    $cli = $this->consultas("select * from clientes where ruc =\'" . $_POST[\'ruc\'] . "\'");
    if (count($cli)) {
      header(\'Location:\' . ruta . \'?pagina=clientes&error=1\');
      exit;
    } else {
      // Hash de contraseña para seguridad
      $clave_hash = password_hash($_POST[\'clave\'], PASSWORD_DEFAULT);
      
      // Insertar solo campos requeridos: RUC, Razón Social, Clave (hasheada), Representante
      $stmt = $this->datab
        ->prepare("insert into clientes (ruc, razon_social, representante, clave) values(?, ?, ?, ?)");
      $stmt->bindParam(1, $_POST[\'ruc\']);
      $stmt->bindParam(2, $_POST[\'razon_social\']);
      $stmt->bindParam(3, $_POST[\'representante\']);
      $stmt->bindParam(4, $clave_hash);
      $stmt->execute();
      
      header(\'Location:\' . ruta . \'?pagina=clientes\');
      exit;
    }
  }';

// Actualizar for_clientes_editar()
$patron_editar = '/public function for_clientes_editar\(\)\s*\{[^}]*UPDATE clientes set[^}]*telefono=\?,[^}]*direccion=\?,[^}]*where id=\?/s';

$reemplazo_editar = 'public function for_clientes_editar()
  {
    $sql = $this->consultas("select * from clientes where id =\'" . $_POST[\'id\'] . "\'");
    
    // Verificar si la contraseña cambió
    if (isset($_POST[\'clave\']) && !empty(trim($_POST[\'clave\']))) {
      // Si la contraseña en BD es hash y la nueva es diferente, crear nuevo hash
      if (password_verify($_POST[\'clave\'], $sql[0][\'clave\']) || $_POST[\'clave\'] == $sql[0][\'clave\']) {
        $claven = $sql[0][\'clave\']; // Mantener la misma
      } else {
        $claven = password_hash($_POST[\'clave\'], PASSWORD_DEFAULT); // Nuevo hash
      }
    } else {
      $claven = $sql[0][\'clave\']; // Mantener la actual
    }
    
    $stmt = $this->datab->prepare("UPDATE clientes set 
      ruc=?,
      razon_social=?,
      representante=?,
      clave=? 
      where id=?
    ");
    $stmt->bindParam(1, $_POST[\'ruc\']);
    $stmt->bindParam(2, $_POST[\'razon_social\']);
    $stmt->bindParam(3, $_POST[\'representante\']);
    $stmt->bindParam(4, $claven);
    $stmt->bindParam(5, $_POST[\'id\']);
    $stmt->execute();
    
    header(\'Location:\' . ruta . \'?pagina=clientes\');
    exit;
  }';

echo "<h1>Actualización de Funciones de Clientes</h1>";
echo "<p>Este script actualizará las funciones para eliminar teléfono, dirección y correo, y agregar hash de contraseñas.</p>";
echo "<p><strong>IMPORTANTE:</strong> Haz un backup antes de ejecutar.</p>";
echo "<p>Archivo: $archivo</p>";

// Mostrar cambios propuestos
echo "<h2>Cambios propuestos:</h2>";
echo "<h3>1. for_clientes_agregar()</h3>";
echo "<pre>" . htmlspecialchars($reemplazo_agregar) . "</pre>";

echo "<h3>2. for_clientes_editar()</h3>";
echo "<pre>" . htmlspecialchars($reemplazo_editar) . "</pre>";

echo "<p><strong>Nota:</strong> Este script solo muestra los cambios. Debes aplicarlos manualmente o usar un editor de código.</p>";
?>

