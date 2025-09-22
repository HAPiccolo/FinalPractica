<?php
include 'database.php';

// Obtener todos los pacientes
$sql = "SELECT * FROM pacientes ORDER BY fecha_registro DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes Registrados - Traumatología</title>
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
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="registrar.php">Registrar Paciente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="pacientes.php">Ver Pacientes</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Pacientes Registrados</h2>
        
        <?php if (count($pacientes) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>DNI</th>
                            <th>Edad</th>
                            <th>Accidente</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pacientes as $paciente): ?>
                            <tr>
                                <td><?php echo $paciente['nombre_apellido']; ?></td>
                                <td><?php echo $paciente['dni']; ?></td>
                                <td><?php echo $paciente['edad']; ?></td>
                                <td><?php echo $paciente['tipo_accidente']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($paciente['fecha_registro'])); ?></td>
                                <td>
                                    <a href="ver_paciente.php?id=<?php echo $paciente['id']; ?>" class="btn btn-sm btn-info">Ver</a>
                                    <a href="editar_paciente.php?id=<?php echo $paciente['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                                    <a href="eliminar_paciente.php?id=<?php echo $paciente['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este registro?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                No hay pacientes registrados aún.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>