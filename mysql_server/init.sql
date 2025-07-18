-- Crear base de datos principal
CREATE DATABASE IF NOT EXISTS insegura;
USE insegura;

-- Crear tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(255),
    clave VARCHAR(255),
    nombre VARCHAR(100) DEFAULT NULL
);

-- Insertar usuarios iniciales
INSERT INTO usuarios (usuario, clave, nombre) VALUES
('admin', 'admin1234', 'Administrador'),
('user', 'userpass', 'Usuario'),
('diego', '1234', 'Diego');

-- Crear tabla de comentarios
CREATE TABLE IF NOT EXISTS comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(255),
    comentario TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear usuarios para acceso remoto
CREATE USER IF NOT EXISTS 'webapp'@'%' IDENTIFIED BY 'insegura';
GRANT ALL PRIVILEGES ON insegura.* TO 'webapp'@'%' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY 'toor';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;

FLUSH PRIVILEGES;
