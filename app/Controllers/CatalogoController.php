<?php
namespace App\Controllers;
use App\Models\Producto;
use Database;
use Throwable;
class CatalogoController {
    public function index(): void {
        if (!isset($_SESSION['user_id'])) { header('Location: login.php'); return; }
        $productos = []; $errorCarga = false;
        try { $productos = (new Producto((new Database())->getConnection()))->promociones(); }
        catch (Throwable $e) { $errorCarga = true; }
        $categorias = array_values(array_unique(array_column($productos, 'categoria')));
        vista('catalogo', compact('productos','categorias','errorCarga') + ['titulo'=>'Ofertas que valen la pena']);
    }
}
