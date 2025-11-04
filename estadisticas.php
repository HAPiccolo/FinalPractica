<?php
include "database.php";

// --- 1️⃣ Obtener años disponibles ---
$sqlYears = "SELECT DISTINCT YEAR(fecha_accidente) AS anio FROM accidentes ORDER BY anio DESC";
$years = $pdo->query($sqlYears)->fetchAll(PDO::FETCH_COLUMN);

// Año actual o seleccionado
$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : (count($years) > 0 ? $years[0] : date('Y'));

// Mes inicial y final seleccionados (por defecto todo el año)
$monthStart = isset($_GET['month_start']) ? intval($_GET['month_start']) : 1;
$monthEnd = isset($_GET['month_end']) ? intval($_GET['month_end']) : 12;

// Validación de rango de meses
if ($monthStart < 1 || $monthStart > 12) $monthStart = 1;
if ($monthEnd < 1 || $monthEnd > 12) $monthEnd = 12;
if ($monthStart > $monthEnd) [$monthStart, $monthEnd] = [$monthEnd, $monthStart]; // invertir si está al revés

// --- 2️⃣ Accidentes por mes ---
$sqlMonthly = "
    SELECT MONTH(fecha_accidente) AS mes, COUNT(*) AS total
    FROM accidentes
    WHERE YEAR(fecha_accidente) = :year
      AND MONTH(fecha_accidente) BETWEEN :mstart AND :mend
    GROUP BY mes
    ORDER BY mes
";
$stmt = $pdo->prepare($sqlMonthly);
$stmt->execute(['year' => $selectedYear, 'mstart' => $monthStart, 'mend' => $monthEnd]);
$monthlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$monthly = array_fill(1, 12, 0);
foreach ($monthlyData as $m) {
    $monthly[(int)$m['mes']] = (int)$m['total'];
}

// --- 3️⃣ Accidentes por tipo ---
$sqlTypes = "
    SELECT tipo_accidente, COUNT(*) AS total
    FROM accidentes
    WHERE YEAR(fecha_accidente) = :year
      AND MONTH(fecha_accidente) BETWEEN :mstart AND :mend
    GROUP BY tipo_accidente
    ORDER BY total DESC
";
$stmt = $pdo->prepare($sqlTypes);
$stmt->execute(['year' => $selectedYear, 'mstart' => $monthStart, 'mend' => $monthEnd]);
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);

$topType = count($types) > 0 ? $types[0]['tipo_accidente'] : 'Sin datos';

// --- 4️⃣ Accidentes por tipo de vehículo ---
$sqlVehicles = "
    SELECT vehiculo_involucrado, COUNT(*) AS total
    FROM accidentes
    WHERE YEAR(fecha_accidente) = :year
      AND MONTH(fecha_accidente) BETWEEN :mstart AND :mend
    GROUP BY vehiculo_involucrado
    ORDER BY total DESC
";
$stmt = $pdo->prepare($sqlVehicles);
$stmt->execute(['year' => $selectedYear, 'mstart' => $monthStart, 'mend' => $monthEnd]);
$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- 5️⃣ Totales por año ---
$sqlYearly = "
    SELECT YEAR(fecha_accidente) AS anio, COUNT(*) AS total
    FROM accidentes
    GROUP BY anio
    ORDER BY anio DESC
";
$yearly = $pdo->query($sqlYearly)->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas - Traumatología</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include "navbar.php"; ?>

    <main class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>📊 Estadísticas de Accidentes</h2>
        </div>

        <!-- 🔹 FILTROS -->
        <form id="filterForm" method="get" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="year" class="form-label">Año</label>
                <select id="year" name="year" class="form-select">
                    <?php foreach ($years as $y): ?>
                        <option value="<?= $y ?>" <?= $y == $selectedYear ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="month_start" class="form-label">Mes desde</label>
                <select id="month_start" name="month_start" class="form-select">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $monthStart ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="month_end" class="form-label">Mes hasta</label>
                <select id="month_end" name="month_end" class="form-select">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $monthEnd ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary w-100">Aplicar filtro</button>
            </div>
        </form>

        <!-- 🔹 PRIMERA FILA: Accidentes por mes + tipo más frecuente -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Accidentados por mes (<?= htmlspecialchars($selectedYear) ?>)</h5>
                        <canvas id="monthlyChart" height="140"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center shadow-sm d-flex align-items-center justify-content-center">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Tipo de accidente más frecuente</h5>
                        <p class="fs-4 fw-bold text-primary"><?= htmlspecialchars($topType) ?></p>
                        <p class="text-muted">Año <?= htmlspecialchars($selectedYear) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔹 SEGUNDA FILA: Tipos de accidente y tipos de vehículo -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Distribución por tipo de accidente</h5>
                        <canvas id="typesChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Distribución por tipo de vehículo</h5>
                        <canvas id="vehiclesChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔹 TERCERA FILA: Totales por año -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Totales de accidentados por año</h5>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Año</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($yearly)): ?>
                                        <tr>
                                            <td colspan="2" class="text-center">No hay datos registrados.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($yearly as $y): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($y['anio']) ?></td>
                                                <td><?= htmlspecialchars($y['total']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // --- Gráfico por mes ---
        const monthlyLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        const monthlyData = <?= json_encode(array_values($monthly)) ?>;
        new Chart(document.getElementById('monthlyChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Accidentados',
                    data: monthlyData,
                    backgroundColor: 'rgba(54,162,235,0.6)',
                    borderColor: 'rgba(54,162,235,1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });

        // --- Gráfico por tipo de accidente ---
        const typesLabels = <?= json_encode(array_column($types, 'tipo_accidente')) ?>;
        const typesCounts = <?= json_encode(array_column($types, 'total')) ?>;
        new Chart(document.getElementById('typesChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: typesLabels,
                datasets: [{
                    data: typesCounts
                }]
            }
        });

        // --- Gráfico por tipo de vehículo ---
        const vehiclesLabels = <?= json_encode(array_column($vehicles, 'vehiculo_involucrado')) ?>;
        const vehiclesCounts = <?= json_encode(array_column($vehicles, 'total')) ?>;
        new Chart(document.getElementById('vehiclesChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: vehiclesLabels,
                datasets: [{
                    data: vehiclesCounts
                }]
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>