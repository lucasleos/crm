<?php
include('../../views/body/head.php');
?>
<style>
    #estados .card-select[data-value="Pendiente de análisis"].selected {
        background-color: #17a2b8;
    }

    #estados .card-select[data-value="Esperando Respuesta"].selected {
        background-color: #ffc107;
    }

    #estados .card-select[data-value="En proceso de cotización"].selected {
        background-color: #1a1aff;
    }

    #estados .card-select[data-value="Cerrado - GANADO"].selected {
        background-color: #28a745;
    }

    #estados .card-select[data-value="Cerrado - PERDIDO"].selected {
        background-color: #dc3545;
    }

    #estados .card-select[data-value="Cerrado - VENCIDO"].selected {
        background-color: #6c757d;
    }

    #estados .card-select[data-value="Cerrado - NO COMERCIALIZAMOS"].selected {
        background-color: #ff5000;
    }

    .stretch-card {
        margin-left: 32%;
        overflow-y: hidden;
        position: relative;
        z-index: 0;
    }

    /* Estilos personalizados para el tooltip */
    .tooltip-inner {
        background-color: #28a745 !important;
        font-size: 15px;
        padding: 10px;
    }

    .tooltip.bs-tooltip-right .arrow::before {
        border-right-color: #28a745 !important;
        /* Color verde para la flecha del tooltip */
    }
</style>
<main role="main" class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-2">
                    <div class="col">
                        <h2 class="h5 page-title">Reportes</h2>
                    </div>
                    <div class="col-auto">
                        <label for=""><i class="mdi mdi-calendar"></i> <span id="date"></span> - <i
                                class="mdi mdi-clock-outline"></i> <span id="time"></span></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="tablesection">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row p-0 m-0">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 p-0 m-0">
                        <div class="card shadow">
                            <div class="card-body pb-3 pt-3">
                                <a class="tab_wb active">REPORTE UNO</a>
                                <!-- <a class="tab_wb ml-2" href="#">REPORTE DOS</a>
                                <a class="tab_wb ml-2" href="#">REPORTE TRES</a> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-12 fixed-column p-0 m-0" id="allFixed">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1 class="p-2 mt-3 text-center" id="saldototal">$0</h1>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card shadow">
                            <div class="card-body p-2 pt-4">
                                <div class="form-group col-auto">
                                    <label>Años
                                        <span class="select-all" data-target="anios">
                                            <i class="mdi mdi-checkbox-multiple-marked-circle-outline"></i>
                                        </span>
                                        <span class="deselect-all" data-target="anios" style="display:none;">
                                            <i class="mdi mdi-checkbox-multiple-marked-circle"></i>
                                        </span>
                                    </label>
                                    <div class="card-group-select" id="anios">
                                        <div class="card-select" data-value="2024">2024</div>
                                    </div>
                                </div>
                                <div class="form-group col-auto">
                                    <label>Meses
                                        <span class="select-all" data-target="meses">
                                            <i class="mdi mdi-checkbox-multiple-marked-circle-outline"></i>
                                        </span>
                                        <span class="deselect-all" data-target="meses" style="display:none;">
                                            <i class="mdi mdi-checkbox-multiple-marked-circle"></i>
                                        </span>
                                    </label>
                                    <div class="card-group-select" id="meses">
                                    </div>
                                </div>
                                <div class="form-group col-auto">
                                    <label>Monedas
                                        <span class="select-all" data-target="monedas">
                                            <i class="mdi mdi-checkbox-multiple-marked-circle-outline"></i>
                                        </span>
                                        <span class="deselect-all" data-target="monedas" style="display:none;">
                                            <i class="mdi mdi-checkbox-multiple-marked-circle"></i>
                                        </span>
                                    </label>
                                    <div class="card-group-select" id="monedas">
                                        <div class="card-select" data-value="USD">Dólar</div>
                                        <div class="card-select" data-value="ARS">Peso</div>
                                    </div>
                                </div>
                                <div class="form-group col-auto">
                                    <label>Estados
                                        <span class="select-all" data-target="estados">
                                            <i class="mdi mdi-checkbox-multiple-marked-circle-outline"></i>
                                        </span>
                                        <span class="deselect-all" data-target="estados" style="display:none;">
                                            <i class="mdi mdi-checkbox-multiple-marked-circle"></i>
                                        </span>
                                    </label>
                                    <div class="card-group-select" id="estados">
                                        <div class="card-select" data-value="Pendiente de análisis">Pendiente de análisis</div>
                                        <div class="card-select" data-value="Esperando Respuesta">Esperando Respuesta</div>
                                        <div class="card-select" data-value="En proceso de cotización">En proceso de cotización</div>
                                        <div class="card-select" data-value="Cerrado - GANADO">Cerrado - GANADO</div>
                                        <div class="card-select" data-value="Cerrado - PERDIDO">Cerrado - PERDIDO</div>
                                        <div class="card-select" data-value="Cerrado - VENCIDO">Cerrado - VENCIDO</div>
                                        <div class="card-select" data-value="Cerrado - NO COMERCIALIZAMOS">Cerrado - NO COMERCIALIZAMOS</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-12 row stretch-card" id="allStretch" style="display:block !important">

                <div class="col-lg-12 col-md-12 col-sm-12 col-12 p-0 m-0" id="graphTabla">
                    <div class="card shadow">
                        <div class="card-body p-4 pb-5">
                            <label onclick="maxDiv('graphTabla')" class="allfs" style="cursor: pointer;">
                                <i class="mdi mdi-fullscreen"></i>
                            </label>
                            <label onclick="minDiv('graphTabla')" class="allfsn" style="display:none">
                                <i class="mdi mdi-fullscreen-exit"></i>
                            </label>
                            <div class="card-select btn_excel" onclick="exportarToExcel()"
                                style="margin-bottom: 10px; display: block; right:10px">
                                <i class="mdi mdi-microsoft-excel"></i>
                                Exportar
                            </div>

                            <h4 class="text-center" id="tableTitle" style="display:none">Facturación por Estado y Mes</h4>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12" id="seleccionarDatos0" style="text-align:center;">
                                <iconify-icon icon="solar:chart-square-broken" style="font-size:120px; "></iconify-icon>
                                <h4>Selecciona los datos correspondientes</h4>
                            </div>

                            <div class="row mt-4"
                                style="display:block !important; max-height: 450px; overflow: auto; border-radius: 10px">
                                <div id="tablafacturacion"></div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-12 p-0 m-0" id="graphUno">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row p-2" style="font-size:16px">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <iconify-icon icon="solar:chart-square-bold-duotone"></iconify-icon>
                                        Gráfico Uno
                                    </label>
                                    <label onclick="maxDiv('graphUno')" class="allfs" style="cursor: pointer;">
                                        <i class="mdi mdi-fullscreen"></i>
                                    </label>
                                    <label onclick="minDiv('graphUno')" class="allfsn" style="position: absolute;right: 20px;display:none">
                                        <i class="mdi mdi-fullscreen-exit"></i>
                                    </label>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-12" id="seleccionarDatos1" style="text-align:center;">
                                    <iconify-icon icon="solar:chart-square-broken" style="font-size:120px; "></iconify-icon>
                                    <h4>Selecciona los datos correspondientes</h4>
                                </div>
                                <div id="grafico-donut-1" style="width: 100%; height: 600px; margin: 0 auto;display:none"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-12 p-0 m-0" id="graphDos">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row p-2" style="font-size:16px">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <iconify-icon icon="solar:chart-square-bold-duotone"></iconify-icon>
                                        Gráfico Dos
                                    </label>
                                    <label onclick="maxDiv('graphDos')" class="allfs" style="cursor: pointer;">
                                        <i class="mdi mdi-fullscreen"></i>
                                    </label>
                                    <label onclick="minDiv('graphDos')" class="allfsn" style="position: absolute;right: 20px;display:none">
                                        <i class="mdi mdi-fullscreen-exit"></i>
                                    </label>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-12" id="seleccionarDatos2" style="text-align:center;">
                                    <iconify-icon icon="solar:chart-square-broken" style="font-size:120px; "></iconify-icon>
                                    <h4>Selecciona los datos correspondientes</h4>
                                </div>
                                <div id="grafico-funnel-1" style="width: 100%; height: 600px; margin: 0 auto;display:none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</main>
<?php
include('../../views/body/footer.php');
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../public/js/bootstrap.min.js"></script>
<script src='../../public/js/jquery.dataTables.min.js'></script>
<script src='../../public/js/dataTables.bootstrap4.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.17.0/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="../../functions/report/main.js"></script>
<script src="../../functions/report/exportData.js"></script>