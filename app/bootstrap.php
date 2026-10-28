<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once dirname(__DIR__).'/config/database.php';
require_once dirname(__DIR__).'/includes/registro.php';
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) return;
    $file = __DIR__.'/'.str_replace('\\', '/', substr($class, strlen($prefix))).'.php';
    if (is_file($file)) require_once $file;
});
function vista($nombre, $variables = []) {
    extract($variables, EXTR_SKIP);
    require __DIR__.'/Views/'.$nombre.'.php';
}
function tokenFormulario() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function tokenValido($post) {
    return isset($_SESSION['csrf'], $post['_token']) && is_string($post['_token']) && hash_equals($_SESSION['csrf'], $post['_token']);
}
function dinero($valor) { return '$'.number_format((float)$valor, 2, '.', ','); }
function iconoProducto($categoria) {
    $tipo = 'laptop';
    if ($categoria === 'Accesorios') $tipo = 'auriculares';
    if ($categoria === 'Fotografía') $tipo = 'camara';
    if ($categoria === 'Wearables') $tipo = 'reloj';
    $figuras = [
        'laptop'=>'<rect x="55" y="35" width="210" height="135" rx="12" fill="#27243e"/><rect x="65" y="45" width="190" height="113" rx="5" fill="#c9bef9"/><path d="M65 144 130 70 185 124 222 90 255 132v26H65Z" fill="#8870dc"/><circle cx="218" cy="72" r="15" fill="#f0eaff"/><path d="M55 170 27 195q-4 8 10 8h246q14 0 10-8l-28-25Z" fill="#665786"/><path d="M131 180h58l8 9h-74Z" fill="#aaa0c3"/>',
        'auriculares'=>'<path d="M83 132V99a77 77 0 0 1 154 0v33" fill="none" stroke="#342c52" stroke-width="20"/><path d="M83 105a77 77 0 0 1 154 0" fill="none" stroke="#9d89d9" stroke-width="8"/><rect x="64" y="109" width="49" height="82" rx="22" fill="#57457f"/><rect x="207" y="109" width="49" height="82" rx="22" fill="#57457f"/><rect x="87" y="116" width="25" height="67" rx="12" fill="#aa96e0"/><rect x="207" y="116" width="25" height="67" rx="12" fill="#aa96e0"/>',
        'camara'=>'<path d="M65 73h45l15-23h69l16 23h45q13 0 13 13v99q0 13-13 13H65q-13 0-13-13V86q0-13 13-13Z" fill="#403650"/><rect x="60" y="64" width="32" height="11" rx="4" fill="#766584"/><rect x="58" y="105" width="204" height="62" fill="#5f5073"/><circle cx="164" cy="136" r="58" fill="#2c263a"/><circle cx="164" cy="136" r="44" fill="#9c8ac1"/><circle cx="164" cy="136" r="32" fill="#39304f"/><circle cx="164" cy="136" r="19" fill="#65558c"/><circle cx="154" cy="125" r="8" fill="#d9d0f0"/><rect x="223" y="84" width="24" height="12" rx="3" fill="#d9d0f0"/>',
        'reloj'=>'<rect x="132" y="10" width="56" height="210" rx="20" fill="#b4a2d5"/><rect x="107" y="65" width="106" height="102" rx="27" fill="#302a40"/><rect x="116" y="74" width="88" height="84" rx="20" fill="#7964ab"/><circle cx="160" cy="116" r="29" fill="none" stroke="#e0d6f4" stroke-width="5"/><path d="M160 95v23l16 10" fill="none" stroke="#fff" stroke-width="5"/>'
    ];
    return '<svg viewBox="0 0 320 240" role="img" aria-label="Ilustración del producto"><ellipse cx="160" cy="213" rx="116" ry="10" fill="#302449" opacity=".08"/>'.$figuras[$tipo].'</svg>';
}
