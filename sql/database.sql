CREATE DATABASE IF NOT EXISTS catalogo_db;
USE catalogo_db;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_documento VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(150) UNIQUE NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    pais VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio_original DECIMAL(10, 2) NOT NULL,
    precio_promocion DECIMAL(10, 2) NOT NULL,
    imagen VARCHAR(255),
    categoria VARCHAR(100),
    fecha_inicio DATE,
    fecha_fin DATE,
    activo BOOLEAN DEFAULT TRUE
);

-- Insertar productos de ejemplo
INSERT INTO productos (nombre, descripcion, precio_original, precio_promocion, imagen, categoria, fecha_inicio, fecha_fin) VALUES
('Laptop Gaming Pro', 'Laptop de alto rendimiento con 16GB RAM y 512GB SSD', 1200.00, 899.99, 'laptop.jpg', 'Electrónica', '2026-09-01', '2026-10-31'),
('Smartphone Ultra', 'Teléfono inteligente con cámara de 108MP', 899.00, 699.99, 'smartphone.jpg', 'Electrónica', '2026-09-15', '2026-10-15'),
('Auriculares Bluetooth', 'Auriculares inalámbricos con cancelación de ruido', 199.99, 129.99, 'auriculares.jpg', 'Accesorios', '2026-09-01', '2026-11-30'),
('Smartwatch Fitness', 'Reloj inteligente con monitor de salud', 299.00, 199.99, 'smartwatch.jpg', 'Wearables', '2026-09-20', '2026-10-20'),
('Cámara Digital 4K', 'Cámara profesional con grabación 4K', 799.00, 599.99, 'camara.jpg', 'Fotografía', '2026-09-01', '2026-12-31'),
('Tablet Pro 11"', 'Tablet con pantalla de 11 pulgadas y stylus', 649.00, 499.99, 'tablet.jpg', 'Electrónica', '2026-09-10', '2026-10-10');
