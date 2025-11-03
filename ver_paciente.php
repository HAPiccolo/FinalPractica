<?php
include 'database.php';

if (!isset($_GET['id'])) {
    die("Paciente no encontrado.");
}

$id = $_GET['id'];

// Obtener paciente por ID
$sql = "SELECT * FROM pacientes WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paciente) {
    die("Paciente no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ver Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php
    include "./navbar.php";
    ?>
    <div class="container mt-4">


        <div class="card mb-4 shadow">



            <div class="container mt-3">

                <!-- 1. Datos del paciente -->
                <h5 class="mb-2 text-primary">1. Datos del paciente</h5>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><b>Nombre:</b> <?= htmlspecialchars($paciente['nombre_apellido']) ?></li>
                    <li class="list-group-item"><b>DNI:</b> <?= htmlspecialchars($paciente['dni']) ?></li>
                    <li class="list-group-item"><b>Sexo:</b> <?= htmlspecialchars($paciente['sexo']) ?></li>
                    <li class="list-group-item"><b>Edad:</b> <?= htmlspecialchars($paciente['edad']) ?></li>
                    <li class="list-group-item"><b>Obra social:</b> <?= htmlspecialchars($paciente['obra_social']) ?></li>
                    <li class="list-group-item"><b>Fecha Registro:</b> <?= date('d/m/Y H:i', strtotime($paciente['fecha_registro'])) ?></li>
                    <li class="list-group-item"><b>Teléfono:</b> <?= htmlspecialchars($paciente['telefono']) ?></li>
                </ul>

                <!-- 2. Datos del accidente -->
                <h5 class="mb-2 text-primary">2. Datos del accidente</h5>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><b>Fecha del accidente:</b> <?= date('d/m/Y H:i', strtotime($paciente['fecha_accidente'])) ?></li>
                    <li class="list-group-item"><b>Ubicación del accidente:</b> <?= htmlspecialchars($paciente['ubicacion']) ?></li>
                    <li class="list-group-item"><b>Accidente:</b> <?= htmlspecialchars($paciente['tipo_accidente']) ?></li>
                    <li class="list-group-item"><b>Vehículo involucrado:</b> <?= htmlspecialchars($paciente['vehiculo_involucrado']) ?></li>
                    <li class="list-group-item"><b>Uso de casco/cinturón:</b> <?= htmlspecialchars($paciente['uso_casco_cinturon']) ?></li>
                    <li class="list-group-item"><b>Estado de la vía:</b> <?= htmlspecialchars($paciente['estado_via']) ?></li>
                    <li class="list-group-item"><b>Factores de riesgo:</b> <?= htmlspecialchars($paciente['factores_riesgo']) ?></li>
                </ul>

                <!-- 3. Atención inicial -->
                <h5 class="mb-2 text-primary">3. Atención inicial</h5>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><b>Lugar de atención:</b> <?= htmlspecialchars($paciente['lugar_atencion']) ?></li>
                    <li class="list-group-item"><b>Traslado en ambulancia:</b> <?= htmlspecialchars($paciente['traslado_ambulancia']) ?></li>
                    <li class="list-group-item"><b>Tiempo de atención:</b> <?= htmlspecialchars($paciente['tiempo_atencion']) ?></li>
                </ul>

                <!-- 4. Diagnóstico traumatológico -->
                <h5 class="mb-2 text-primary">4. Diagnóstico traumatológico</h5>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><b>Región afectada:</b> <?= htmlspecialchars($paciente['region_afectada']) ?></li>
                    <li class="list-group-item"><b>Tratamiento inicial:</b> <?= htmlspecialchars($paciente['tratamiento_inicial']) ?></li>
                </ul>

                <!-- 5. Evolución / Seguimiento -->
                <h5 class="mb-2 text-primary">5. Evolución / Seguimiento</h5>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><b>Evolución / Seguimiento:</b><br><?= nl2br(htmlspecialchars($paciente['evolucion_seguimiento'])) ?></li>
                </ul>

                <div class="text-center mb-4">
                    <a href="editar.php?id=<?= $paciente['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                </div>

            </div>


            <!-- Volver-->
            <a href="pacientes.php" class="btn btn-outline-primary">Volver</a>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>