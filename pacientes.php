<?php
include 'database.php';
// PHP: obtener todos los accidentes con datos del paciente
$sql = "SELECT 
            a.id AS accidente_id,
            p.id AS paciente_id,
            p.nombre_apellido,
            p.dni,
            p.edad,
            a.tipo_accidente,
            a.fecha_accidente
        FROM pacientes p
        INNER JOIN accidentes a ON a.paciente_id = p.id
        ORDER BY a.fecha_accidente DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$accidentados = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de accidentados - Traumatología</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php
    include "navbar.php";
    ?>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Listado de Accidentados </h2>

        <?php if (count($accidentados) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>DNI</th>
                            <th>Edad</th>
                            <th>Accidente</th>
                            <th>Fecha Accidente</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($accidentados as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nombre_apellido']) ?></td>
                                <td><?= htmlspecialchars($row['dni']) ?></td>
                                <td><?= htmlspecialchars($row['edad']) ?></td>
                                <td><?= htmlspecialchars($row['tipo_accidente']) ?></td>
                                <td><?= !empty($row['fecha_accidente']) ? date('d/m/Y H:i', strtotime($row['fecha_accidente'])) : '-' ?></td>
                                <td>
                                    <!-- Pasamos el id del accidente para ver el detalle de ese registro -->
                                    <a href="ver_accidente.php?id=<?= $row['accidente_id'] ?>" class="btn btn-sm btn-info">Ver</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                No hay registros de accidentes aún.
            </div>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>