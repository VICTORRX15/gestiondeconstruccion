<div class="obras-page">
    <div class="container">
        <h2 class="mb-4"><i class="bi bi-building text-primary"></i> Mis Obras y Proyectos</h2>
        
        <div class="row" id="obrasList">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary"></div>
                <p>Cargando obras...</p>
            </div>
        </div>
    </div>
</div>

<script>
function loadObras() {
    fetch('?controller=Obra&action=ajaxList')
        .then(res => res.json())
        .then(obras => {
            let html = '';
            if (obras.length === 0) {
                html = `
                    <div class="col-12">
                        <div class="alert alert-warning text-center py-5">
                            <i class="bi bi-folder-x fs-1"></i>
                            <h4>No hay obras registradas</h4>
                            <p>Comienza creando tu primer proyecto</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#obraModalSidebar" onclick="resetObraSidebarForm()">
                                <i class="bi bi-plus-circle"></i> Crear Primera Obra
                            </button>
                        </div>
                    </div>
                `;
            } else {
                obras.forEach(obra => {
                    const estadoClass = obra.estado === 'en_ejecucion' ? 'success' : 
                                       (obra.estado === 'planificacion' ? 'info' : 
                                       (obra.estado === 'terminado' ? 'secondary' : 'warning'));
                    html += `
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-lg animate__animated animate__fadeInUp obra-card">
                                <div class="card-header bg-${estadoClass} text-white">
                                    <i class="bi bi-building"></i> ${obra.codigo}
                                    <span class="badge bg-light text-dark float-end">${obra.estado}</span>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">${obra.nombre}</h5>
                                    <p class="card-text text-muted small">${obra.descripcion || 'Sin descripción'}</p>
                                    <p><i class="bi bi-geo-alt"></i> ${obra.ubicacion || 'No especificada'}</p>
                                    <div class="d-grid gap-2">
                                        <a href="?controller=Obra&action=ver&id=${obra.id}" class="btn btn-primary">
                                            <i class="bi bi-eye"></i> Ver Detalle de la Obra
                                        </a>
                                        <button class="btn btn-outline-secondary btn-sm" onclick="mostrarSubgrupos(${obra.id}, '${obra.nombre.replace(/'/g, "\\'")}')">
                                            <i class="bi bi-folder2-open"></i> Ver Capítulos y Partidas
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            document.getElementById('obrasList').innerHTML = html;
        })
        .catch(error => {
            console.error('Error cargando obras:', error);
            document.getElementById('obrasList').innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger text-center">
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                        <p>Error al cargar las obras. Verifica la conexión.</p>
                    </div>
                </div>
            `;
        });
}

function mostrarSubgrupos(obra_id, obra_nombre) {
    console.log('Mostrando subgrupos para obra:', obra_id, obra_nombre);
    
    fetch(`?controller=Grupo&action=ajaxList&obra_id=${obra_id}`)
        .then(res => res.json())
        .then(grupos => {
            console.log('Grupos cargados:', grupos);
            
            let modalHtml = `
                <div class="modal fade" id="subgruposModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-folder2-open"></i> ${obra_nombre} - Capítulos y Partidas
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                <div class="accordion" id="accordionGrupos">
            `;
            
            if (grupos.length === 0) {
                modalHtml += `
                    <div class="alert alert-warning text-center">
                        <i class="bi bi-folder-x fs-1"></i>
                        <p>No hay capítulos registrados en esta obra.</p>
                        <button class="btn btn-sm btn-success" onclick="window.location.href='?controller=Apu&obra_id=${obra_id}'">
                            <i class="bi bi-plus-circle"></i> Crear Primer Capítulo
                        </button>
                    </div>
                `;
            } else {
                for (let index = 0; index < grupos.length; index++) {
                    const grupo = grupos[index];
                    modalHtml += `
                        <div class="accordion-item mb-2">
                            <h2 class="accordion-header" id="heading${grupo.id}">
                                <button class="accordion-button ${index !== 0 ? 'collapsed' : ''}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${grupo.id}" aria-expanded="${index === 0 ? 'true' : 'false'}">
                                    <strong><i class="bi bi-folder"></i> ${grupo.codigo} - ${grupo.nombre}</strong>
                                    <span class="badge bg-secondary rounded-pill ms-2" id="badge-${grupo.id}">Cargando...</span>
                                </button>
                            </h2>
                            <div id="collapse${grupo.id}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" data-bs-parent="#accordionGrupos">
                                <div class="accordion-body" id="contenido-${grupo.id}">
                                    <div class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm text-primary"></div>
                                        <span class="ms-2">Cargando partidas...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
            
            modalHtml += `
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="btn-group me-auto">
                                    <a href="?controller=Computo&obra_id=${obra_id}" class="btn btn-primary">
                                        <i class="bi bi-rulers"></i> Todos los Cómputos
                                    </a>
                                    <a href="?controller=Apu&obra_id=${obra_id}" class="btn btn-success">
                                        <i class="bi bi-calculator"></i> Todos los APU
                                    </a>
                                    <a href="?controller=Presupuesto&obra_id=${obra_id}" class="btn btn-warning">
                                        <i class="bi bi-currency-dollar"></i> Presupuesto General
                                    </a>
                                    <a href="?controller=Cronograma&obra_id=${obra_id}" class="btn btn-info">
                                        <i class="bi bi-calendar-week"></i> Cronograma General
                                    </a>
                                </div>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            const existingModal = document.getElementById('subgruposModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            const modalElement = document.getElementById('subgruposModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            
            grupos.forEach(grupo => {
                cargarPartidasParaGrupo(grupo.id, grupo.codigo, grupo.nombre, obra_id);
            });
        })
        .catch(error => {
            console.error('Error cargando grupos:', error);
            alert('Error al cargar los capítulos. Verifica la conexión.');
        });
}

function cargarPartidasParaGrupo(grupo_id, grupo_codigo, grupo_nombre, obra_id) {
    fetch(`?controller=Partida&action=ajaxListByGrupo&grupo_id=${grupo_id}`)
        .then(res => res.json())
        .then(partidas => {
            console.log(`Partidas del grupo ${grupo_codigo}:`, partidas);
            
            const badge = document.getElementById(`badge-${grupo_id}`);
            if (badge) {
                badge.textContent = `${partidas.length} partidas`;
            }
            
            let contenidoHtml = '';
            if (partidas.length === 0) {
                contenidoHtml = `
                    <div class="alert alert-light text-center mb-0">
                        <i class="bi bi-info-circle"></i> No hay partidas en este capítulo.
                        <button class="btn btn-sm btn-primary ms-2" onclick="window.location.href='?controller=Apu&obra_id=${obra_id}&grupo_id=${grupo_id}'">
                            <i class="bi bi-plus-circle"></i> Agregar Partida
                        </button>
                    </div>
                `;
            } else {
                contenidoHtml = `
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">Código</th>
                                    <th>Descripción</th>
                                    <th width="80">Unidad</th>
                                    <th width="200">Acciones Directas</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                partidas.forEach(partida => {
                    contenidoHtml += `
                        <tr>
                            <td><strong>${partida.codigo}</strong></td>
                            <td>${partida.descripcion}</td>
                            <td>${partida.unidad}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="?controller=Computo&obra_id=${obra_id}&partida_id=${partida.id}" class="btn btn-outline-primary" title="Ver cómputos">
                                        <i class="bi bi-rulers"></i> Cómputos
                                    </a>
                                    <a href="?controller=Apu&obra_id=${obra_id}&partida_id=${partida.id}" class="btn btn-outline-success" title="Ver APU">
                                        <i class="bi bi-calculator"></i> APU
                                    </a>
                                    <a href="?controller=Presupuesto&obra_id=${obra_id}&partida_id=${partida.id}" class="btn btn-outline-warning" title="Ver presupuesto">
                                        <i class="bi bi-currency-dollar"></i> Presupuesto
                                    </a>
                                    <a href="?controller=Cronograma&obra_id=${obra_id}&partida_id=${partida.id}" class="btn btn-outline-info" title="Ver cronograma">
                                        <i class="bi bi-calendar-week"></i> Cronograma
                                    </a>
                                </div>
                             </div>
                            </td>
                        </tr>
                    `;
                });
                contenidoHtml += `
                            </tbody>
                        </table>
                    </div>
                `;
            }
            
            const contenidoDiv = document.getElementById(`contenido-${grupo_id}`);
            if (contenidoDiv) {
                contenidoDiv.innerHTML = contenidoHtml;
            }
        })
        .catch(error => {
            console.error(`Error cargando partidas del grupo ${grupo_id}:`, error);
            const contenidoDiv = document.getElementById(`contenido-${grupo_id}`);
            if (contenidoDiv) {
                contenidoDiv.innerHTML = `
                    <div class="alert alert-danger text-center mb-0">
                        <i class="bi bi-exclamation-triangle"></i> Error al cargar las partidas
                    </div>
                `;
            }
        });
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    loadObras();
});
</script>