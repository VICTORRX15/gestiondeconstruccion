// assets/js/apus.js (extracto)
function cargarRecursos(partida_id) {
    fetch(`?controller=Apu&action=ajaxGetRecursos&partida_id=${partida_id}`)
        .then(res => res.json())
        .then(data => {
            let html = '';
            let total = 0;
            data.forEach(r => {
                const subtotal = r.cantidad * r.precio_unitario;
                total += subtotal;
                html += `<tr>
                            <td>${r.descripcion}</td>
                            <td>${r.unidad}</td>
                            <td>${r.cantidad}</td>
                            <td>${r.precio_unitario}</td>
                            <td>${subtotal.toFixed(2)}</td>
                            <td><button class="btn btn-sm btn-danger" onclick="eliminarRecurso(${r.id})"><i class="bi bi-trash"></i></button></td>
                         </tr>`;
            });
            html += `<tr class="table-info"><td colspan="4"><strong>TOTAL APU</strong></td><td colspan="2"><strong>${total.toFixed(2)} Bs.</strong></td></tr>`;
            document.getElementById('recursosBody').innerHTML = html;
        });
}

function saveRecurso() {
    const partida_id = document.getElementById('currentPartidaId').value;
    const data = {
        partida_id: partida_id,
        descripcion: document.getElementById('recurso_desc').value,
        unidad: document.getElementById('recurso_unidad').value,
        cantidad: document.getElementById('recurso_cantidad').value,
        precio_unitario: document.getElementById('recurso_pu').value
    };
    if (!data.descripcion || !data.unidad || data.cantidad <= 0 || data.precio_unitario <= 0) {
        alert('Complete todos los campos correctamente');
        return;
    }
    fetch('?controller=Apu&action=ajaxSaveRecurso', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            document.getElementById('recurso_desc').value = '';
            document.getElementById('recurso_unidad').value = '';
            document.getElementById('recurso_cantidad').value = '';
            document.getElementById('recurso_pu').value = '';
            cargarRecursos(partida_id);
            loadApus(); // actualiza lista de APUs porque cambió el precio
        } else alert('Error al guardar recurso');
    });
}

function eliminarRecurso(id) {
    if (confirm('¿Eliminar este recurso?')) {
        fetch('?controller=Apu&action=ajaxDeleteRecurso', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                const partida_id = document.getElementById('currentPartidaId').value;
                cargarRecursos(partida_id);
                loadApus();
            }
        });
    }
}