<?php
ob_start();
$raiz = dirname(__DIR__);
$archivos = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($raiz, FilesystemIterator::SKIP_DOTS));
foreach ($archivos as $archivo) {
    if ($archivo->getExtension() !== 'php' || strpos($archivo->getPathname(), DIRECTORY_SEPARATOR.'.git'.DIRECTORY_SEPARATOR) !== false) continue;
    passthru(escapeshellarg(PHP_BINARY).' -l '.escapeshellarg($archivo->getPathname()), $codigo);
    if ($codigo !== 0) exit($codigo);
}
require $raiz.'/tests/registro_test.php';
require $raiz.'/tests/mvc_test.php';
ob_end_flush();
