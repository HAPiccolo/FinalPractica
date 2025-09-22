<?php
$host = 'localhost';
$dbname = 'traumatologia_db';
$username = 'root';
$password = 'noxon';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Crear tabla si no existe
    $sql = "CREATE TABLE IF NOT EXISTS pacientes (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        nombre_apellido VARCHAR(255) NOT NULL,
        dni VARCHAR(20) NOT NULL UNIQUE,
        edad INT(3) NOT NULL,
        sexo ENUM('M', 'F', 'Otro') NOT NULL,
        telefono VARCHAR(20),
        obra_social VARCHAR(255),
        fecha_accidente DATETIME NOT NULL,
        ubicacion TEXT,
        tipo_accidente VARCHAR(255),
        vehiculo_involucrado VARCHAR(255),
        uso_casco_cinturon ENUM('Sí', 'No'),
        estado_via VARCHAR(100),
        factores_riesgo TEXT,
        lugar_atencion VARCHAR(255),
        traslado_ambulancia ENUM('Sí', 'No'),
        tiempo_atencion VARCHAR(50),
        region_afectada VARCHAR(255),
        tipo_lesion VARCHAR(255),
        tratamiento_inicial TEXT,
        evolucion_seguimiento TEXT,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>