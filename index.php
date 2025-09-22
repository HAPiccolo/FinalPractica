<?php
include 'database.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Registro - Traumatología</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Sistema Traumatología</a>
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
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="jumbotron bg-light p-5 rounded">
                    <h1 class="display-4">Sistema de Registro de Pacientes</h1>
                    <p class="lead">Sistema especializado para el registro y seguimiento de pacientes en el área de Traumatología.</p>
                    <hr class="my-4">
                    <p>Registre toda la información relevante de los accidentados de manera organizada y eficiente.</p>
                    <a class="btn btn-primary btn-lg" href="registrar.php" role="button">Registrar nuevo paciente</a>
                    <a class="btn btn-outline-primary btn-lg" href="pacientes.php" role="button">Ver pacientes registrados</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>