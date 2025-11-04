<?php
include 'database.php';

if (isset($_GET['dni'])) {
    $dni = htmlspecialchars($_GET['dni']);

    $stmt = $pdo->prepare("SELECT * FROM pacientes WHERE dni = ?");
    $stmt->execute([$dni]);
    $paciente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($paciente) {
        echo json_encode(['existe' => true, 'paciente' => $paciente]);
    } else {
        echo json_encode(['existe' => false]);
    }
}
