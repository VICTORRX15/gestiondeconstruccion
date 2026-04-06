<div class="modal fade" id="recursoModal" tabindex="-1" aria-labelledby="recursoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="recursoModalLabel"><i class="bi bi-list-ul"></i> Recursos del APU</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="currentPartidaId">
                <h6 id="partidaNombre" class="mb-3"></h6>
                
                <!-- Tabla de recursos existentes -->
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Descripción</th>
                                <th>Unidad</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario (Bs)</th>
                                <th>Total (Bs)</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="recursosBody">
                            <tr><td colspan="6" class="text-center">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>

                <hr>
                <h6><i class="bi bi-plus-circle"></i> Agregar nuevo recurso</h6>
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Descripción</label>
                        <input type="text" id="recurso_desc" class="form-control" placeholder="Ej: Cemento">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Unidad</label>
                        <input type="text" id="recurso_unidad" class="form-control" placeholder="bolsa, m3, hh">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Cantidad</label>
                        <input type="number" step="0.0001" id="recurso_cantidad" class="form-control" placeholder="0.00">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Precio Unitario</label>
                        <input type="number" step="0.01" id="recurso_pu" class="form-control" placeholder="0.00">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success w-100" onclick="saveRecurso()"><i class="bi bi-save"></i> Guardar</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>