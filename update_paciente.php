<?php

include 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pacientes.php');
    exit;
}

$id = $_POST['id'] ?? null;
$evolucion = trim($_POST['evolucion_seguimiento'] ?? '');

if (!$id) {
    die('ID inválido.');
}

try {
    $sql = "UPDATE pacientes SET evolucion_seguimiento = :evolucion WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':evolucion' => $evolucion,
        ':id' => $id
    ]);
    header('Location: pacientes.php');
    exit;
} catch (PDOException $e) {
    die('Error BD: ' . $e->getMessage());
}
?>