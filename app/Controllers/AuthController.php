<?php
namespace App\Controllers;
use App\Models\Usuario;
use Database;
use Throwable;
class AuthController {
    public function registro(): void {
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
            if (!tokenValido($_POST)) $mensaje = 'La sesión del formulario venció. Tus datos se conservan; vuelve a enviarlo.';
            elseif (!$errores) {
                try {
                    $usuarios = new Usuario((new Database())->getConnection());
                    foreach ($usuarios->buscarDuplicados($datos['numero_documento'], $datos['correo']) as $usuario) {
                        if ($usuario['numero_documento'] === $datos['numero_documento']) $errores['numero_documento'] = 'Este documento ya está registrado.';
                        if (strcasecmp($usuario['correo'], $datos['correo']) === 0) $errores['correo'] = 'Este correo ya está registrado.';
                    }
                    if (!$errores) {
                        $usuarios->crear($datos);
                        $mensaje = 'Registro exitoso. Ya puedes iniciar sesión.';
                        $exito = true;
                        $datos = array_fill_keys(array_keys($campos), '');
                    }
                } catch (Throwable $e) {
                    $mensaje = 'No fue posible guardar el registro. Tus datos se conservan; inténtalo nuevamente.';
                }
            }
        }
        vista('auth/registro', compact('campos','datos','errores','mensaje','exito') + ['titulo'=>'Crea tu cuenta']);
    }
    public function login(): void {
        if (isset($_SESSION['user_id'])) { header('Location: catalogo.php'); return; }
        $correo = ''; $mensaje = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = is_string($_POST['correo'] ?? null) ? trim($_POST['correo']) : '';
            $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
            if (!tokenValido($_POST)) $mensaje = 'La sesión del formulario venció. Inténtalo nuevamente.';
            elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL) || $password === '') $mensaje = 'Ingresa un correo válido y tu contraseña.';
            else {
                try {
                    $usuario = (new Usuario((new Database())->getConnection()))->porCorreo($correo);
                    if ($usuario && password_verify($password, $usuario['password'])) {
                        session_regenerate_id(true);
                        $_SESSION['user_id'] = $usuario['id'];
                        $_SESSION['user_name'] = $usuario['nombre'].' '.$usuario['apellidos'];
                        $_SESSION['user_email'] = $usuario['correo'];
                        header('Location: catalogo.php'); return;
                    }
                    $mensaje = 'El correo o la contraseña no son correctos.';
                } catch (Throwable $e) { $mensaje = 'No pudimos conectar con el servicio. Inténtalo nuevamente.'; }
            }
        }
        vista('auth/login', compact('correo','mensaje') + ['titulo'=>'Bienvenido de nuevo']);
    }
    public function logout(): void {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php');
    }
}
