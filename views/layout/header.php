<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Construcción Civil</title>
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>assets/images/icono.ico.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://unpkg.com/vis-timeline@latest/dist/vis-timeline-graph2d.min.css" rel="stylesheet">
    <script src="https://unpkg.com/vis-timeline@latest/dist/vis-timeline-graph2d.min.js"></script>
    <link href="<?php echo BASE_URL; ?>assets/css/custom.css" rel="stylesheet">
    
    <!-- CSS específico para la página de obras (solo cuando controller=Obra) -->
    <?php 
    $controller = isset($_GET['controller']) ? $_GET['controller'] : '';
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    if ($controller === 'Obra' && ($action === '' || $action === 'index')): 
    ?>
    <link href="<?php echo BASE_URL; ?>assets/css/obras.css" rel="stylesheet">
    <?php endif; ?>
</head>
<body>
<header class="navbar navbar-dark bg-primary sticky-top">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="Logo" height="40" class="me-2" style="border-radius: 5px;" onerror="this.style.display='none'">
            <span class="navbar-brand mb-0 h1"><i class="bi bi-building me-2"></i>Construcción Civil - Sistema Integral</span>
        </div>
        <div class="d-flex align-items-center text-white">
            <small id="currentDateTime" class="me-3"></small>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="darkModeToggle">
                <label class="form-check-label text-white" for="darkModeToggle"><i class="bi bi-moon"></i></label>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid mt-3">
    <div class="row">
        <?php include 'sidebar.php'; ?>
        <main class="col-md-10 ms-sm-auto px-md-4">