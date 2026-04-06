<h2><i class="bi bi-speedometer2"></i> Dashboard</h2>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Cómputos</h6>
                        <h3 id="totalComputos">0</h3>
                    </div>
                    <i class="bi bi-rulers display-6"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total APUs</h6>
                        <h3 id="totalApus">0</h3>
                    </div>
                    <i class="bi bi-calculator display-6"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Presupuesto Total</h6>
                        <h4 id="presupuestoTotal">Bs. 0</h4>
                    </div>
                    <i class="bi bi-currency-dollar display-6"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Grupos</h6>
                        <h3 id="totalGrupos">0</h3>
                    </div>
                    <i class="bi bi-tags display-6"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-bar-chart"></i> Cómputos por Partida
            </div>
            <div class="card-body">
                <canvas id="computosChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-pie-chart"></i> Precios Unitarios APU
            </div>
            <div class="card-body">
                <canvas id="apusChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Cargar datos del dashboard
fetch('?controller=Dashboard&action=ajaxData')
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('totalComputos').innerText = data.total_computos;
            document.getElementById('totalApus').innerText = data.total_apus;
            document.getElementById('presupuestoTotal').innerText = 'Bs. ' + data.presupuesto_total.toFixed(2);
            document.getElementById('totalGrupos').innerText = data.total_grupos;
            
            // Gráfico de cómputos
            const computosLabels = Object.keys(data.computos_por_partida);
            const computosValues = Object.values(data.computos_por_partida);
            
            if (computosLabels.length > 0) {
                new Chart(document.getElementById('computosChart'), {
                    type: 'bar',
                    data: {
                        labels: computosLabels.slice(0, 10), // Mostrar solo top 10
                        datasets: [{
                            label: 'Cantidad Computada',
                            data: computosValues.slice(0, 10),
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: false }
                        }
                    }
                });
            }
            
            // Gráfico de APU
            const apuLabels = data.apus_data.map(a => a.codigo);
            const apuValues = data.apus_data.map(a => a.precio_unitario);
            
            if (apuLabels.length > 0) {
                new Chart(document.getElementById('apusChart'), {
                    type: 'pie',
                    data: {
                        labels: apuLabels,
                        datasets: [{
                            data: apuValues,
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'right' }
                        }
                    }
                });
            }
        } else {
            console.error('Error:', data.error);
            document.getElementById('totalComputos').innerText = 'Error';
        }
    })
    .catch(error => {
        console.error('Error fetching dashboard data:', error);
        document.getElementById('totalComputos').innerText = 'Error';
    });
</script>