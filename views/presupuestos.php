<?php
$obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
$obraModel = new Obra();
$obra = $obraModel->getById($obra_id);
?>

<div class="container-fluid">
    <div class="card bg-gradient-warning text-white mb-4 shadow">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-currency-dollar"></i> Presupuesto - <?php echo htmlspecialchars($obra['nombre']); ?></h2>
                    <p class="mb-0">Consolidado por capítulos</p>
                </div>
                <!-- Reemplazar el botón -->
<a href="?controller=Obra&action=ver&id=<?php echo $obra_id; ?>" class="btn btn-light">
    <i class="bi bi-arrow-left"></i> Atrás
</a>
            </div>
        </div>
    </div>

    <div id="loadingPresupuesto" class="text-center py-5">
        <div class="spinner-border text-primary"></div>
        <p>Calculando presupuesto...</p>
    </div>

    <div id="presupuestoContainer" class="d-none"></div>
</div>

<script>
function loadPresupuesto() {
    const obra_id = <?php echo $obra_id; ?>;
    
    fetch(`?controller=Presupuesto&action=ajaxData&obra_id=${obra_id}`)
        .then(res => res.json())
        .then(response => {
            document.getElementById('loadingPresupuesto').classList.add('d-none');
            
            if (response.success && response.data) {
                document.getElementById('presupuestoContainer').classList.remove('d-none');
                const data = response.data;
                let html = '';
                let granTotal = 0;
                
                for (let grupoId in data.grupos) {
                    const grupo = data.grupos[grupoId];
                    granTotal += grupo.subtotal;
                    
                    html += `
                        <div class="card mb-4 shadow animate__animated animate__fadeInUp">
                            <div class="card-header bg-dark text-white">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="bi bi-folder2"></i> ${grupo.grupo_codigo} - ${grupo.grupo_nombre}</strong>
                                    <span>Subtotal: Bs. ${grupo.subtotal.toFixed(2)}</span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr><th>Código</th><th>Descripción</th><th>Unidad</th><th>Cantidad</th><th>Precio Unitario</th><th>Total</th></tr>
                                    </thead>
                                    <tbody>`;
                    
                    grupo.partidas.forEach(p => {
                        html += `<tr>
                                    <td>${p.codigo}</td>
                                    <td>${p.descripcion}</td>
                                    <td>${p.unidad}</td>
                                    <td class="text-end">${p.cantidad.toFixed(4)}</td>
                                    <td class="text-end">${p.precio_unitario.toFixed(2)}</td>
                                    <td class="text-end text-primary fw-bold">${p.costo_total.toFixed(2)}</td>
                                </tr>`;
                    });
                    
                    html += `</tbody></table></div></div>`;
                }
                
                html += `
                    <div class="alert alert-success text-center shadow">
                        <i class="bi bi-trophy fs-1"></i>
                        <h3>TOTAL GENERAL DEL PROYECTO</h3>
                        <div class="display-4 fw-bold">Bs. ${granTotal.toFixed(2)}</div>
                    </div>
                `;
                
                document.getElementById('presupuestoContainer').innerHTML = html;
            } else {
                document.getElementById('presupuestoContainer').innerHTML = `
                    <div class="alert alert-warning text-center">
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                        <p>No hay datos de presupuesto. Cree grupos, partidas y cómputos primero.</p>
                    </div>
                `;
                document.getElementById('presupuestoContainer').classList.remove('d-none');
            }
        });
}

loadPresupuesto();
</script>

<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
</style>