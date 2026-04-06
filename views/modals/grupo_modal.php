<div class="modal fade" id="grupoModal" tabindex="-1" aria-labelledby="grupoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="grupoModalLabel"><i class="bi bi-tags"></i> Administrar Grupos (Capítulos)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card mb-3">
                    <div class="card-header">Agregar nuevo grupo</div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-3">
                                <input type="text" id="grupo_codigo" class="form-control" placeholder="Código (ej: 04)">
                            </div>
                            <div class="col-5">
                                <input type="text" id="grupo_nombre" class="form-control" placeholder="Nombre del grupo">
                            </div>
                            <div class="col-2">
                                <input type="number" id="grupo_orden" class="form-control" placeholder="Orden">
                            </div>
                            <div class="col-2">
                                <button class="btn btn-success w-100" onclick="saveGrupo()"><i class="bi bi-plus-circle"></i> Agregar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Orden</th>
                                <th width="80">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="gruposBody">
                            <!-- Se llena con JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>