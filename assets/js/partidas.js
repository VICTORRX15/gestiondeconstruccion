function loadPartidas() {
    fetch('?controller=Partida&action=ajaxList')
        .then(res => res.json())
        .then(data => {
            // Actualizar selects de partidas en cómputos y APU
            const selects = document.querySelectorAll('.partida-select');
            selects.forEach(select => {
                let oldValue = select.value;
                select.innerHTML = '<option value="">Seleccionar partida</option>';
                data.forEach(p => {
                    select.innerHTML += `<option value="${p.id}">${p.codigo} - ${p.descripcion} (${p.unidad})</option>`;
                });
                if(oldValue) select.value = oldValue;
            });
            // Guardar lista global para filtros
            window.partidasData = data;
        });
}
function savePartida() {
    const id = document.getElementById('partidaId').value;
    const data = {
        codigo: document.getElementById('partida_codigo').value,
        descripcion: document.getElementById('partida_descripcion').value,
        unidad: document.getElementById('partida_unidad').value,
        grupo_id: document.getElementById('partida_grupo').value
    };
    if(!data.codigo || !data.descripcion || !data.unidad || !data.grupo_id) {
        alert('Complete todos los campos');
        return;
    }
    if(id) data.id = id;
    fetch('?controller=Partida&action=ajaxSave', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(res => {
        if(res.success) {
            bootstrap.Modal.getInstance(document.getElementById('partidaModal')).hide();
            loadPartidas();
            // Recargar APU y cómputos
            if(typeof loadApus === 'function') loadApus();
            if(typeof loadComputos === 'function') loadComputos();
            // Recargar filtro de grupos en cómputos
            if(typeof loadFiltroGrupos === 'function') loadFiltroGrupos();
        } else alert('Error al guardar');
    });
}
function resetPartidaForm() {
    document.getElementById('partidaId').value = '';
    document.getElementById('partida_codigo').value = '';
    document.getElementById('partida_descripcion').value = '';
    document.getElementById('partida_unidad').value = '';
    // Cargar grupos en el select
    fetch('?controller=Grupo&action=ajaxList')
        .then(res => res.json())
        .then(grupos => {
            let html = '<option value="">Seleccionar grupo</option>';
            grupos.forEach(g => { html += `<option value="${g.id}">${g.codigo} - ${g.nombre}</option>`; });
            document.getElementById('partida_grupo').innerHTML = html;
        });
}
function deletePartida(id) {
    if(confirm('¿Eliminar partida? Se eliminarán también sus cómputos y recursos APU.')) {
        fetch('?controller=Partida&action=ajaxDelete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id='+id
        })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                loadPartidas();
                if(typeof loadApus === 'function') loadApus();
                if(typeof loadComputos === 'function') loadComputos();
            }
        });
    }
}