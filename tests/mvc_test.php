<?php
if (PHP_SAPI === 'cli' && session_status() !== PHP_SESSION_ACTIVE) ini_set('session.save_path', sys_get_temp_dir());
require_once __DIR__.'/../app/bootstrap.php';
function verificarMVC($condicion, $nombre) {
    if (!$condicion) throw new RuntimeException($nombre);
    echo "OK MVC: $nombre\n";
}
$dbPrueba = new PDO('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$dbPrueba->exec('CREATE TABLE usuarios (id INTEGER PRIMARY KEY,numero_documento TEXT UNIQUE,nombre TEXT,apellidos TEXT,correo TEXT UNIQUE,ciudad TEXT,pais TEXT,password TEXT)');
$modelo = new App\Models\Usuario($dbPrueba);
$nuevo = ['numero_documento'=>'MVC01','nombre'=>'Ana','apellidos'=>'Gómez','correo'=>'mvc@example.test','ciudad'=>'Roma','pais'=>'Italia','password'=>'Prueba!123'];
$modelo->crear($nuevo);
$usuario = $modelo->porCorreo($nuevo['correo']);
verificarMVC($usuario && password_verify($nuevo['password'], $usuario['password']), 'Modelo guarda y recupera usuario con contraseña protegida');
verificarMVC(count($modelo->buscarDuplicados('MVC01', 'otro@example.test')) === 1, 'Modelo detecta documento duplicado');
verificarMVC(count($modelo->buscarDuplicados('OTRO', 'mvc@example.test')) === 1, 'Modelo detecta correo duplicado');
verificarMVC($modelo->porCorreo('nadie@example.test') === null, 'Correo inexistente devuelve null');
$dbPrueba->sqliteCreateFunction('CURDATE', fn()=> '2026-10-27');
$dbPrueba->exec('CREATE TABLE productos (id INTEGER PRIMARY KEY,nombre TEXT,activo INTEGER,fecha_inicio TEXT,fecha_fin TEXT)');
$dbPrueba->exec("INSERT INTO productos VALUES (1,'Vigente',1,'2026-10-01','2026-10-31'),(2,'Vencido',1,'2026-09-01','2026-10-01'),(3,'Futuro',1,'2026-11-01','2026-11-30'),(4,'Inactivo',0,'2026-10-01','2026-10-31')");
$promos = (new App\Models\Producto($dbPrueba))->promociones();
verificarMVC(count($promos) === 1 && $promos[0]['nombre'] === 'Vigente', 'Catálogo excluye vencidos, futuros e inactivos');
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = $nuevo + ['confirm_password'=>'otra','_token'=>tokenFormulario()];
$_POST['correo'] = 'correo-invalido';
ob_start(); (new App\Controllers\AuthController())->registro(); $html = ob_get_clean();
verificarMVC(str_contains($html,'value="Ana"') && str_contains($html,'value="Prueba!123"'), 'Controlador y vista conservan los datos ante errores');
verificarMVC(str_contains($html,'id="error-correo"') && str_contains($html,'id="error-confirm_password"'), 'Vista localiza ambos errores');
verificarMVC(str_contains($html,'value="Italia"'), 'País correcto llega a la vista');
$_POST['_token'] = 'incorrecto';
ob_start(); (new App\Controllers\AuthController())->registro(); $html = ob_get_clean();
verificarMVC(str_contains($html,'La sesión del formulario venció') && str_contains($html,'value="Ana"'), 'Rechaza token inválido conservando los datos');
verificarMVC(!str_contains($html,'Warning:') && !str_contains($html,'Fatal error:'), 'Renderizado sin errores PHP');
