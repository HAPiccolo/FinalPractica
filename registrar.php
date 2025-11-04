<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // === 1. Sanitizar entradas ===
        $nombre_apellido = htmlspecialchars($_POST['nombre_apellido']);
        $dni = htmlspecialchars($_POST['dni']);
        $edad = (int)$_POST['edad'];
        $sexo = htmlspecialchars($_POST['sexo']);
        $telefono = htmlspecialchars($_POST['telefono']);
        $obra_social = htmlspecialchars($_POST['obra_social']);

        // ⚠️ Convertimos correctamente el formato del input "datetime-local"
        // Ejemplo de entrada: "2025-11-03T15:30"
        // Esto lo transforma en "2025-11-03 15:30:00"
        $fecha_accidente = date('Y-m-d H:i:s', strtotime($_POST['fecha_accidente']));

        $ubicacion = htmlspecialchars($_POST['ubicacion']);
        $tipo_accidente = isset($_POST['tipo_accidente']) ? implode(', ', $_POST['tipo_accidente']) : '';
        $vehiculo_involucrado = isset($_POST['vehiculo_involucrado']) ? implode(', ', $_POST['vehiculo_involucrado']) : '';
        $uso_casco_cinturon = htmlspecialchars($_POST['uso_casco_cinturon']);
        $estado_via = htmlspecialchars($_POST['estado_via']);
        $factores_riesgo = htmlspecialchars($_POST['factores_riesgo']);
        $lugar_atencion = htmlspecialchars($_POST['lugar_atencion']);
        $traslado_ambulancia = htmlspecialchars($_POST['traslado_ambulancia']);
        $tiempo_atencion = htmlspecialchars($_POST['tiempo_atencion']);
        $region_afectada = htmlspecialchars($_POST['region_afectada']);
        $tipo_lesion = isset($_POST['tipo_lesion']) ? implode(', ', $_POST['tipo_lesion']) : '';
        $tratamiento_inicial = htmlspecialchars($_POST['tratamiento_inicial']);
        $evolucion_seguimiento = htmlspecialchars($_POST['evolucion_seguimiento']);

        // === 2. Verificar si el paciente ya existe ===
        $stmt = $pdo->prepare("SELECT id FROM pacientes WHERE dni = ?");
        $stmt->execute([$dni]);
        $paciente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($paciente) {
            $paciente_id = $paciente['id'];
        } else {
            $sql_paciente = "INSERT INTO pacientes (nombre_apellido, dni, edad, sexo, telefono, obra_social)
                             VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql_paciente);
            $stmt->execute([$nombre_apellido, $dni, $edad, $sexo, $telefono, $obra_social]);
            $paciente_id = $pdo->lastInsertId();
        }

        // === 3. Registrar el accidente asociado al paciente ===
        $sql_accidente = "INSERT INTO accidentes (
            paciente_id, fecha_accidente, ubicacion, tipo_accidente,
            vehiculo_involucrado, uso_casco_cinturon, estado_via,
            factores_riesgo, lugar_atencion, traslado_ambulancia,
            tiempo_atencion, region_afectada, tipo_lesion,
            tratamiento_inicial, evolucion_seguimiento
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql_accidente);
        $stmt->execute([
            $paciente_id,
            $fecha_accidente,
            $ubicacion,
            $tipo_accidente,
            $vehiculo_involucrado,
            $uso_casco_cinturon,
            $estado_via,
            $factores_riesgo,
            $lugar_atencion,
            $traslado_ambulancia,
            $tiempo_atencion,
            $region_afectada,
            $tipo_lesion,
            $tratamiento_inicial,
            $evolucion_seguimiento
        ]);

        $success = "✅ Accidente registrado correctamente para el paciente con DNI: $dni";
    } catch (PDOException $e) {
        $error = "❌ Error al registrar: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Paciente - Traumatología</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php
    include "navbar.php";
    ?>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Registro de Accidente - Traumatología</h2>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="registrar.php">
            <!-- Sección 1: Datos del paciente -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>1. Datos del paciente</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dni" class="form-label">DNI / Documento</label>
                            <input type="text" class="form-control border-dark" id="dni" name="dni" required onblur="buscarPaciente()">
                            <small id="status_dni" class="text-muted"></small>

                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nombre_apellido" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control border-dark" id="nombre_apellido" name="nombre_apellido" required disabled>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="edad" class="form-label">Edad</label>
                            <input type="number" class="form-control border-dark" id="edad" name="edad" required disabled>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="sexo" class="form-label">Sexo</label>
                            <select class="form-select border-dark" id="sexo" name="sexo" required disabled>
                                <option value="">Seleccionar...</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono de contacto</label>
                            <input type="tel" class="form-control border-dark" id="telefono" name="telefono" disabled>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="obra_social" class="form-label">Obra Social / Cobertura médica</label>
                        <input type="text" class="form-control border-dark" id="obra_social" name="obra_social" disabled>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Datos del accidente -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>2. Datos del accidente</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_accidente" class="form-label">Fecha y hora del accidente</label>
                            <input type="datetime-local" class="form-control border-dark" id="fecha_accidente" name="fecha_accidente" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control border-dark" id="ubicacion" name="ubicacion" placeholder="Dirección exacta, ciudad, provincia" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de accidente (selección múltiple)</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="tipo_accidente[]" value="Caída de altura" id="caida_altura">
                                    <label class="form-check-label " for="caida_altura">Caída de altura</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="tipo_accidente[]" value="Accidente de tránsito" id="transito">
                                    <label class="form-check-label" for="transito">Accidente de tránsito</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="tipo_accidente[]" value="Accidente laboral" id="laboral">
                                    <label class="form-check-label" for="laboral">Accidente laboral</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="tipo_accidente[]" value="Doméstico" id="domestico">
                                    <label class="form-check-label" for="domestico">Doméstico</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="tipo_accidente[]" value="Deportivo" id="deportivo">
                                    <label class="form-check-label" for="deportivo">Deportivo</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="tipo_accidente[]" value="Otro" id="otro_accidente">
                                    <label class="form-check-label" for="otro_accidente">Otro (especificar)</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Vehículo involucrado (si aplica)</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="vehiculo_involucrado[]" value="Peatón" id="peaton">
                                    <label class="form-check-label" for="peaton">Peatón</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="vehiculo_involucrado[]" value="Bicicleta" id="bicicleta">
                                    <label class="form-check-label" for="bicicleta">Bicicleta</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="vehiculo_involucrado[]" value="Moto" id="moto">
                                    <label class="form-check-label" for="moto">Moto</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="vehiculo_involucrado[]" value="Auto" id="auto">
                                    <label class="form-check-label" for="auto">Auto</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="vehiculo_involucrado[]" value="Camión" id="camion">
                                    <label class="form-check-label" for="camion">Camión</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="vehiculo_involucrado[]" value="Transporte público" id="transporte_publico">
                                    <label class="form-check-label" for="transporte_publico">Transporte público</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="vehiculo_involucrado[]" value="Otro" id="otro_vehiculo">
                                    <label class="form-check-label" for="otro_vehiculo">Otro (especificar)</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="uso_casco_cinturon" class="form-label">Uso de casco / cinturón</label>
                            <select class="form-select border-dark" id="uso_casco_cinturon" name="uso_casco_cinturon">
                                <option value="">Seleccionar...</option>
                                <option value="Sí">Sí</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="estado_via" class="form-label">Estado de la vía</label>
                            <input type="text" class="form-control border-dark" id="estado_via" name="estado_via" placeholder="seca, mojada, en mal estado, etc.">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="factores_riesgo" class="form-label">Factores de riesgo</label>
                        <textarea class="form-control border-dark" id="factores_riesgo" name="factores_riesgo" rows="2" placeholder="alcohol, velocidad, trabajo sin protección, etc."></textarea>
                    </div>
                </div>
            </div>

            <!-- Sección 3: Atención inicial -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>3. Atención inicial</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="lugar_atencion" class="form-label">Lugar de atención inicial</label>
                            <select class="form-select border-dark" id="lugar_atencion" name="lugar_atencion">
                                <option value="">Seleccionar...</option>
                                <option value="Ambulancia">Ambulancia</option>
                                <option value="Guardia hospitalaria">Guardia hospitalaria</option>
                                <option value="Domicilio">Domicilio</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="traslado_ambulancia" class="form-label">Traslado en ambulancia</label>
                            <select class="form-select border-dark" id="traslado_ambulancia" name="traslado_ambulancia">
                                <option value="">Seleccionar...</option>
                                <option value="Sí">Sí</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tiempo_atencion" class="form-label">Tiempo hasta la atención médica (aprox.)</label>
                        <input type="text" class="form-control border-dark" id="tiempo_atencion" name="tiempo_atencion" placeholder="Ej: 30 minutos, 1 hora, etc.">
                    </div>
                </div>
            </div>

            <!-- Sección 4: Diagnóstico traumatológico -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>4. Diagnóstico traumatológico</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="region_afectada" class="form-label">Región afectada</label>
                            <select class="form-select border-dark" id="region_afectada" name="region_afectada">
                                <option value="">Seleccionar...</option>
                                <option value="Cabeza">Cabeza</option>
                                <option value="Cuello">Cuello</option>
                                <option value="Miembros superiores">Miembros superiores</option>
                                <option value="Miembros inferiores">Miembros inferiores</option>
                                <option value="Columna">Columna</option>
                                <option value="Pelvis">Pelvis</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de lesión (selección múltiple)</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="tipo_lesion[]" value="Fractura" id="fractura">
                                    <label class="form-check-label" for="fractura">Fractura</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="tipo_lesion[]" value="Esguince" id="esguince">
                                    <label class="form-check-label" for="esguince">Esguince</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="tipo_lesion[]" value="Luxación" id="luxacion">
                                    <label class="form-check-label" for="luxacion">Luxación</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="tipo_lesion[]" value="Contusión" id="contusion">
                                    <label class="form-check-label" for="contusion">Contusión</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="tipo_lesion[]" value="Herida" id="herida">
                                    <label class="form-check-label" for="herida">Herida</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="tipo_lesion[]" value="Politraumatismo" id="politraumatismo">
                                    <label class="form-check-label" for="politraumatismo">Politraumatismo</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input border-dark" type="checkbox" name="tipo_lesion[]" value="Otro" id="otro_lesion">
                                    <label class="form-check-label" for="otro_lesion">Otro (especificar)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 5: Tratamiento inicial -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>5. Tratamiento inicial</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="tratamiento_inicial" class="form-label">Tratamiento aplicado</label>
                        <textarea class="form-control border-dark" id="tratamiento_inicial" name="tratamiento_inicial" rows="3" placeholder="Inmovilización, férula, cirugía inmediata, observación, internación, alta médica, etc."></textarea>
                    </div>
                </div>
            </div>

            <!-- Sección 6: Evolución / Seguimiento -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>6. Evolución / Seguimiento (opcional)</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="evolucion_seguimiento" class="form-label">Notas de evolución</label>
                        <textarea class="form-control border-dark" id="evolucion_seguimiento" name="evolucion_seguimiento" rows="3" placeholder="Internación (días), complicaciones, alta definitiva, etc."></textarea>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 mb-5">
                <button type="submit" class="btn btn-primary btn-lg">Registrar Paciente</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        async function buscarPaciente() {
            const dni = document.getElementById('dni').value.trim();
            const status = document.getElementById('status_dni');

            if (dni === "") return;

            try {
                const response = await fetch(`buscar_paciente.php?dni=${dni}`);
                const data = await response.json();

                const campos = document.querySelectorAll('#nombre_apellido, #edad, #sexo, #telefono, #obra_social');

                if (data.existe) {
                    const p = data.paciente;

                    // Autocompletar datos
                    document.getElementById('nombre_apellido').value = p.nombre_apellido;
                    document.getElementById('edad').value = p.edad;
                    document.getElementById('sexo').value = p.sexo;
                    document.getElementById('telefono').value = p.telefono;
                    document.getElementById('obra_social').value = p.obra_social;

                    // Mantener deshabilitados
                    campos.forEach(campo => {
                        campo.setAttribute('disabled', true);
                        campo.classList.add('bg-light');
                    });

                    status.textContent = "✅ Paciente encontrado. Datos bloqueados (ya cargados).";
                    status.className = "text-success";

                } else {
                    // Limpiar y habilitar para nuevo paciente
                    campos.forEach(campo => {
                        campo.value = "";
                        campo.removeAttribute('disabled');
                        campo.classList.remove('bg-light');
                    });

                    status.textContent = "🆕 Paciente no encontrado. Complete los datos.";
                    status.className = "text-warning";
                }

            } catch (error) {
                console.error(error);
                status.textContent = "⚠️ Error al buscar el paciente.";
                status.className = "text-danger";
            }
        }

        // También permitir buscar al presionar Enter
        document.getElementById('dni').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarPaciente();
            }
        });
    </script>


</body>

</html>