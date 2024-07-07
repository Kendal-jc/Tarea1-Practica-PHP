<?php
session_start();
class Gasto {
    private $descripcion;
    private $monto;

    public function __construct($descripcion, $monto) {
        $this->descripcion = $descripcion;
        $this->monto = $monto;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getMonto() {
        return $this->monto;
    }
}

class RegistroGastos {
    private $gastos;

    public function __construct() {
        $this->gastos = isset($_SESSION['gastos']) ? $_SESSION['gastos'] : array();
    }

    public function agregarGasto($descripcion, $monto) {
        $gasto = new Gasto($descripcion, $monto);
        $this->gastos[] = $gasto;
        $_SESSION['gastos'] = $this->gastos;
    }

    public function obtenerGastos() {
        return $this->gastos;
    }

    public function obtenerTotalGastos() {
        $total = 0;
        foreach ($this->gastos as $gasto) {
            $total += $gasto->getMonto();
        }
        return $total;
    }
}



// Programa principal
$registroGastos = new RegistroGastos();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["monto"]) && isset($_POST["descripcion"])) {
        $descripcion = $_POST["descripcion"];
        $monto = floatval($_POST["monto"]);
        $registroGastos->agregarGasto($descripcion, $monto);
        $_SESSION['registroGastos'] = serialize($registroGastos);
        header("Location: index.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario PHP</title>
</head>
<body>

<h2>Formulario</h2>

<form action="index.php" method="post">
    <label for="monto">Monto:</label><br>
    <input type="number" id="monto" name="monto"><br><br>


    <label for="descripcion ">Descripción :</label><br>
    <input type="text" id="descripcion" name="descripcion"><br><br>

    <input type="submit" value="Agregar Gasto">
</form>

<h3>Listado de Gastos</h3>
<ul>
<?php
if(isset($_SESSION['registroGastos'])) {
    $registroGastos = unserialize($_SESSION['registroGastos']);
    $gastos = $registroGastos->obtenerGastos();
    foreach ($gastos as $gasto) {
        echo "<li> Descripcion:" . $gasto->getDescripcion() . "   Gastos: $" . $gasto->getMonto() . "</li>";
    }
}
?>
</ul>

<p>Total de Gastos: $<?php if(isset($registroGastos)) echo $registroGastos->obtenerTotalGastos(); ?></p>
</body>
</html>