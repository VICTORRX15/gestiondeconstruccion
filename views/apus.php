<?php
$obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
$obraModel = new Obra();
$obra = $obraModel->getById($obra_id);
$grupoModel = new Grupo();
$grupos = $grupoModel->getAllByObra($obra_id);
?>

<div class="container-fluid">
    <!-- Header de la obra -->
    <div class="card bg-gradient-primary text-white mb-4 shadow">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-building"></i> <?php echo htmlspecialchars($obra['nombre']); ?></h2>
                    <p class="mb-0"><i class="bi bi-code"></i> <?php echo htmlspecialchars($obra['codigo']); ?> | 
                    <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($obra['ubicacion']); ?></p>
                </div>
                <!-- Reemplazar el botón "Cambiar Obra" por -->
<a href="?controller=Obra&action=ver&id=<?php echo $obra_id; ?>" class="btn btn-light">
    <i class="bi bi-arrow-left"></i> Atrás
</a>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-calculator-fill text-success"></i> Análisis de Precios Unitarios (APU)</h3>
        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#grupoModal" onclick="resetGrupoForm()">
                <i class="bi bi-folder-plus"></i> Nuevo Grupo
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#partidaModal" onclick="resetPartidaForm()">
                <i class="bi bi-plus-circle"></i> Nueva Partida
            </button>
        </div>
    </div>

    <!-- Grupos y Partidas -->
    <div class="accordion" id="accordionGrupos">
        <?php foreach ($grupos as $index => $grupo): ?>
            <?php 
            $partidaModel = new Partida();
            $partidas = $partidaModel->getAllByGrupo($grupo['id']);
            ?>
            <div class="accordion-item mb-3 shadow-sm">
                <h2 class="accordion-header" id="heading<?php echo $grupo['id']; ?>">
                    <button class="accordion-button <?php echo $index > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $grupo['id']; ?>">
                        <div class="d-flex justify-content-between w-100 me-3">
                            <span><i class="bi bi-folder2-open text-primary"></i> <strong><?php echo $grupo['codigo']; ?> - <?php echo $grupo['nombre']; ?></strong></span>
                            <span class="badge bg-secondary rounded-pill"><?php echo count($partidas); ?> partidas</span>
                        </div>
                    </button>
                </h2>
                <div id="collapse<?php echo $grupo['id']; ?>" class="accordion-collapse collapse <?php echo $index == 0 ? 'show' : ''; ?>" data-bs-parent="#accordionGrupos">
                    <div class="accordion-body">
                        <div class="row">
                            <?php foreach ($partidas as $partida): ?>
                                <?php 
                                $apuModel = new Apu();
                                $precio_unitario = $apuModel->getPrecioUnitario($partida['id']);
                                $recursos = $apuModel->getRecursosByPartida($partida['id']);
                                ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-header bg-light">
                                            <strong><i class="bi bi-tag"></i> <?php echo $partida['codigo']; ?></strong>
                                            <span class="float-end text-muted"><?php echo $partida['unidad']; ?></span>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title"><?php echo $partida['descripcion']; ?></h6>
                                            <div class="alert alert-success py-2 text-center mt-2">
                                                <small>Precio Unitario:</small>
                                                <strong class="fs-5">Bs. <?php echo number_format($precio_unitario, 2); ?></strong>
                                            </div>
                                            <button class="btn btn-sm btn-info w-100" onclick="verDetalleAPU(<?php echo $partida['id']; ?>, '<?php echo $partida['codigo']; ?> - <?php echo addslashes($partida['descripcion']); ?>')">
                                                <i class="bi bi-eye"></i> Ver/Editar Recursos
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (empty($partidas)): ?>
                            <div class="alert alert-light text-center">
                                <i class="bi bi-info-circle"></i> No hay partidas en este grupo.
                                <button class="btn btn-sm btn-primary" onclick="abrirModalPartida(<?php echo $grupo['id']; ?>)">
                                    <i class="bi bi-plus"></i> Agregar Partida
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($grupos)): ?>
        <div class="alert alert-warning text-center py-5">
            <i class="bi bi-folder-x fs-1"></i>
            <h4>No hay grupos registrados</h4>
            <p>Para comenzar, crea un nuevo grupo (capítulo) para esta obra.</p>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#grupoModal" onclick="resetGrupoForm()">
                <i class="bi bi-folder-plus"></i> Crear Primer Grupo
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- Modal para ver detalle del APU -->
<div class="modal fade" id="apuDetalleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-list-ul"></i> Detalle APU: <span id="apuPartidaNombre"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="apuPartidaId">
                
                <h6>Recursos actuales</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr><th>Descripción</th><th>Unidad</th><th>Cantidad</th><th>Precio Unitario</th><th>Total</th><th></th></tr>
                        </thead>
                        <tbody id="recursosTableBody">
                            <tr><td colspan="6" class="text-center">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-success" id="totalAPUDisplay">
                    <strong>Total APU: Bs. <span id="totalAPU">0.00</span></strong>
                </div>
                
                <hr>
                <h6><i class="bi bi-plus-circle"></i> Agregar nuevo recurso</h6>
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" id="recurso_desc" class="form-control" placeholder="Descripción">
                    </div>
                    <div class="col-md-2">
                        <input type="text" id="recurso_unidad" class="form-control" placeholder="Unidad">
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.0001" id="recurso_cantidad" class="form-control" placeholder="Cantidad">
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" id="recurso_pu" class="form-control" placeholder="Precio">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success w-100" onclick="agregarRecurso()">
                            <i class="bi bi-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Grupo -->
<div class="modal fade" id="grupoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-folder"></i> Nuevo Grupo/Capítulo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="grupoId">
                <input type="hidden" id="grupoObraId" value="<?php echo $obra_id; ?>">
                <div class="mb-2">
                    <label>Código</label>
                    <input type="text" id="grupo_codigo" class="form-control" placeholder="Ej: 04">
                </div>
                <div class="mb-2">
                    <label>Nombre</label>
                    <input type="text" id="grupo_nombre" class="form-control" placeholder="Ej: Instalaciones">
                </div>
                <div class="mb-2">
                    <label>Orden</label>
                    <input type="number" id="grupo_orden" class="form-control" value="0">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" onclick="saveGrupo()">Guardar Grupo</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Partida -->
<div class="modal fade" id="partidaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-tag"></i> Nueva Partida</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="partidaId">
                <div class="mb-2">
                    <label>Grupo</label>
                    <select id="partida_grupo_id" class="form-select">
                        <?php foreach ($grupos as $grupo): ?>
                            <option value="<?php echo $grupo['id']; ?>"><?php echo $grupo['codigo']; ?> - <?php echo $grupo['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Código</label>
                    <input type="text" id="partida_codigo" class="form-control" placeholder="Ej: 04.01">
                </div>
                <div class="mb-2">
                    <label>Descripción</label>
                    <input type="text" id="partida_descripcion" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Unidad</label>
                    <input type="text" id="partida_unidad" class="form-control" placeholder="m2, m3, und">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" onclick="savePartida()">Guardar Partida</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPartidaId = 0;

function verDetalleAPU(partida_id, nombre) {
    currentPartidaId = partida_id;
    document.getElementById('apuPartidaId').value = partida_id;
    document.getElementById('apuPartidaNombre').innerText = nombre;
    cargarRecursos(partida_id);
    new bootstrap.Modal(document.getElementById('apuDetalleModal')).show();
}

function cargarRecursos(partida_id) {
    fetch(`?controller=Apu&action=ajaxGetRecursos&partida_id=${partida_id}`)
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                let html = '';
                let total = 0;
                const recursos = response.data;
                
                if (recursos.length === 0) {
                    html = '<tr><td colspan="6" class="text-center text-muted">No hay recursos registrados</td></tr>';
                } else {
                    recursos.forEach(r => {
                        const subtotal = parseFloat(r.cantidad) * parseFloat(r.precio_unitario);
                        total += subtotal;
                        html += `
                            <tr>
                                <td><strong>${r.descripcion}</strong></td>
                                <td>${r.unidad}</td>
                                <td class="text-end">${parseFloat(r.cantidad).toFixed(4)}</td>
                                <td class="text-end">Bs. ${parseFloat(r.precio_unitario).toFixed(2)}</td>
                                <td class="text-end text-primary">Bs. ${subtotal.toFixed(2)}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-danger" onclick="eliminarRecurso(${r.id})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }
                document.getElementById('recursosTableBody').innerHTML = html;
                document.getElementById('totalAPU').innerText = total.toFixed(2);
            }
        });
}

function agregarRecurso() {
    const data = {
        partida_id: currentPartidaId,
        descripcion: document.getElementById('recurso_desc').value,
        unidad: document.getElementById('recurso_unidad').value,
        cantidad: document.getElementById('recurso_cantidad').value,
        precio_unitario: document.getElementById('recurso_pu').value
    };
    
    if (!data.descripcion || !data.unidad || !data.cantidad || !data.precio_unitario) {
        alert('Complete todos los campos');
        return;
    }
    
    fetch('?controller=Apu&action=ajaxSaveRecurso', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            document.getElementById('recurso_desc').value = '';
            document.getElementById('recurso_unidad').value = '';
            document.getElementById('recurso_cantidad').value = '';
            document.getElementById('recurso_pu').value = '';
            cargarRecursos(currentPartidaId);
            showToast('✅ Recurso agregado', 'success');
            // Recargar la página para actualizar precios
            setTimeout(() => location.reload(), 500);
        } else {
            alert('Error al guardar');
        }
    });
}

function eliminarRecurso(id) {
    if (confirm('¿Eliminar este recurso?')) {
        fetch('?controller=Apu&action=ajaxDeleteRecurso', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                cargarRecursos(currentPartidaId);
                showToast('🗑️ Recurso eliminado', 'warning');
                setTimeout(() => location.reload(), 500);
            }
        });
    }
}

function saveGrupo() {
    const id = document.getElementById('grupoId').value;
    const data = {
        obra_id: document.getElementById('grupoObraId').value,
        codigo: document.getElementById('grupo_codigo').value,
        nombre: document.getElementById('grupo_nombre').value,
        orden: document.getElementById('grupo_orden').value
    };
    
    if (!data.codigo || !data.nombre) {
        alert('Complete código y nombre');
        return;
    }
    
    if (id) data.id = id;
    
    fetch('?controller=Grupo&action=ajaxSave', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('grupoModal')).hide();
            location.reload();
        }
    });
}

function savePartida() {
    const id = document.getElementById('partidaId').value;
    const data = {
        grupo_id: document.getElementById('partida_grupo_id').value,
        codigo: document.getElementById('partida_codigo').value,
        descripcion: document.getElementById('partida_descripcion').value,
        unidad: document.getElementById('partida_unidad').value
    };
    
    if (!data.grupo_id || !data.codigo || !data.descripcion || !data.unidad) {
        alert('Complete todos los campos');
        return;
    }
    
    if (id) data.id = id;
    
    fetch('?controller=Partida&action=ajaxSave', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('partidaModal')).hide();
            location.reload();
        }
    });
}

function resetGrupoForm() {
    document.getElementById('grupoId').value = '';
    document.getElementById('grupo_codigo').value = '';
    document.getElementById('grupo_nombre').value = '';
    document.getElementById('grupo_orden').value = 0;
}

function resetPartidaForm() {
    document.getElementById('partidaId').value = '';
    document.getElementById('partida_codigo').value = '';
    document.getElementById('partida_descripcion').value = '';
    document.getElementById('partida_unidad').value = '';
}

function abrirModalPartida(grupo_id) {
    document.getElementById('partida_grupo_id').value = grupo_id;
    resetPartidaForm();
    new bootstrap.Modal(document.getElementById('partidaModal')).show();
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'warning'} position-fixed bottom-0 end-0 m-3 animate__animated animate__fadeInUp`;
    toast.style.zIndex = '9999';
    toast.style.background = type === 'success' ? '#d4edda' : '#fff3cd';
    toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>