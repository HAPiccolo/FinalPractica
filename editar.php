<?php
include 'database.php';

if (!isset($_GET['id'])) {
    die("Accidente no encontrado.");
}

$id = $_GET['id'];

$sql = "SELECT a.id, a.evolucion_seguimiento, p.nombre_apellido 
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
    <title>Editar Evolución</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3>Editar evolución / seguimiento</h3>
            </div>
            <div class="card-body">
                <p><b>Paciente:</b> <?= htmlspecialchars($accidente['nombre_apellido']) ?></p>
                <form action="editar_accidente.php" method="POST">
                    <input type="hidden" name="id" value="<?= $accidente['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Evolución / Seguimiento</label>
                        <textarea name="evolucion_seguimiento" class="form-control" rows="5" required><?= htmlspecialchars($accidente['evolucion_seguimiento']) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                    <a href="ver_accidente.php?id=<?= $accidente['id'] ?>" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>