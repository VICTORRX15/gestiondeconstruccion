function loadComputos() {
    const grupoFiltro = document.getElementById('filtroGrupo')?.value || '';
    let url = '?controller=Computo&action=ajaxList';
    if(grupoFiltro) url += '&grupo=' + grupoFiltro;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.forEach(c => {
                html += `<tr>
                            <td>${c.codigo}</td><td>${c.descripcion}</td><td>${c.unidad}</td>
                            <td>${c.n_veces}</td><td>${c.factor}</td>
                            <td>${c.largo || '-'}</td><td>${c.ancho || '-'}</td><td>${c.alto || '-'}</td>
                            <td>${parseFloat(c.cantidad).toFixed(4)}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editComputo(${c.id})"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="deleteComputo(${c.id})"><i class="bi bi-trash"></i></button>
                            </td>
                         </tr>`;
            });
            document.getElementById('computosBody').innerHTML = html;
        });
}
function loadFiltroGrupos() {
    fetch('?controller=Grupo&action=ajaxList')
        .then(res => res.json())
        .then(grupos => {
            let html = '<option value="">Todos los grupos</option>';
            grupos.forEach(g => { html += `<option value="${g.id}">${g.codigo} - ${g.nombre}</option>`; });
            document.getElementById('filtroGrupo').innerHTML = html;
        });
}
// Al cambiar filtro
document.addEventListener('DOMContentLoaded', function() {
    loadFiltroGrupos();
    loadPartidas(); // para llenar select de partidas en modal
    loadComputos();
    document.getElementById('filtroGrupo')?.addEventListener('change', loadComputos);
});
function saveComputo() {
    const id = document.getElementById('computoId').value;
    const data = {
        partida_id: document.getElementById('partida_id').value,
        n_veces: document.getElementById('n_veces').value,
        factor: document.getElementById('factor').value,
        largo: document.getElementById('largo').value,
        ancho: document.getElementById('ancho').value,
        alto: document.getElementById('alto').value
    };
    if(id) data.id = id;
    fetch('?controller=Computo&action=ajaxSave', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(res => {
        if(res.success) {
            bootstrap.Modal.getInstance(document.getElementById('computoModal')).hide();
            loadComputos();
        } else alert('Error al guardar');
    });
}
function editComputo(id) {
    fetch('?controller=Computo&action=ajaxList')
        .then(res => res.json())
        .then(data => {
            const item = data.find(c => c.id == id);
            if(item) {
                document.getElementById('computoId').value = item.id;
                document.getElementById('partida_id').value = item.partida_id;
                document.getElementById('n_veces').value = item.n_veces;
                document.getElementById('factor').value = item.factor;
                document.getElementById('largo').value = item.largo || '';
                document.getElementById('ancho').value = item.ancho || '';
                document.getElementById('alto').value = item.alto || '';
                new bootstrap.Modal(document.getElementById('computoModal')).show();
            }
        });
}
function deleteComputo(id) {
    if(confirm('¿Eliminar este cómputo?')) {
        fetch('?controller=Computo&action=ajaxDelete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id='+id
        })
        .then(res => res.json())
        .then(res => { if(res.success) loadComputos(); });
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
loadComputos();