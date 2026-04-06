<nav class="col-md-2 d-none d-md-block bg-light sidebar" style="min-height: 100vh;">
    <div class="position-sticky pt-3">
        <h6 class="sidebar-heading px-3 mt-2 mb-1 text-muted">
            <i class="bi bi-building"></i> MIS PROYECTOS
        </h6>
        <div id="sidebarObras">
            <div class="text-center py-2">
                <div class="spinner-border spinner-border-sm text-primary"></div>
                <small>Cargando...</small>
            </div>
        </div>
        <hr>
        <div class="px-3">
            <a href="?controller=Obra" class="btn btn-sm btn-outline-primary w-100 mb-2">
                <i class="bi bi-grid-3x3-gap-fill"></i> Mis Obras y Proyectos
            </a>
            <button class="btn btn-sm btn-success w-100" data-bs-toggle="modal" data-bs-target="#obraModalSidebar" onclick="resetObraSidebarForm()">
                <i class="bi bi-plus-circle"></i> Nueva Obra
            </button>
        </div>
        
        <!-- Tarjeta del Desarrollador - Solo visible en la página de obras -->
        <div id="developerCardContainer" style="display: none;">
            <div class="developer-sidebar-card">
                <img src="<?php echo BASE_URL; ?>assets/images/photo.png" 
                     alt="Victor Rodrigo Condori Cruz" 
                     class="developer-sidebar-avatar"
                     onerror="this.src='https://ui-avatars.com/api/?background=4F46E5&color=fff&rounded=true&size=70&name=Victor+Condori'">
                <div class="developer-sidebar-name">Victor Rodrigo Condori Cruz</div>
                <div class="developer-sidebar-title">
                    <i class="bi bi-tools"></i> Técnico Medio I Construcción Civil
                </div>
                <div class="developer-sidebar-bio">
                    Plataforma web para gestión de obras (cómputos métricos, APU, presupuestos y planificación), desarrollada en el marco del proyecto de grado.
                </div>
                <div class="developer-sidebar-social">
                    <a href="#" target="_blank"><i class="bi bi-linkedin"></i></a>
                    <a href="#" target="_blank"><i class="bi bi-github"></i></a>
                    <a href="#" target="_blank"><i class="bi bi-envelope"></i></a>
                    <a href="https://wa.me/59179117749" target="_blank"><i class="bi bi-whatsapp"></i></a>
                </div>
                <div class="mt-2">
                    <small class="opacity-75">
                        <i class="bi bi-award"></i> CEA Fausto Reynaga
                    </small>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Modal para nueva obra desde sidebar -->
<div class="modal fade" id="obraModalSidebar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-building"></i> Nueva Obra</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="obraIdSidebar">
                <div class="mb-2">
                    <label>Código</label>
                    <input type="text" id="obra_codigo_sidebar" class="form-control" placeholder="Ej: OBR-001">
                </div>
                <div class="mb-2">
                    <label>Nombre</label>
                    <input type="text" id="obra_nombre_sidebar" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Descripción</label>
                    <textarea id="obra_descripcion_sidebar" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-2">
                    <label>Ubicación</label>
                    <input type="text" id="obra_ubicacion_sidebar" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Estado</label>
                    <select id="obra_estado_sidebar" class="form-select">
                        <option value="planificacion">Planificación</option>
                        <option value="en_ejecucion">En Ejecución</option>
                        <option value="pausado">Pausado</option>
                        <option value="terminado">Terminado</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" onclick="saveObraSidebar()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
function loadSidebarObras() {
    fetch('?controller=Obra&action=ajaxList')
        .then(res => res.json())
        .then(obras => {
            let html = '<ul class="nav flex-column">';
            obras.forEach(obra => {
                const active = window.location.href.includes(`obra_id=${obra.id}`) ? 'active fw-bold' : '';
                html += `
                    <li class="nav-item">
                        <a class="nav-link ${active}" href="?controller=Obra&action=ver&id=${obra.id}">
                            <i class="bi bi-building"></i> ${obra.codigo}
                            <small class="text-muted d-block">${obra.nombre.substring(0, 25)}</small>
                        </a>
                    </li>
                `;
            });
            html += '</ul>';
            document.getElementById('sidebarObras').innerHTML = html;
        });
}

function saveObraSidebar() {
    const data = {
        codigo: document.getElementById('obra_codigo_sidebar').value,
        nombre: document.getElementById('obra_nombre_sidebar').value,
        descripcion: document.getElementById('obra_descripcion_sidebar').value,
        ubicacion: document.getElementById('obra_ubicacion_sidebar').value,
        estado: document.getElementById('obra_estado_sidebar').value
    };
    
    if (!data.codigo || !data.nombre) {
        alert('Complete código y nombre');
        return;
    }
    
    fetch('?controller=Obra&action=ajaxSave', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('obraModalSidebar')).hide();
            location.reload();
        }
    });
}

function resetObraSidebarForm() {
    document.getElementById('obra_codigo_sidebar').value = '';
    document.getElementById('obra_nombre_sidebar').value = '';
    document.getElementById('obra_descripcion_sidebar').value = '';
    document.getElementById('obra_ubicacion_sidebar').value = '';
    document.getElementById('obra_estado_sidebar').value = 'planificacion';
}

// Mostrar/ocultar tarjeta del desarrollador según la página
function checkDeveloperCardVisibility() {
    const urlParams = new URLSearchParams(window.location.search);
    const controller = urlParams.get('controller');
    const action = urlParams.get('action');
    
    // Mostrar solo en la página principal de obras (controller=Obra y sin action o action=index)
    const developerCard = document.getElementById('developerCardContainer');
    if (developerCard) {
        if ((!controller || controller === 'Obra') && (!action || action === 'index')) {
            developerCard.style.display = 'block';
        } else {
            developerCard.style.display = 'none';
        }
    }
}

loadSidebarObras();
checkDeveloperCardVisibility();
</script>