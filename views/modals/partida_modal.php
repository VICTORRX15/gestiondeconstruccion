<div class="modal fade" id="partidaModal" tabindex="-1" aria-labelledby="partidaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="partidaModalLabel"><i class="bi bi-folder-plus"></i> Nueva Partida</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="partidaId">
                <div class="mb-3">
                    <label class="form-label">Código *</label>
                    <input type="text" id="partida_codigo" class="form-control" placeholder="Ej: 04.01" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción *</label>
                    <input type="text" id="partida_descripcion" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Unidad *</label>
                    <input type="text" id="partida_unidad" class="form-control" placeholder="m2, m3, und, glb" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Grupo (Capítulo) *</label>
                    <select id="partida_grupo" class="form-select grupo-select" required>
                        <option value="">Seleccionar grupo</option>
                    </select>
                    <small class="text-muted">Si no existe el grupo, adminístrelo desde el botón "Administrar Grupos".</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="savePartida()">Guardar</button>
            </div>
        </div>
    </div>
</div>