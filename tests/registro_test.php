<?php
require __DIR__.'/../includes/registro.php';
function comprobar($condicion, $nombre) { if (!$condicion) throw new RuntimeException($nombre); echo "OK: $nombre\n"; }
$datos = ['numero_documento'=>'12345','nombre'=>'Ana','apellidos'=>'Gómez','correo'=>'ana@example.com','ciudad'=>'Roma','pais'=>'Francia','password'=>'secreto123','confirm_password'=>'secreto123'];
comprobar(validarRegistro($datos) === [], 'Registro válido');
comprobar(resolverCiudad(' roma ') === ['Roma','Italia'], 'Roma autocompleta Italia');
comprobar(resolverCiudad('BOGOTA') === ['Bogotá','Colombia'], 'Ciudad sin tilde y mayúsculas');
comprobar(resolverCiudad('paris') === ['París','Francia'], 'París autocompleta Francia');
comprobar(resolverCiudad('Desconocida') === null, 'Rechaza ciudad fuera del catálogo');
$datos['correo'] = 'incorrecto'; $datos['confirm_password'] = 'otra';
$errores = validarRegistro($datos);
comprobar(isset($errores['correo'], $errores['confirm_password']), 'Errores separados por campo');
comprobar($datos['nombre'] === 'Ana' && $datos['password'] === 'secreto123', 'Validación conserva los datos');
comprobar(escapar('"<script>') === '&quot;&lt;script&gt;', 'Valores escapados contra HTML');
