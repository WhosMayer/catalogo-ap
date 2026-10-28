<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#6b46c1"><title><?= escapar($titulo) ?> | Catálogo-ap</title><link rel="stylesheet" href="assets/ui.css"></head>
<body><a class="skip-link" href="#contenido">Saltar al contenido</a><header class="site-header"><div class="wrap nav-inner"><a class="brand" href="index.php"><span class="brand-mark" aria-hidden="true">c.</span>catálogo<span class="brand-dot">.</span></a><nav aria-label="Navegación principal">
<?php if (isset($_SESSION['user_id'])): ?><a class="nav-active" href="catalogo.php">Explorar ofertas</a><span class="nav-user"><?= escapar($_SESSION['user_name']) ?></span><a class="nav-out" href="Logout.php">Cerrar sesión <span aria-hidden="true">↗</span></a>
<?php else: ?><a href="login.php">Iniciar sesión</a><a class="button button-small" href="registro.php">Crear cuenta <span aria-hidden="true">↗</span></a><?php endif ?>
</nav></div></header>
