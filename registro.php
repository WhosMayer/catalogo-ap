<?php
session_start();
require_once 'config/database.php';
require_once 'includes/registro.php';
$campos = ['numero_documento'=>'Número de documento','nombre'=>'Nombre','apellidos'=>'Apellidos','correo'=>'Correo electrónico','ciudad'=>'Ciudad','pais'=>'País','password'=>'Contraseña','confirm_password'=>'Confirmar contraseña'];
$datos = array_fill_keys(array_keys($campos), '');
$errores = []; $mensaje = ''; $exito = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($datos as $campo=>$valor) $datos[$campo] = is_string($_POST[$campo] ?? null) ? $_POST[$campo] : '';
    foreach (['numero_documento','nombre','apellidos','correo','ciudad'] as $campo) $datos[$campo] = trim($datos[$campo]);
    $errores = validarRegistro($datos);
    $ubicacion = resolverCiudad($datos['ciudad']);
    $datos['pais'] = $ubicacion ? $ubicacion[1] : '';
    if ($ubicacion) $datos['ciudad'] = $ubicacion[0];
    if (!$errores) {
        try {
            $db = (new Database())->getConnection();
            $stmt = $db->prepare('SELECT numero_documento, correo FROM usuarios WHERE numero_documento = ? OR correo = ?');
            $stmt->execute([$datos['numero_documento'], $datos['correo']]);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $usuario) {
                if ($usuario['numero_documento'] === $datos['numero_documento']) $errores['numero_documento'] = 'Este documento ya está registrado.';
                if (strcasecmp($usuario['correo'], $datos['correo']) === 0) $errores['correo'] = 'Este correo ya está registrado.';
            }
            if (!$errores) {
                $stmt = $db->prepare('INSERT INTO usuarios (numero_documento,nombre,apellidos,correo,ciudad,pais,password) VALUES (?,?,?,?,?,?,?)');
                $stmt->execute([$datos['numero_documento'],$datos['nombre'],$datos['apellidos'],$datos['correo'],$datos['ciudad'],$datos['pais'],password_hash($datos['password'], PASSWORD_DEFAULT)]);
                $mensaje = 'Registro exitoso. Ya puedes iniciar sesión.'; $exito = true;
                $datos = array_fill_keys(array_keys($campos), '');
            }
        } catch (Exception $e) {
            $mensaje = 'No fue posible guardar el registro. Tus datos se conservan; inténtalo nuevamente.';
        }
    }
}
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Registro de usuario</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="assets/style.css"></head>
<body><main class="container py-5"><div class="card mx-auto p-4" style="max-width:650px"><h1>Registro de usuario</h1>
<?php if ($mensaje): ?><p role="alert" class="alert alert-<?= $exito ? 'success' : 'danger' ?>"><?= escapar($mensaje) ?></p><?php endif ?>
<?php if ($errores): ?><div class="alert alert-danger" role="alert"><strong>Corrige los campos indicados:</strong><ul><?php foreach ($errores as $campo=>$error): ?><li><a href="#<?= $campo ?>"><?= escapar($campos[$campo].': '.$error) ?></a></li><?php endforeach ?></ul></div><?php endif ?>
<form method="post" action="registro.php" novalidate autocomplete="off">
<?php foreach ($campos as $campo=>$etiqueta): $error = $errores[$campo] ?? ''; ?>
<div class="mb-3"><label for="<?= $campo ?>" class="form-label"><?= escapar($etiqueta) ?></label>
<input id="<?= $campo ?>" name="<?= $campo ?>" type="<?= strpos($campo, 'password') !== false ? 'password' : ($campo === 'correo' ? 'email' : 'text') ?>" class="form-control <?= $error ? 'is-invalid' : '' ?>" value="<?= escapar($datos[$campo]) ?>" <?= $campo === 'pais' ? 'readonly' : 'required' ?> <?= $campo === 'ciudad' ? 'list="ciudades"' : '' ?> aria-invalid="<?= $error ? 'true' : 'false' ?>" <?= $error ? 'aria-describedby="error-'.$campo.'"' : '' ?>>
<?php if ($error): ?><div class="invalid-feedback" id="error-<?= $campo ?>"><?= escapar($error) ?></div><?php endif ?></div>
<?php endforeach ?>
<datalist id="ciudades"><?php foreach (ciudades() as $ciudad=>$pais): ?><option value="<?= escapar($ciudad) ?>"><?= escapar($pais) ?></option><?php endforeach ?></datalist>
<button class="btn btn-primary w-100" type="submit">Registrarse</button></form><a class="mt-3" href="login.php">Iniciar sesión</a></div></main>
<script>const ciudades = <?= json_encode(ciudades(), JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>;
const normalizar = s => s.trim().normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
const ciudad = document.getElementById('ciudad'), pais = document.getElementById('pais');
function completarPais() { const clave = Object.keys(ciudades).find(c => normalizar(c) === normalizar(ciudad.value)); pais.value = clave ? ciudades[clave] : ''; }
ciudad.addEventListener('input', completarPais); completarPais();
document.querySelector('[aria-invalid="true"]')?.focus();</script></body></html>
