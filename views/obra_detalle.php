<?php
$obra_id = $obra['id'];
?>

<div class="container-fluid">
    <div class="card bg-gradient-primary text-white mb-4 shadow">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-building"></i> <?php echo htmlspecialchars($obra['nombre']); ?></h2>
                    <p class="mb-0">
                        <i class="bi bi-code"></i> <?php echo htmlspecialchars($obra['codigo']); ?> | 
                        <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($obra['ubicacion']); ?> |
                        <i class="bi bi-flag"></i> Estado: 
                        <span class="badge bg-light text-dark"><?php echo $obra['estado']; ?></span>
                    </p>
                </div>
                <a href="?controller=Obra" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Volver a Obras
                </a>
            </div>
        </div>
    </div>

    <!-- Resto del contenido igual... -->
    <div class="row mb-4">
        <!-- Tarjetas de estadísticas -->
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div><h6>Total Grupos</h6><h3><?php echo $resumen['total_grupos'] ?? 0; ?></h3></div>
                        <i class="bi bi-folder2-open fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div><h6>Total Partidas</h6><h3><?php echo $resumen['total_partidas'] ?? 0; ?></h3></div>
                        <i class="bi bi-tags fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div><h6>Total Cómputos</h6><h3><?php echo $resumen['total_computos'] ?? 0; ?></h3></div>
                        <i class="bi bi-rulers fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div><h6>Presupuesto Total</h6><h4>Bs. <?php echo number_format($presupuesto['total_presupuesto'] ?? 0, 2); ?></h4></div>
                        <i class="bi bi-currency-dollar fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Módulos -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow">
                <div class="card-header bg-dark text-white"><i class="bi bi-calculator"></i> Análisis de Precios Unitarios</div>
                <div class="card-body text-center">
                    <p>Gestiona los recursos y precios unitarios de cada partida.</p>
                    <a href="?controller=Apu&obra_id=<?php echo $obra_id; ?>" class="btn btn-success"><i class="bi bi-eye"></i> Ver APU</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow">
                <div class="card-header bg-dark text-white"><i class="bi bi-rulers"></i> Cómputos Métricos</div>
                <div class="card-body text-center">
                    <p>Registra las cantidades de obra por partida.</p>
                    <a href="?controller=Computo&obra_id=<?php echo $obra_id; ?>" class="btn btn-primary"><i class="bi bi-eye"></i> Ver Cómputos</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow">
                <div class="card-header bg-dark text-white"><i class="bi bi-currency-dollar"></i> Presupuesto</div>
                <div class="card-body text-center">
                    <p>Consulta el presupuesto consolidado por capítulos.</p>
                    <a href="?controller=Presupuesto&obra_id=<?php echo $obra_id; ?>" class="btn btn-warning"><i class="bi bi-eye"></i> Ver Presupuesto</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow">
                <div class="card-header bg-dark text-white"><i class="bi bi-calendar-week"></i> Cronograma</div>
                <div class="card-body text-center">
                    <p>Visualiza el cronograma de actividades en Gantt.</p>
                    <a href="?controller=Cronograma&obra_id=<?php echo $obra_id; ?>" class="btn btn-info"><i class="bi bi-eye"></i> Ver Cronograma</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>