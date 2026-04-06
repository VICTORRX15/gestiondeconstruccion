function loadGrupos() {
    fetch('?controller=Grupo&action=ajaxList')
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.forEach(g => {
                html += `<tr>
                            <td>${g.codigo}</td>
                            <td>${g.nombre}</td>
                            <td>${g.orden}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editGrupo(${g.id})"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="deleteGrupo(${g.id})"><i class="bi bi-trash"></i></button>
                            </td>
                         </tr>`;
            });
            document.getElementById('gruposBody').innerHTML = html;
            // También actualizar selects de grupos en otros modales
            const selectGrupos = document.querySelectorAll('.grupo-select');
            selectGrupos.forEach(select => {
                let oldValue = select.value;
                select.innerHTML = '<option value="">Seleccionar grupo</option>';
                data.forEach(g => {
                    select.innerHTML += `<option value="${g.id}">${g.codigo} - ${g.nombre}</option>`;
                });
                if(oldValue) select.value = oldValue;
            });
        });
}
function saveGrupo() {
    const data = {
        codigo: document.getElementById('grupo_codigo').value,
        nombre: document.getElementById('grupo_nombre').value,
        orden: document.getElementById('grupo_orden').value
    };
    if(!data.codigo || !data.nombre) { alert('Complete código y nombre'); return; }
    fetch('?controller=Grupo&action=ajaxSave', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(res => {
        if(res.success) {
            document.getElementById('grupo_codigo').value = '';
            document.getElementById('grupo_nombre').value = '';
            document.getElementById('grupo_orden').value = '';
            loadGrupos();
            // Recargar partidas en APU y selects
            if(typeof loadPartidas === 'function') loadPartidas();
        }
    });
}
function editGrupo(id) {
    fetch('?controller=Grupo&action=ajaxList')
        .then(res => res.json())
        .then(data => {
            const g = data.find(g => g.id == id);
            if(g) {
                document.getElementById('grupo_codigo').value = g.codigo;
                document.getElementById('grupo_nombre').value = g.nombre;
                document.getElementById('grupo_orden').value = g.orden;
                // Eliminar y volver a crear con el mismo ID (simplificado, mejor usar update)
                deleteGrupo(id).then(() => saveGrupo());
            }
        });
}
function deleteGrupo(id) {
    if(!confirm('¿Eliminar grupo? Las partidas quedarán sin grupo asignado.')) return;
    return fetch('?controller=Grupo&action=ajaxDelete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id='+id
    })
    .then(res => res.json())
    .then(res => { if(res.success) loadGrupos(); });
}