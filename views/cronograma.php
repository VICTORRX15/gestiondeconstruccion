<?php
$obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
$obraModel = new Obra();
$obra = $obraModel->getById($obra_id);
$partidaModel = new Partida();
$partidas = $partidaModel->getAllByObra($obra_id);
?>

<div class="container-fluid">
    <!-- Header de la obra -->
    <div class="card bg-gradient-info text-white mb-4 shadow">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-calendar-week"></i> Cronograma - <?php echo htmlspecialchars($obra['nombre']); ?></h2>
                    <p class="mb-0">Diagrama Gantt interactivo - Arrastra las barras para ajustar fechas</p>
                </div>
                <!-- Reemplazar el botón -->
<a href="?controller=Obra&action=ver&id=<?php echo $obra_id; ?>" class="btn btn-light">
    <i class="bi bi-arrow-left"></i> Atrás
</a>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4" id="statsCronograma">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div><h6>Total Actividades</h6><h3 id="totalActividades">0</h3></div>
                        <i class="bi bi-list-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div><h6>Completadas</h6><h3 id="completadas">0</h3></div>
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div><h6>En Progreso</h6><h3 id="enProgreso">0</h3></div>
                        <i class="bi bi-hourglass-split fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div><h6>Duración Total</h6><h3 id="duracionTotal">0 días</h3></div>
                        <i class="bi bi-calendar-range fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor del Gantt -->
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-graph-up"></i> Diagrama Gantt Interactivo
            <button class="btn btn-sm btn-success float-end" data-bs-toggle="modal" data-bs-target="#actividadModal" onclick="resetActividadForm()">
                <i class="bi bi-plus-circle"></i> Nueva Actividad
            </button>
        </div>
        <div class="card-body">
            <div id="ganttChart" style="height: 500px; background: #f8f9fa;"></div>
        </div>
    </div>

    <!-- Lista de actividades -->
    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            <i class="bi bi-list-ul"></i> Lista de Actividades
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Actividad</th><th>Partida</th><th>Inicio</th><th>Fin</th><th>Progreso</th><th>Acciones</th></tr>
                    </thead>
                    <tbody id="listaActividades"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Actividad -->
<div class="modal fade" id="actividadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-calendar-plus"></i> Actividad</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="actividadId">
                <input type="hidden" id="actividadObraId" value="<?php echo $obra_id; ?>">
                <div class="mb-3">
                    <label>Nombre de la actividad</label>
                    <input type="text" id="actividad_nombre" class="form-control" placeholder="Ej: Excavación">
                </div>
                <div class="row">
                    <div class="col">
                        <label>Fecha Inicio</label>
                        <input type="date" id="actividad_inicio" class="form-control">
                    </div>
                    <div class="col">
                        <label>Fecha Fin</label>
                        <input type="date" id="actividad_fin" class="form-control">
                    </div>
                </div>
                <div class="mb-3 mt-2">
                    <label>Progreso (%)</label>
                    <div class="input-group">
                        <input type="range" id="actividad_progreso" class="form-range" min="0" max="100" value="0" oninput="document.getElementById('progresoLabel').innerText = this.value + '%'">
                        <span class="input-group-text" id="progresoLabel">0%</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Partida asociada</label>
                    <select id="actividad_partida" class="form-select">
                        <option value="">Sin asociar</option>
                        <?php foreach ($partidas as $partida): ?>
                            <option value="<?php echo $partida['id']; ?>"><?php echo $partida['codigo']; ?> - <?php echo $partida['descripcion']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" onclick="saveActividad()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1"></script>
<script>
let timeline = null;
let actividades = [];

function loadCronograma() {
    const obra_id = <?php echo $obra_id; ?>;
    fetch(`?controller=Cronograma&action=ajaxList&obra_id=${obra_id}`)
        .then(res => res.json())
        .then(data => {
            actividades = data;
            actualizarEstadisticas();
            actualizarListaActividades();
            
            const items = new vis.DataSet(data.map(a => ({
                id: a.id,
                content: `${a.nombre} ${a.progreso > 0 ? `✅ ${a.progreso}%` : '📋'}`,
                start: a.fecha_inicio,
                end: a.fecha_fin,
                title: a.partida_nombre || 'Sin partida',
                className: a.progreso === 100 ? 'completed-task' : (a.progreso > 0 ? 'in-progress-task' : 'pending-task')
            })));
            
            const container = document.getElementById('ganttChart');
            const options = {
                height: '500px',
                editable: {
                    updateTime: true,
                    updateGroup: false,
                    add: false,
                    remove: false
                },
                onMove: function(item, callback) {
                    fetch('?controller=Cronograma&action=ajaxSave', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ id: item.id, fecha_inicio: item.start, fecha_fin: item.end })
                    }).then(() => loadCronograma());
                    callback(item);
                },
                zoomKey: 'ctrlKey',
                zoomable: true
            };
            
            if (timeline) timeline.destroy();
            timeline = new vis.Timeline(container, items, options);
        });
}

function actualizarEstadisticas() {
    const total = actividades.length;
    const completadas = actividades.filter(a => a.progreso === 100).length;
    const enProgreso = actividades.filter(a => a.progreso > 0 && a.progreso < 100).length;
    let duracionTotal = 0;
    actividades.forEach(a => {
        const inicio = new Date(a.fecha_inicio);
        const fin = new Date(a.fecha_fin);
        const diffDays = Math.ceil(Math.abs(fin - inicio) / (1000 * 60 * 60 * 24));
        duracionTotal += diffDays;
    });
    
    document.getElementById('totalActividades').innerText = total;
    document.getElementById('completadas').innerText = completadas;
    document.getElementById('enProgreso').innerText = enProgreso;
    document.getElementById('duracionTotal').innerText = duracionTotal + ' días';
    
    // Confeti si se completó una nueva actividad
    if (completadas > 0 && completadas !== window.prevCompletadas) {
        canvasConfetti({ particleCount: 150, spread: 70, origin: { y: 0.6 } });
        window.prevCompletadas = completadas;
    }
}

function actualizarListaActividades() {
    let html = '';
    actividades.forEach(a => {
        const progresoColor = a.progreso === 100 ? 'success' : (a.progreso > 0 ? 'warning' : 'secondary');
        html += `<tr>
                    <td><strong>${a.nombre}</strong></td>
                    <td>${a.partida_nombre || '<span class="text-muted">-</span>'}</td>
                    <td>${a.fecha_inicio}</td>
                    <td>${a.fecha_fin}</td>
                    <td><div class="progress" style="height:20px"><div class="progress-bar bg-${progresoColor}" style="width:${a.progreso}%">${a.progreso}%</div></div></td>
                    <td><button class="btn btn-sm btn-warning" onclick="editActividad(${a.id})"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteActividad(${a.id})"><i class="bi bi-trash"></i></button></td>
                </tr>`;
    });
    document.getElementById('listaActividades').innerHTML = html;
}

function saveActividad() {
    const id = document.getElementById('actividadId').value;
    const data = {
        obra_id: document.getElementById('actividadObraId').value,
        nombre: document.getElementById('actividad_nombre').value,
        fecha_inicio: document.getElementById('actividad_inicio').value,
        fecha_fin: document.getElementById('actividad_fin').value,
        progreso: document.getElementById('actividad_progreso').value,
        partida_id: document.getElementById('actividad_partida').value || null
    };
    
    if (!data.nombre || !data.fecha_inicio || !data.fecha_fin) {
        alert('Complete todos los campos obligatorios');
        return;
    }
    
    if (id) data.id = id;
    
    fetch('?controller=Cronograma&action=ajaxSave', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('actividadModal')).hide();
            loadCronograma();
            resetActividadForm();
        }
    });
}

function editActividad(id) {
    const act = actividades.find(a => a.id == id);
    if (act) {
        document.getElementById('actividadId').value = act.id;
        document.getElementById('actividad_nombre').value = act.nombre;
        document.getElementById('actividad_inicio').value = act.fecha_inicio;
        document.getElementById('actividad_fin').value = act.fecha_fin;
        document.getElementById('actividad_progreso').value = act.progreso;
        document.getElementById('progresoLabel').innerText = act.progreso + '%';
        document.getElementById('actividad_partida').value = act.partida_id || '';
        new bootstrap.Modal(document.getElementById('actividadModal')).show();
    }
}

function deleteActividad(id) {
    if (confirm('¿Eliminar esta actividad?')) {
        fetch('?controller=Cronograma&action=ajaxDelete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        }).then(() => loadCronograma());
    }
}

function resetActividadForm() {
    document.getElementById('actividadId').value = '';
    document.getElementById('actividad_nombre').value = '';
    document.getElementById('actividad_inicio').value = '';
    document.getElementById('actividad_fin').value = '';
    document.getElementById('actividad_progreso').value = 0;
    document.getElementById('progresoLabel').innerText = '0%';
}

loadCronograma();
</script>

<style>
.completed-task { background-color: #28a745 !important; }
.in-progress-task { background-color: #ffc107 !important; }
.pending-task { background-color: #6c757d !important; }
.vis-item { border-radius: 8px !important; cursor: pointer !important; }
.bg-gradient-info { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
</style>