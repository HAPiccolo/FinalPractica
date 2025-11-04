<?php
include 'database.php';

if (!isset($_GET['id'])) {
    die("Accidente no especificado.");
}

$id = $_GET['id'];

// Consulta: unir accidente con datos del paciente
$sql = "SELECT 
            a.*, 
            p.nombre_apellido, 
            p.dni, 
            p.edad, 
            p.sexo, 
            p.telefono, 
            p.obra_social, 
            p.fecha_registro AS fecha_registro_paciente
        FROM accidentes a
        INNER JOIN pacientes p ON a.paciente_id = p.id
        WHERE a.id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$accidente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$accidente) {
    die("Accidente no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle del Accidente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="container mt-4">
        <div class="card mb-4 shadow">
            <div class="container mt-3">

                <h5 class="mb-2 text-primary">1. Datos del paciente</h5>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><b>Nombre:</b> <?= htmlspecialchars($accidente['nombre_apellido']) ?></li>
                    <li class="list-group-item"><b>DNI:</b> <?= htmlspecialchars($accidente['dni']) ?></li>
                    <li class="list-group-item"><b>Sexo:</b> <?= htmlspecialchars($accidente['sexo']) ?></li>
                    <li class="list-group-item"><b>Edad:</b> <?= htmlspecialchars($accidente['edad']) ?></li>
                    <li class="list-group-item"><b>Obra social:</b> <?= htmlspecialchars($accidente['obra_social']) ?></li>
                    <li class="list-group-item"><b>Fecha Registro Paciente:</b> <?= date('d/m/Y H:i', strtotime($accidente['fecha_registro_paciente'])) ?></li>
                    <li class="list-group-item"><b>Teléfono:</b> <?= htmlspecialchars($accidente['telefono']) ?></li>
                </ul>

                <h5 class="mb-2 text-primary">2. Datos del accidente</h5>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><b>Fecha del accidente:</b> <?= date('d/m/Y H:i', strtotime($accidente['fecha_accidente'])) ?></li>
                    <li class="list-group-item"><b>Ubicación:</b> <?= htmlspecialchars($accidente['ubicacion']) ?></li>
                    <li class="list-group-item"><b>Tipo de accidente:</b> <?= htmlspecialchars($accidente['tipo_accidente']) ?></li>
                    <li class="list-group-item"><b>Vehículo involucrado:</b> <?= htmlspecialchars($accidente['vehiculo_involucrado']) ?></li>
                    <li class="list-group-item"><b>Uso de casco/cinturón:</b> <?= htmlspecialchars($accidente['uso_casco_cinturon']) ?></li>
                    <li class="list-group-item"><b>Estado de la vía:</b> <?= htmlspecialchars($accidente['estado_via']) ?></li>
                    <li class="list-group-item"><b>Factores de riesgo:</b> <?= htmlspecialchars($accidente['factores_riesgo']) ?></li>
                </ul>

                <h5 class="mb-2 text-primary">3. Atención inicial</h5>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><b>Lugar de atención:</b> <?= htmlspecialchars($accidente['lugar_atencion']) ?></li>
                    <li class="list-group-item"><b>Traslado en ambulancia:</b> <?= htmlspecialchars($accidente['traslado_ambulancia']) ?></li>
                    <li class="list-group-item"><b>Tiempo de atención:</b> <?= htmlspecialchars($accidente['tiempo_atencion']) ?></li>
                </ul>

                <h5 class="mb-2 text-primary">4. Diagnóstico traumatológico</h5>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><b>Región afectada:</b> <?= htmlspecialchars($accidente['region_afectada']) ?></li>
                    <li class="list-group-item"><b>Tipo de lesión:</b> <?= htmlspecialchars($accidente['tipo_lesion']) ?></li>
                    <li class="list-group-item"><b>Tratamiento inicial:</b> <?= htmlspecialchars($accidente['tratamiento_inicial']) ?></li>
                </ul>

                <h5 class="mb-2 text-primary">5. Evolución / Seguimiento</h5>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><b>Evolución / Seguimiento:</b><br><?= nl2br(htmlspecialchars($accidente['evolucion_seguimiento'])) ?></li>
                </ul>

                <div class="text-center mb-4">
                    <a href="editar.php?id=<?= $accidente['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="index.php" class="btn btn-secondary btn-sm">Volver</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>