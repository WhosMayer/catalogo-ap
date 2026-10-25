<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: catalogo.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Catálogo de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 text-center">
                <h1 class="display-4 mb-4">Catálogo de Productos en Promoción</h1>
                <p class="badge bg-success">Versión 1.1 · Registro mejorado y detalles de productos</p><p class="lead mb-5">Descubre las mejores ofertas en tecnología y accesorios</p>
                <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                    <a href="login.php" class="btn btn-primary btn-lg px-4">Iniciar Sesión</a>
                    <a href="registro.php" class="btn btn-outline-primary btn-lg px-4">Registrarse</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>