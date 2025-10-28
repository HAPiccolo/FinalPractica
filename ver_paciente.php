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
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sistema Traumatología</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="registrar.php">Registrar Paciente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pacientes.php">Ver Pacientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="estadisticas.php">Estadisticas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h1 class="mb-4 text-primary">Ver Paciente</h1>

        <div class="card mb-4 shadow">

            <!-- 1. Datos del paciente (botón) -->
            <div class="card-header p-0">
                <button class="btn btn-primary w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#datosPaciente" aria-expanded="true" aria-controls="datosPaciente">
                    1. Datos del paciente
                </button>
            </div>
            <div id="datosPaciente" class="collapse show">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><b>Nombre:</b> <?= htmlspecialchars($paciente['nombre_apellido']) ?></li>
                    <li class="list-group-item"><b>DNI:</b> <?= htmlspecialchars($paciente['dni']) ?></li>
                    <li class="list-group-item"><b>Sexo:</b> <?= htmlspecialchars($paciente['sexo']) ?></li>
                    <li class="list-group-item"><b>Edad:</b> <?= htmlspecialchars($paciente['edad']) ?></li>
                    <li class="list-group-item"><b>Obra social:</b> <?= htmlspecialchars($paciente['obra_social']) ?></li>
                    <li class="list-group-item"><b>Fecha Registro:</b> <?= date('d/m/Y H:i', strtotime($paciente['fecha_registro'])) ?></li>
                    <li class="list-group-item"><b>Teléfono:</b> <?= htmlspecialchars($paciente['telefono']) ?></li>
                </ul>
            </div>

            <!-- 2. Datos del accidente (botón) -->
            <div class="card-header p-0 mt-2">
                <button class="btn btn-primary w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#datosAccidente" aria-expanded="false" aria-controls="datosAccidente">
                    2. Datos del accidente
                </button>
            </div>
            <div id="datosAccidente" class="collapse">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><b>Fecha del accidente:</b> <?= date('d/m/Y H:i', strtotime($paciente['fecha_accidente'])) ?></li>
                    <li class="list-group-item"><b>Ubicación del accidente:</b> <?= htmlspecialchars($paciente['ubicacion']) ?></li>
                    <li class="list-group-item"><b>Accidente:</b> <?= htmlspecialchars($paciente['tipo_accidente']) ?></li>
                    <li class="list-group-item"><b>Vehículo involucrado:</b> <?= htmlspecialchars($paciente['vehiculo_involucrado']) ?></li>
                    <li class="list-group-item"><b>Uso de casco/cinturón:</b> <?= htmlspecialchars($paciente['uso_casco_cinturon']) ?></li>
                    <li class="list-group-item"><b>Estado de la vía:</b> <?= htmlspecialchars($paciente['estado_via']) ?></li>
                    <li class="list-group-item"><b>Factores de riesgo:</b> <?= htmlspecialchars($paciente['factores_riesgo']) ?></li>
                </ul>
            </div>

            <!-- 3. Atención inicial (botón) -->
            <div class="card-header p-0 mt-2">
                <button class="btn btn-primary w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#atencionInicial" aria-expanded="false" aria-controls="atencionInicial">
                    3. Atención inicial
                </button>
            </div>
            <div id="atencionInicial" class="collapse">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><b>Lugar de atención:</b> <?= htmlspecialchars($paciente['lugar_atencion']) ?></li>
                    <li class="list-group-item"><b>Traslado en ambulancia:</b> <?= htmlspecialchars($paciente['traslado_ambulancia']) ?></li>
                    <li class="list-group-item"><b>Tiempo de atención:</b> <?= htmlspecialchars($paciente['tiempo_atencion']) ?></li>
                </ul>
            </div>

            <!-- 4. Diagnóstico traumatológico (botón) -->
            <div class="card-header p-0 mt-2">
                <button class="btn btn-primary w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#diagnostico" aria-expanded="false" aria-controls="diagnostico">
                    4. Diagnóstico traumatológico
                </button>
            </div>
            <div id="diagnostico" class="collapse">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><b>Región afectada:</b> <?= htmlspecialchars($paciente['region_afectada']) ?></li>
                    <li class="list-group-item"><b>Tratamiento inicial:</b> <?= htmlspecialchars($paciente['tratamiento_inicial']) ?></li>
                </ul>
            </div>

            <!-- 5. Evolución/Seguimiento (botón) - aquí está el Edit -->
            <div class="card-header p-0 mt-2">
                <button class="btn btn-primary w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#evolucion" aria-expanded="false" aria-controls="evolucion">
                    5. Evolución / Seguimiento
                </button>
            </div>
            <div id="evolucion" class="collapse">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><b>Evolución / Seguimiento:</b><br><?= nl2br(htmlspecialchars($paciente['evolucion_seguimiento'])) ?></li>
                </ul>
                <div class="card-body text-center">
                    <a href="editar.php?id=<?php echo $paciente['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                </div>
            </div>

        </div>

        <!-- Volver-->
        <a href="pacientes.php" class="btn btn-outline-primary">Volver</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>