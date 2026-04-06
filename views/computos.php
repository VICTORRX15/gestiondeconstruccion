<?php
$obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
$obraModel = new Obra();
$obra = $obraModel->getById($obra_id);
$grupoModel = new Grupo();
$grupos = $grupoModel->getAllByObra($obra_id);
$partidaModel = new Partida();
$partidas = $partidaModel->getAllByObra($obra_id);
?>

<div class="container-fluid">
    <div class="card bg-gradient-primary text-white mb-4 shadow">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-rulers"></i> Cómputos Métricos - <?php echo htmlspecialchars($obra['nombre']); ?></h2>
                    <p class="mb-0">Registro de cantidades de obra por partida</p>
                </div>
                <!-- Reemplazar el botón -->
<a href="?controller=Obra&action=ver&id=<?php echo $obra_id; ?>" class="btn btn-light">
    <i class="bi bi-arrow-left"></i> Atrás
</a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <label><i class="bi bi-funnel"></i> Filtrar por grupo</label>
            <select id="filtroGrupo" class="form-select">
                <option value="">Todos los grupos</option>
                <?php foreach ($grupos as $grupo): ?>
                    <option value="<?php echo $grupo['id']; ?>"><?php echo $grupo['codigo']; ?> - <?php echo $grupo['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label><i class="bi bi-search"></i> Buscar partida</label>
            <input type="text" id="buscarPartida" class="form-control" placeholder="Escribe para buscar...">
        </div>
        <div class="col-md-4 align-self-end">
            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#computoModal" onclick="resetComputoForm()">
                <i class="bi bi-plus-circle"></i> Nuevo Cómputo
            </button>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-dark">
                        <tr><th>Código</th><th>Descripción</th><th>Unidad</th><th>N° Veces</th><th>Factor</th><th>Largo</th><th>Ancho</th><th>Alto</th><th>Cantidad</th><th>Acciones</th></tr>
                    </thead>
                    <tbody id="computosBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cómputo -->
<div class="modal fade" id="computoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-calculator"></i> Registro de Cómputo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="computoId">
                <div class="mb-3">
                    <label>Partida</label>
                    <select id="partida_id" class="form-select" required>
                        <option value="">Seleccionar partida</option>
                        <?php foreach ($partidas as $partida): ?>
                            <option value="<?php echo $partida['id']; ?>" data-grupo="<?php echo $partida['grupo_id']; ?>">
                                <?php echo $partida['codigo']; ?> - <?php echo $partida['descripcion']; ?> (<?php echo $partida['unidad']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col"><label>N° Veces</label><input type="number" step="0.01" id="n_veces" class="form-control" value="1"></div>
                    <div class="col"><label>Factor</label><input type="number" step="0.01" id="factor" class="form-control" value="1"></div>
                </div>
                <div class="row mt-2">
                    <div class="col"><label>Largo (m)</label><input type="number" step="0.01" id="largo" class="form-control" placeholder="Opcional"></div>
                    <div class="col"><label>Ancho (m)</label><input type="number" step="0.01" id="ancho" class="form-control" placeholder="Opcional"></div>
                    <div class="col"><label>Alto (m)</label><input type="number" step="0.01" id="alto" class="form-control" placeholder="Opcional"></div>
                </div>
                <small class="text-muted">Cantidad = N° Veces × Factor × Largo × Ancho × Alto</small>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" onclick="saveComputo()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
let allComputos = [];

function loadComputos() {
    const obra_id = <?php echo $obra_id; ?>;
    fetch(`?controller=Computo&action=ajaxList&obra_id=${obra_id}`)
        .then(res => res.json())
        .then(data => {
            allComputos = data;
            aplicarFiltros();
        });
}

function aplicarFiltros() {
    const grupoFiltro = document.getElementById('filtroGrupo')?.value || '';
    const busqueda = document.getElementById('buscarPartida')?.value.toLowerCase() || '';
    
    let filtered = [...allComputos];
    
    if (grupoFiltro) {
        filtered = filtered.filter(c => c.grupo_id == grupoFiltro);
    }
    if (busqueda) {
        filtered = filtered.filter(c => 
            c.codigo.toLowerCase().includes(busqueda) || 
            c.descripcion.toLowerCase().includes(busqueda)
        );
    }
    
    let html = '';
    if (filtered.length === 0) {
        html = '<tr><td colspan="10" class="text-center text-muted">No hay cómputos registrados</td</tr>';
    } else {
        filtered.forEach(c => {
            html += `<tr>
                        <td><strong>${c.codigo}</strong></td>
                        <td>${c.descripcion}</td>
                        <td>${c.unidad}</td>
                        <td class="text-end">${c.n_veces}</td>
                        <td class="text-end">${c.factor}</td>
                        <td class="text-end">${c.largo || '-'}</td>
                        <td class="text-end">${c.ancho || '-'}</td>
                        <td class="text-end">${c.alto || '-'}</td>
                        <td class="text-end fw-bold text-primary">${parseFloat(c.cantidad).toFixed(4)}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning" onclick="editComputo(${c.id})"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="deleteComputo(${c.id})"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>`;
        });
    }
    document.getElementById('computosBody').innerHTML = html;
}

function saveComputo() {
    const id = document.getElementById('computoId').value;
    const data = {
        partida_id: document.getElementById('partida_id').value,
        n_veces: document.getElementById('n_veces').value,
        factor: document.getElementById('factor').value,
        largo: document.getElementById('largo').value || null,
        ancho: document.getElementById('ancho').value || null,
        alto: document.getElementById('alto').value || null
    };
    
    if (!data.partida_id) {
        alert('Seleccione una partida');
        return;
    }
    
    if (id) data.id = id;
    
    fetch('?controller=Computo&action=ajaxSave', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('computoModal')).hide();
            loadComputos();
            resetComputoForm();
        } else {
            alert('Error al guardar');
        }
    });
}

function editComputo(id) {
    const computo = allComputos.find(c => c.id == id);
    if (computo) {
        document.getElementById('computoId').value = computo.id;
        document.getElementById('partida_id').value = computo.partida_id;
        document.getElementById('n_veces').value = computo.n_veces;
        document.getElementById('factor').value = computo.factor;
        document.getElementById('largo').value = computo.largo || '';
        document.getElementById('ancho').value = computo.ancho || '';
        document.getElementById('alto').value = computo.alto || '';
        new bootstrap.Modal(document.getElementById('computoModal')).show();
    }
}

function deleteComputo(id) {
    if (confirm('¿Eliminar este cómputo?')) {
        fetch('?controller=Computo&action=ajaxDelete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) loadComputos();
        });
    }
}

function resetComputoForm() {
    document.getElementById('computoId').value = '';
    document.getElementById('n_veces').value = 1;
    document.getElementById('factor').value = 1;
    document.getElementById('largo').value = '';
    document.getElementById('ancho').value = '';
    document.getElementById('alto').value = '';
}

document.getElementById('filtroGrupo')?.addEventListener('change', aplicarFiltros);
document.getElementById('buscarPartida')?.addEventListener('keyup', aplicarFiltros);

loadComputos();
</script>