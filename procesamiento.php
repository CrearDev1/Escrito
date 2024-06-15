<?php
session_start();


// Definir las funciones
function agregarProducto(&$productos, $nombre, $cantidad, $valor, $modelo) {
    $productos[] = [
        'nombre' => $nombre,
        'cantidad' => $cantidad,
        'valor' => $valor,
        'modelo' => $modelo
    ];
    return $productos;
}

//Prueba agregar productos
agregarProducto($productos,"Mate",2,200,"Coquito");
agregarProducto($productos,"Matera",20,2000,"Black");

//Prueba mostrar productos
mostrarProductos($productos);

//Prueba actualizar productos
actualizarProducto($productos,"Mate",4,100,"Coquito");


//Prueba buscar producto por modelo
buscarProductoPorModelo($productos,"Coquito");

// Prueba Calcular valor total
calcularValorTotal($productos);

//Prueba filtrar productos por valor
echo "<br>";
echo buscarProductoPorValor($productos,10000);

//Prueba listar modelos disponibles
echo mostrarModelosDisponibles($productos);

//Prueba calcular valor promedio
echo calcularValorPromedio($productos);

//Prueba limpiar resultados
limpiarResultados($productos);

function buscarProductoPorModelo($productos, $modelo) {
    foreach ($productos as $producto) {
        if ($producto['modelo'] == $modelo) {
            echo "Nombre: " . $producto['nombre'] . "<br>";
            break;
        }else{
            echo "Modelo no encontrado.<br>";
            break;
        }
    }
}

function mostrarProductos($productos) {
    $result = '';
    foreach ($productos as $producto) {
        $result .= "Nombre: " . $producto['nombre'] . ", Modelo: " . $producto['modelo'] . "<br>";
    }
     echo $result;
}

function actualizarProducto(&$productos, $nombre,$cantidad, $valor, $modelo) {
    foreach ($productos as &$producto) {
        if ($producto['nombre'] == $nombre) {
            $producto['modelo'] = $modelo;
            $producto['valor'] = $valor;
            $producto['cantidad'] = $cantidad;
            break;
        }
    }
    return $productos;
}

function calcularValorTotal($productos) {
    $result = '';
    foreach ($productos as $producto) {
        $result = $producto['valor']+(int)$result;
        
    }
    echo "Valor total del inventario: $".$result;
}

function buscarProductoPorValor($productos, $valor) {
    foreach ($productos as $producto) {
        if ($producto['valor'] >= $valor) {
            return "Nombre: " . $producto['nombre'] . "<br>";
        }
    }
    return "Producto no encontrado.<br>";
}

function mostrarModelosDisponibles($productos) {
    $result = "";
    $productosDisponibles = false;
    
    foreach ($productos as $producto) {
        if ($producto['cantidad'] >= 1) {
            $infoProducto = "Nombre: " . $producto['nombre'] . "<br>";
            $result .= $infoProducto;
            $productosDisponibles = true;
        }
    }
    
    if ($productosDisponibles) {
        return $result;
    } else {
        return "No hay productos disponibles.<br>";
    }
}

function calcularValorPromedio($productos) {
    $result = 0;
    foreach ($productos as $producto) {
        $result = $producto['valor']+$result;
        
    }
    return  "Valor total del inventario: $".($result/count($productos));
}

function limpiarResultados($productos) {
    $productos[] = [
        'nombre' => "",
        'cantidad' => "",
        'valor' => "",
        'modelo' => ""
    ];
    return $productos;
}


// Inicializar el array de productos en la sesión
if (!isset($_SESSION['productos'])) {
    $_SESSION['productos'] = [];
}

$productos = $_SESSION['productos'];
$resultado = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'];
    $nombre = $_POST['nombre'] ?? '';
    $valor = $_POST['valor'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';

    switch ($accion) {
        case 'agregar':
            $productos = agregarProducto($productos, $nombre, $valor, $cantidad);
            $resultado = "Producto agregado correctamente.<br>";
            break;
        
        case 'buscar':
            $resultado = buscarProductoPorModelo($productos, $modelo);
            break;
        
        case 'mostrar':
            $resultado = mostrarProductos($productos);
            break;
        
        case 'actualizar':
            $productos = actualizarProducto($productos, $cantidad, $nombre, $valor);
            $resultado = "Producto actualizado correctamente.<br>";
            break;

        case 'limpiar':
            $_SESSION['productos'] = [];
            $resultado = "Resultados limpiados correctamente.<br>";
            session_destroy();
            break;

        default:
            $resultado = "Acción no válida.";
    }

    $_SESSION['productos'] = $productos;
    $_SESSION['resultado'] = $resultado;
}

// Redirigir de vuelta a index.php
//header("Location: formulario.php");
exit();
?>
