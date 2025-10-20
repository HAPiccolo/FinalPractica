<?php
include 'database.php';

if (!isset($_GET['id'])) {
    die("Paciente no encontrado.");
}

$id = $_GET['id'];

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
    <title>Editar Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container mt-4">
<div class="card-header bg-primary text-white">
    <h2>Editar Paciente</h2>
</div>    

    <form action="update_paciente.php" method="POST">
        <input type="hidden" name="id" value="<?= $paciente['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Evolución / Seguimiento</label>
             <input type="text" name="evolucion_seguimiento" class="form-control" value="<?= $paciente['evolucion_seguimiento'] ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="pacientes.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
