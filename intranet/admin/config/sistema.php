<?php session_start();
date_default_timezone_set("America/Lima");
class Conectar
{
  public $isConn;
  protected $datab;
  // connect to db
  public function __construct(
    $username = USER,
    $password = PASSWORD,
    $host = HOST,
    $dbname = DB_NAME,
    $options = []
  ) {
    $this->isConn = TRUE;
    try {
      $this->datab = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
      $this->datab->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->datab->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception($e->getMessage());
    }
  }


  // disconnect from db
  public function Disconnect()
  {
    $this->datab = NULL;
    $this->isConn = FALSE;
  }
  // get row
  public function consulta($query, $params = [])
  {
    try {
      $stmt = $this->datab->prepare($query);
      $stmt->execute($params);
      return $stmt->fetch();
    } catch (PDOException $e) {
      throw new Exception($e->getMessage());
    }
  }
  // get rows
  public function consultas($query, $params = [])
  {
    try {
      $stmt = $this->datab->prepare($query);
      $stmt->execute($params);
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      throw new Exception($e->getMessage());
    }
  }



  // insert row
  public function insertRow($query, $params = [])
  {
    try {
      $stmt = $this->datab->prepare($query);
      $stmt->execute($params);
      return TRUE;
    } catch (PDOException $e) {
      throw new Exception($e->getMessage());
    }
  }
  // update row
  public function updateRow($query, $params = [])
  {
    $this->insertRow($query, $params);
  }
  // delete row
  public function deleteRow($query, $params = [])
  {
    $this->insertRow($query, $params);
  }


  static  public function cortar($t, $c)
  {
    $texto = strip_tags($t);
    if (mb_strlen($texto) > $c) {
      $tx = mb_substr($texto, 0, $c, 'utf-8') . '...';
    } else {
      $tx = mb_substr($texto, 0, $c, 'utf-8');
    }
    return  $tx;
  }



  static  public function calular_p($precio, $descuento)
  {
    $cien = 100;
    $r1 = $cien * $descuento;
    $r = $r1 / $precio;
    $re = $cien - $r;
    return $re;
  }



  static  public function ar($d)
  {
    if (is_object($d)) {
      // Gets the properties of the given object
      // with get_object_vars function
      $d = get_object_vars($d);
    }

    if (is_array($d)) {
      // Return array converted to object Using __FUNCTION__ (Magic constant) for recursive call
      return array_map(__FUNCTION__, $d);
    } else {
      // Return array
      return $d;
    }
  }

  ///////////////////////////////////////
  public function codigo($query)
  {
    try {
      $stmt = $this->datab->prepare("select * from $query order by id desc limit 1");
      $stmt->execute();
      $sql = $stmt->fetchAll();



      if (count($sql)) {
        $sqcc = $sql[0]['id'];
      } else {
        $sqcc = '0';
      }
      $cdgo = substr(str_shuffle(str_repeat('0123456789', 5)), 0, 3);
      $cdgo0=$cdgo+ '1';

      $co_orden1 =  $cdgo0.$sqcc ;
      $orco = '';
      $codigo = $orco . $co_orden1;
      return $codigo;


      // return $stmt->fetchAll();
    } catch (PDOException $e) {
      throw new Exception($e->getMessage());
    }
  }
  ///////////////////////////////////////



  static  public function cal_dias($fecha_actual)
  {

    $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
    $dia = $dias[date('w', strtotime($fecha_actual))];
    return $dia;
  }
  static  public function suma_dias($fecha_actual, $dias)
  {
    $fechas_futuro = strtotime('+' . $dias . ' day', strtotime($fecha_actual));
    $fechas_futuro = date('d-m-Y', $fechas_futuro);
    return $fechas_futuro;
  }

 static  public  function conocerDiaSemanaFecha($fecha)
  {
    $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
    $dia = $dias[date('w', strtotime($fecha))];
    return $dia;
  }
  

  static  public function fecha_a_letra($fecha)
  {   
    $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
    $dia = $dias[date('w', strtotime($fecha))];
    $num = date("j", strtotime($fecha));
    $anno = date("Y", strtotime($fecha));
    $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
    $mes = $mes[(date('m', strtotime($fecha)) * 1) - 1];
    return $dia . ', ' . $num . ' de ' . $mes . ' del ' . $anno;
  }
 














}
