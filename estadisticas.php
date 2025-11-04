<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas - Traumatología</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>


    <?php
    include "navbar.php";
    ?>

    <main class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Estadísticas de Accidentes</h2>
            <div>
                <form id="yearForm" method="get" class="d-flex align-items-center">
                    <label for="year" class="me-2">Año:</label>
                    <select id="year" name="year" class="form-select" onchange="document.getElementById('yearForm').submit();">
                        <?php if (empty($years)) : ?>
                            <option value="<?= $selectedYear ?>"><?= $selectedYear ?></option>
                        <?php else : ?>
                            <?php foreach ($years as $y) : ?>
                                <option value="<?= $y ?>" <?= $y == $selectedYear ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Accidentados por mes (<?= htmlspecialchars($selectedYear) ?>)</h5>
                        <canvas id="monthlyChart" width="400" height="150"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6>Tipo de accidente más frecuente</h6>
                        <p class="fs-5 fw-bold"><?= htmlspecialchars($topType) ?></p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Distribución por tipo</h6>
                        <canvas id="typesChart" width="300" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Accidentados por año</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Año</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($yearly)) : ?>
                                    <tr>
                                        <td colspan="2">No hay datos registrados.</td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($yearly as $y) : ?>
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

    </main>

    <script>
        // Datos para el gráfico mensual
        const monthlyLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        const monthlyData = <?= json_encode(array_values($monthly)) ?>; // 12 valores

        const ctx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Accidentados',
                    data: monthlyData,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
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

        // Datos para el gráfico por tipo
        const types = <?= json_encode(array_map(function ($t) {
                            return $t['tipo_accidente'];
                        }, $types)) ?>;
        const typesCounts = <?= json_encode(array_map(function ($t) {
                                return intval($t['total']);
                            }, $types)) ?>;

        const ctx2 = document.getElementById('typesChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: types,
                datasets: [{
                    data: typesCounts,
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#20c997', '#fd7e14', '#6610f2']
                }]
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>