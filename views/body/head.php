<?php
include("../../config/conexion.php");
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../public/assets/images/favicon.png">
    <title>CRM</title>
    <link rel="stylesheet" href="../../public/css/simplebar.css">

    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/7.4.47/css/materialdesignicons.min.css">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="../../public/css/feather.css">
    <link rel="stylesheet" href="../../public/css/select2.css">
    <link rel="stylesheet" href="../../public/css/dropzone.css">
    <link rel="stylesheet" href="../../public/css/uppy.min.css">
    <link rel="stylesheet" href="../../public/css/jquery.steps.css">
    <link rel="stylesheet" href="../../public/css/jquery.timepicker.css">
    <link rel="stylesheet" href="../../public/css/quill.snow.css">
    <link rel="stylesheet" href="../../public/css/dataTables.bootstrap4.css">

    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="../../public/css/daterangepicker.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="../../public/css/app-light.css" id="lightTheme" disabled>
    <link rel="stylesheet" href="../../public/css/app-dark.css" id="darkTheme">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>


</head>

<body class="vertical dark">
    <div class="wrapper">
        <nav class="topnav navbar navbar-light">
            <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
                <i class="fe fe-menu navbar-toggler-icon"></i>
            </button>
            <ul class="nav">
                <li class="nav-item dropdown">
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="../../functions/login/cerrarSesion.php"><i class="mdi mdi-power"></i> Cerrar Sesión</a>
                    </div>
                </li>
            </ul>
        </nav>
        <aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
            <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
                <i class="fe fe-x"><span class="sr-only"></span></i>
            </a>
            <nav class="vertnav navbar navbar-light">
                <div class="w-100 mb-4 d-flex">
                    <a class="navbar-brand mx-auto mt-2 flex-fill text-center">
                        <img src="../../public/assets/images/sslogo2.png" width="200" alt="" style="margin-left:0px">
                    </a>
                </div>
                <p class="text-muted nav-heading mt-4 mb-1">
                    <span><i class="mdi mdi-home"></i> HOME</span>
                </p>
                <ul class="navbar-nav flex-fill w-100 mb-2">
                    <li class="nav-item dropdown">
                        <a href="../../modules/home/" class="nav-link">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span class="ml-3 item-text">DASHBOARD</span>
                        </a>
                    </li>
                </ul>
                <p class="text-muted nav-heading mt-4 mb-1">
                    <span><i class="mdi mdi-layers-triple"></i> MÓDULOS</span>
                </p>
                <ul class="navbar-nav flex-fill w-100 mb-2">
                    <li class="nav-item w-100">
                        <a href="#stock" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                            <i class="mdi mdi-package-variant"></i>
                            <span class="ml-3 item-text">STOCK</span>
                        </a>
                        <ul class="collapse list-unstyled pl-4 w-100" id="stock">
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="../../modules/stock/clientes.php">
                                    <i class="mdi mdi-archive-settings-outline"></i>
                                    <span class="ml-3 item-text">ABM CLIENTES</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="collapse list-unstyled pl-4 w-100" id="stock">
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="../../modules/presupuestos/presupuestos.php">
                                    <i class="mdi mdi-archive-settings-outline"></i>
                                    <span class="ml-3 item-text">PRESUPUESTOS</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>
    </div>
</body>

</html>

<script>
    if (document.getElementById('tieneContador')) {
        var icono = document.getElementById('tieneContador');

        function alternarOpacidad() {
            if (icono.style.opacity === '0') {
                icono.style.opacity = '1';
            } else {
                icono.style.opacity = '0';
            }
        }
        setInterval(alternarOpacidad, 500);
    }
</script>