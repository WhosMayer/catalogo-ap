<?php
namespace App\Controllers;
class InicioController {
    public function index(): void {
        if (isset($_SESSION['user_id'])) { header('Location: catalogo.php'); return; }
        vista('inicio', ['titulo'=>'Encuentra tu próxima gran oferta']);
    }
}
