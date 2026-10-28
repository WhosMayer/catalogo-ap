<?php
namespace App\Models;
use PDO;
class Producto {
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }
    public function promociones(): array {
        $stmt = $this->db->query('SELECT * FROM productos WHERE activo = TRUE AND fecha_inicio <= CURDATE() AND fecha_fin >= CURDATE() ORDER BY fecha_inicio DESC, id ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
