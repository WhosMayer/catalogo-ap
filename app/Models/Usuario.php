<?php
namespace App\Models;
use PDO;
class Usuario {
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }
    public function buscarDuplicados(string $documento, string $correo): array {
        $stmt = $this->db->prepare('SELECT numero_documento, correo FROM usuarios WHERE numero_documento = ? OR correo = ?');
        $stmt->execute([$documento, $correo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function crear(array $datos): void {
        $stmt = $this->db->prepare('INSERT INTO usuarios (numero_documento,nombre,apellidos,correo,ciudad,pais,password) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([$datos['numero_documento'],$datos['nombre'],$datos['apellidos'],$datos['correo'],$datos['ciudad'],$datos['pais'],password_hash($datos['password'], PASSWORD_DEFAULT)]);
    }
    public function porCorreo(string $correo): ?array {
        $stmt = $this->db->prepare('SELECT id,nombre,apellidos,correo,password FROM usuarios WHERE correo = ?');
        $stmt->execute([$correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
