CREATE DATABASE IF NOT EXISTS insegura;
USE insegura;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(100),
  clave VARCHAR(100)
);

INSERT INTO usuarios (usuario, clave)
VALUES ('admin', 'admin123'), ('hacker', 'password');
