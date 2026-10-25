<?php
function escapar($valor) { return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8'); }
function ciudades() {
    return ['Bogotá'=>'Colombia', 'Medellín'=>'Colombia', 'Cali'=>'Colombia', 'Barranquilla'=>'Colombia', 'Cartagena'=>'Colombia', 'Bucaramanga'=>'Colombia', 'Pereira'=>'Colombia', 'Manizales'=>'Colombia', 'Santa Marta'=>'Colombia', 'Cúcuta'=>'Colombia', 'Roma'=>'Italia', 'Milán'=>'Italia', 'Nápoles'=>'Italia', 'Turín'=>'Italia', 'Florencia'=>'Italia', 'Venecia'=>'Italia', 'Bolonia'=>'Italia', 'París'=>'Francia', 'Marsella'=>'Francia', 'Lyon'=>'Francia', 'Toulouse'=>'Francia', 'Niza'=>'Francia', 'Nantes'=>'Francia', 'Burdeos'=>'Francia'];
}
function resolverCiudad($entrada) {
    $normalizar = function ($s) { return strtolower(strtr(trim($s), ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','Á'=>'a','É'=>'e','Í'=>'i','Ó'=>'o','Ú'=>'u'])); };
    foreach (ciudades() as $ciudad=>$pais) {
        if ($normalizar($ciudad) === $normalizar($entrada)) return [$ciudad, $pais];
    }
    return null;
}
function validarRegistro($datos) {
    $errores = [];
    $limites = ['numero_documento'=>20,'nombre'=>100,'apellidos'=>100,'correo'=>150,'ciudad'=>100];
    foreach ($limites as $campo=>$limite) {
        if (trim($datos[$campo] ?? '') === '') $errores[$campo] = 'Este campo es obligatorio.';
        elseif (strlen($datos[$campo]) > $limite) $errores[$campo] = "Usa como máximo $limite caracteres.";
    }
    if (!isset($errores['correo']) && !filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) $errores['correo'] = 'Ingresa un correo electrónico válido.';
    if (!isset($errores['ciudad']) && !resolverCiudad($datos['ciudad'])) $errores['ciudad'] = 'Selecciona una ciudad del catálogo.';
    if (strlen($datos['password'] ?? '') < 6) $errores['password'] = 'Usa al menos 6 caracteres.';
    if (($datos['password'] ?? '') !== ($datos['confirm_password'] ?? '')) $errores['confirm_password'] = 'Las contraseñas no coinciden.';
    return $errores;
}
