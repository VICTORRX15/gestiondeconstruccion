let timeline = null;
function loadCronograma() {
    fetch('?controller=Cronograma&action=ajaxList')
        .then(res => res.json())
        .then(data => {
            const items = new vis.DataSet(data.map(a => ({
                id: a.id,
                content: a.nombre + (a.progreso ? ` (${a.progreso}%)` : ''),
                start: a.fecha_inicio,
                end: a.fecha_fin,
                progress: a.progreso,
                title: a.partida_nombre || ''
            })));
            const container = document.getElementById('ganttChart');
            const options = {
                editable: true,
                onMove: function(item, callback) {
                    // Al mover, actualizar fechas en BD
                    fetch('?controller=Cronograma&action=ajaxSave', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ id: item.id, fecha_inicio: item.start, fecha_fin: item.end })
                    }).then(() => loadCronograma());
                    callback(item);
                }
            };
            if(timeline) timeline.destroy();
            timeline = new vis.Timeline(container, items, options);
        });
}
function saveActividad() {
    const id = document.getElementById('actividadId').value;
    const data = {
        nombre: document.getElementById('actividad_nombre').value,
        fecha_inicio: document.getElementById('actividad_inicio').value,
        fecha_fin: document.getElementById('actividad_fin').value,
        progreso: document.getElementById('actividad_progreso').value,
        partida_id: document.getElementById('actividad_partida').value || null
    };
    if(!data.nombre || !data.fecha_inicio || !data.fecha_fin) {
        alert('Complete nombre y fechas');
        return;
    }
    if(id) data.id = id;
    fetch('?controller=Cronograma&action=ajaxSave', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(res => {
        if(res.success) {
            bootstrap.Modal.getInstance(document.getElementById('actividadModal')).hide();
            loadCronograma();
        } else alert('Error al guardar');
    });
}
function editActividad(id) {
    fetch('?controller=Cronograma&action=ajaxList')
        .then(res => res.json())
        .then(data => {
            const act = data.find(a => a.id == id);
            if(act) {
                document.getElementById('actividadId').value = act.id;
                document.getElementById('actividad_nombre').value = act.nombre;
                document.getElementById('actividad_inicio').value = act.fecha_inicio;
                document.getElementById('actividad_fin').value = act.fecha_fin;
                document.getElementById('actividad_progreso').value = act.progreso;
                document.getElementById('actividad_partida').value = act.partida_id || '';
                new bootstrap.Modal(document.getElementById('actividadModal')).show();
            }
        });
}
function deleteActividad(id) {
    if(confirm('¿Eliminar actividad?')) {
        fetch('?controller=Cronograma&action=ajaxDelete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id='+id
        }).then(() => loadCronograma());
    }
}
function resetActividadForm() {
    document.getElementById('actividadId').value = '';
    document.getElementById('actividad_nombre').value = '';
    document.getElementById('actividad_inicio').value = '';
    document.getElementById('actividad_fin').value = '';
    document.getElementById('actividad_progreso').value = 0;
    document.getElementById('actividad_partida').value = '';
}
loadCronograma();