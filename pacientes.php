<?php
include 'database.php';

// Si hay filtro por DNI, modificar la consulta
$where = '';
$params = [];
if (isset($_GET['dni']) && !empty($_GET['dni'])) {
    $where = ' WHERE p.dni = ?';
    $params = [trim($_GET['dni'])];
}

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
        $where
        ORDER BY a.fecha_accidente DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
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
    <?php include "navbar.php"; ?>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Listado de Accidentados</h2>
        
        <!-- Filtro de búsqueda con botones -->
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <form class="d-flex gap-2" method="GET">
                    <div class="input-group">
                        <input type="text" name="dni" id="dni" class="form-control" 
                               placeholder="Buscar por DNI" 
                               value="<?php echo htmlspecialchars($_GET['dni'] ?? ''); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <?php if (isset($_GET['dni']) && !empty($_GET['dni'])): ?>
                        <a href="pacientes.php" class="btn btn-secondary">Limpiar</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php if (count($accidentados) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>DNI</th>
                            <th>Edad</th>
                            <th>Tipo Accidente</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($accidentados as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nombre_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($row['dni']); ?></td>
                                <td><?php echo htmlspecialchars($row['edad']); ?></td>
                                <td><?php echo htmlspecialchars($row['tipo_accidente']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_accidente'])); ?></td>
                                <td>
                                    <a href="ver_accidente.php?id=<?php echo $row['accidente_id']; ?>" 
                                       class="btn btn-sm btn-primary">Ver</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <?php if (!empty($_GET['dni'])): ?>
                    No se encontraron accidentes para el DNI: <?php echo htmlspecialchars($_GET['dni']); ?>
                <?php else: ?>
                    No hay registros de accidentes aún.
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>