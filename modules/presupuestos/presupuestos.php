<?php
include('../../views/body/head.php');
?>
<main role="main" class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-2">
                    <div class="col">
                        <h2 class="h5 page-title">Presupuestos</h2>
                    </div>
                    <div class="col-auto">
                        <label for=""><i class="mdi mdi-calendar"></i> <span id="date"></span> - <i class="mdi mdi-clock-outline"></i> <span id="time"></span></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-12 my-4">
                        <div class="card shadow">
                            <div class="card-body">
                                <label class="">Busca y modifica todos los presupuestos.</label><br>
                                <label class="mt-2 pb-2" id="msjeUser"></label>
                                <div class="toolbar">
                                    <form class="form">
                                        <div class="form-row">
                                            <div class="form-group col-auto">
                                                <label for="search" class="sr-only">Search</label>
                                                <input type="text" class="form-control" id="searchPresupuestos" onkeyup="getPresupuestos()" placeholder="Filtrar">
                                            </div>
                                            <div class="form-group col-auto">
                                                <label class="btn btn-primary" data-toggle="modal" data-target="#addModal" onclick="setFechaActual()"><i class="mdi mdi-plus-circle-outline"></i> AGREGAR</label>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <table class="table table-borderless table-hover">
                                    <thead>
                                        <tr>
                                            <th>PRESUPUESTO</th>
                                            <th>EXPEDIENTE</th>
                                            <th>VENCIMIENTO</th>
                                            <th>PRODUCTO/SERVICIO</th>
                                            <th>MONTO</th>
                                            <th>ESTADO</th>
                                            <th>RESPONSABLE</th>
                                        </tr>
                                    </thead>
                                    <tbody id="allPresupuestos"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">EDITAR PRESUPUESTO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="col">
                    <div class="form-group">
                        <label for="nroPresupuestoEdit" class="col-form-label">Nro Presupuesto:</label>
                        <input type="text" class="form-control" id="nroPresupuestoEdit">
                    </div>
                    <div class="form-group">
                        <label for="nroExpedienteEdit" class="col-form-label">Nro Expediente:</label>
                        <input type="text" class="form-control" id="nroExpedienteEdit">
                    </div>
                    <div class="form-group">
                        <label for="fechaInicioEdit" class="col-form-label">Fecha Inicio:</label>
                        <input type="date" class="form-control" id="fechaInicioEdit">
                    </div>
                    <div class="form-group">
                        <label for="fechaVencimientoEdit" class="col-form-label">Fecha Vencimiento:</label>
                        <input type="date" class="form-control" id="fechaVencimientoEdit">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="productoServicioEdit" class="col-form-label">Producto/Servicio:</label>
                        <input type="text" class="form-control" id="productoServicioEdit">
                    </div>
                    <div class="form-group">
                        <label for="montoEdit" class="col-form-label">Monto:</label>
                        <input type="text" class="form-control" id="montoEdit">
                    </div>
                    <div class="form-group">
                        <label for="monedaEdit" class="col-form-label">Moneda:</label>
                        <input type="text" class="form-control" id="monedaEdit">
                    </div>
                    <div class="form-group">
                        <label for="statusEdit" class="col-form-label">Status:</label>
                        <input type="text" class="form-control" id="statusEdit">
                    </div>
                    <div class="form-group">
                        <label for="responsableEdit" class="col-form-label">Responsable:</label>
                        <input type="text" class="form-control" id="responsableEdit">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" class="form-control" id="presupuestoIdEdit">
                <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">CANCELAR</button>
                <button type="button" class="btn mb-2 btn-primary" onclick="editarPresupuestoSave()">GUARDAR CAMBIOS</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="justify-content: center; text-align:center;">
                <input type="hidden" class="form-control" id="presupuestoIdDelete">
                <button type="button" class="btn mb-2 btn-primary" onclick="deletePresupuesto()">ELIMINAR PRESUPUESTO</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">AGREGAR PRESUPUESTO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="nroPresupuesto" class="col-form-label">Nro Presupuesto:</label>
                            <input type="text" class="form-control" id="nroPresupuesto">
                        </div>
                        <div class="form-group">
                            <label for="productoServicio" class="col-form-label">Producto/Servicio:</label>
                            <select class="form-control" id="productoServicio">
                                <option value="producto">Producto</option>
                                <option value="servicio">Servicio</option>
                                <option value="producto+servicio">Producto + Servicio</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cliente" class="col-form-label">Cliente:</label>
                            <input type="text" class="form-control" id="cliente">
                        </div>
                        <div class="form-group">
                            <label for="moneda" class="col-form-label">Moneda:</label>
                            <input type="text" class="form-control" id="moneda">
                        </div>
                        <div class="form-group">
                            <label for="fechaVencimiento" class="col-form-label">Fecha Vencimiento:</label>
                            <input type="date" class="form-control" id="fechaVencimiento">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="nroExpediente" class="col-form-label">Nro Expediente:</label>
                            <input type="text" class="form-control" id="nroExpediente">
                        </div>
                        <div class="form-group">
                            <label for="responsable" class="col-form-label">Responsable:</label>
                            <input type="text" class="form-control" id="responsable">
                        </div>
                        <div class="form-group">
                            <label for="fechaEnvio" class="col-form-label">Fecha Envío:</label>
                            <input type="date" class="form-control" id="fechaEnvio">
                        </div>
                        <div class="form-group">
                            <label for="monto" class="col-form-label">Monto:</label>
                            <input type="text" class="form-control" id="monto">
                        </div>
                        <div class="form-group">
                            <label for="observaciones" class="col-form-label">Observaciones:</label>
                            <textarea class="form-control" id="observaciones"></textarea>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="fechaInicio" class="col-form-label">Fecha Inicio:</label>
                            <input type="date" class="form-control" id="fechaInicio">
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-form-label">Status:</label>
                            <select class="form-control" id="status">
                                <option value="en proceso de cotización">En Proceso de Cotización</option>
                                <option value="pendiente de análisis">Pendiente de Análisis</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fechaGanado" class="col-form-label">Fecha Ganado:</label>
                            <input type="date" class="form-control" id="fechaGanado">
                        </div>
                        <div class="form-group">
                            <label for="fechaAprobacion" class="col-form-label">Fecha Aprobación:</label>
                            <input type="date" class="form-control" id="fechaAprobacion">
                        </div>
                        <div class="form-group">
                            <label for="descripcion" class="col-form-label">Descripción:</label>
                            <textarea class="form-control" id="descripcion"></textarea>
                        </div>
                        <!-- <div class="form-group">
                            <label for="cotizacionDolar" class="col-form-label">Cotización Dólar:</label>
                            <input type="text" class="form-control" id="cotizacionDolar">
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">CANCELAR</button>
                <button type="button" class="btn mb-2 btn-primary" onclick="agregarPresupuesto()">GUARDAR CAMBIOS</button>
            </div>
        </div>
    </div>
</div>

<?php
include('../../views/body/footer.php');
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<script src="../../functions/presupuestos/presupuestos.js"></script>