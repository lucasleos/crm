let clienteGlobal;

document.addEventListener("DOMContentLoaded", function () {
  getPresupuestos();

  const clienteSeleccionado = localStorage.getItem("clienteSeleccionado");
  if (clienteSeleccionado) {
    const cliente = JSON.parse(clienteSeleccionado);
    clienteGlobal = cliente;
    setFechaActual();
    document.getElementById("cliente").value = cliente.razonSocial;
    $("#addModal").modal("show");
    localStorage.removeItem("clienteSeleccionado");
  }
});

function getPresupuestos() {
  var search = document.getElementById("searchPresupuestos").value;
  $.ajax({
    url: "../../functions/presupuestos/getAllPresupuestos.php",
    method: "GET",
    data: {
      search: search,
    },
    success: function (data) {
      if (data != "") {
        $("#allPresupuestos").html(data);
      }
    },
  });
}

function searchClientes() {
  $("#cliente").typeahead({
    source: function (query, process) {
      return $.get(
        "../../functions/stock/searchClientes.php",
        { term: query },
        function (data) {
          return process(JSON.parse(data));
        }
      );
    },
  });
}

$(document).ready(function () {
  $("#addModal").on("shown.bs.modal", function () {
    searchClientes();
  });
});

function agregarPresupuesto() {
  const nroPresupuesto = document.getElementById("nroPresupuesto").value;
  const nroExpediente = document.getElementById("nroExpediente").value;
  const fechaInicio = document.getElementById("fechaInicio").value;
  const fechaVencimiento = document.getElementById("fechaVencimiento").value;
  const fechaAprobacion = document.getElementById("fechaAprobacion").value;
  const productoServicio = document.getElementById("productoServicio").value;
  const descripcion = document.getElementById("descripcion").value;
  const fechaEnvio = document.getElementById("fechaEnvio").value;
  const monto = document.getElementById("monto").value;
  const moneda = document.getElementById("moneda").value;
  const fechaGanado = document.getElementById("fechaGanado").value;
  const status = document.getElementById("status").value;
  const observaciones = document.getElementById("observaciones").value;
  const responsable = document.getElementById("responsable").value;
  const cotizacionDolar = "100000";
  const clienteId = clienteGlobal ? clienteGlobal.id : null;

  if (nroPresupuesto) {
    $.ajax({
      url: "../../functions/presupuestos/agregarPresupuesto.php",
      method: "GET",
      data: {
        nroPresupuesto: nroPresupuesto,
        nroExpediente: nroExpediente,
        fechaInicio: fechaInicio,
        fechaVencimiento: fechaVencimiento,
        fechaAprobacion: fechaAprobacion,
        productoServicio: productoServicio,
        descripcion: descripcion,
        fechaEnvio: fechaEnvio,
        monto: monto,
        moneda: moneda,
        fechaGanado: fechaGanado,
        status: status,
        observaciones: observaciones,
        responsable: responsable,
        cotizacionDolar: cotizacionDolar,
        clienteId: clienteId,
      },
      success: function (data) {
        if (data == "S") {
          cerrarModal("addModal");
          getPresupuestos();
          document.getElementById("msjeUser").innerHTML =
            '<i class="mdi mdi-check"></i> Presupuesto agregado con éxito.';
        } else {
          document.getElementById("msjeUser").innerHTML =
            "Hubo un error al agregar el presupuesto.";
        }
      },
    });
  }
}

function editarPresupuesto(presupuesto) {
  document.getElementById("nroPresupuestoEdit").value =
    presupuesto.nroPresupuesto;
  document.getElementById("nroExpedienteEdit").value =
    presupuesto.nroExpediente;
  document.getElementById("fechaInicioEdit").value = presupuesto.fechaInicio;
  document.getElementById("fechaVencimientoEdit").value =
    presupuesto.fechaVencimiento;
  document.getElementById("fechaAprobacionEdit").value =
    presupuesto.fechaAprobacion;
  document.getElementById("productoServicioEdit").value =
    presupuesto.productoServicio;
  document.getElementById("descripcionEdit").value = presupuesto.descripcion;
  document.getElementById("fechaEnvioEdit").value = presupuesto.fechaEnvio;
  document.getElementById("montoEdit").value = presupuesto.monto;
  document.getElementById("monedaEdit").value = presupuesto.moneda;
  document.getElementById("fechaGanadoEdit").value = presupuesto.fechaGanado;
  document.getElementById("statusEdit").value = presupuesto.status;
  document.getElementById("observacionesEdit").value =
    presupuesto.observaciones;
  document.getElementById("responsableEdit").value = presupuesto.responsable;
  document.getElementById("cotizacionDolarEdit").value =
    presupuesto.cotizacionDolar;
  document.getElementById("clienteIdEdit").value = presupuesto.clienteId;
  document.getElementById("presupuestoIdEdit").value = presupuesto.id;
}

function deletePresupuestoModal(id, nroPresupuesto) {
  document.getElementById("presupuestoIdDelete").value = id;
  document.getElementById("deleteModalLabel").innerHTML =
    "ELIMINAR PRESUPUESTO " + nroPresupuesto;
}

function deletePresupuesto() {
  var id = document.getElementById("presupuestoIdDelete").value;
  console.log(id);
  $.ajax({
    url: "../../functions/stock/deletePresupuesto.php",
    method: "GET",
    data: {
      id: id,
    },
    success: function (data) {
      if (data == "S") {
        cerrarModal("deleteModal");
        document.getElementById("filaPresupuesto" + id).remove();
        getPresupuestos();
      }
    },
  });
}

function editarPresupuestoSave() {
  const nroPresupuesto = document.getElementById("nroPresupuestoEdit").value;
  const nroExpediente = document.getElementById("nroExpedienteEdit").value;
  const fechaInicio = document.getElementById("fechaInicioEdit").value;
  const fechaVencimiento = document.getElementById(
    "fechaVencimientoEdit"
  ).value;
  const fechaAprobacion = document.getElementById("fechaAprobacionEdit").value;
  const productoServicio = document.getElementById(
    "productoServicioEdit"
  ).value;
  const descripcion = document.getElementById("descripcionEdit").value;
  const fechaEnvio = document.getElementById("fechaEnvioEdit").value;
  const monto = document.getElementById("montoEdit").value;
  const moneda = document.getElementById("monedaEdit").value;
  const fechaGanado = document.getElementById("fechaGanadoEdit").value;
  const status = document.getElementById("statusEdit").value;
  const observaciones = document.getElementById("observacionesEdit").value;
  const responsable = document.getElementById("responsableEdit").value;
  const cotizacionDolar = document.getElementById("cotizacionDolarEdit").value;
  const clienteId = document.getElementById("clienteIdEdit").value;
  const id = document.getElementById("presupuestoIdEdit").value;

  if (
    nroPresupuesto &&
    nroExpediente &&
    fechaInicio &&
    fechaVencimiento &&
    productoServicio &&
    monto &&
    moneda &&
    status &&
    responsable &&
    clienteId &&
    id
  ) {
    $.ajax({
      url: "../../functions/stock/editarPresupuesto.php",
      method: "GET",
      data: {
        id: id,
        nroPresupuesto: nroPresupuesto,
        nroExpediente: nroExpediente,
        fechaInicio: fechaInicio,
        fechaVencimiento: fechaVencimiento,
        fechaAprobacion: fechaAprobacion,
        productoServicio: productoServicio,
        descripcion: descripcion,
        fechaEnvio: fechaEnvio,
        monto: monto,
        moneda: moneda,
        fechaGanado: fechaGanado,
        status: status,
        observaciones: observaciones,
        responsable: responsable,
        cotizacionDolar: cotizacionDolar,
        clienteId: clienteId,
      },
      success: function (data) {
        if (data === "S") {
          cerrarModal("editModal");
          getPresupuestos();
          document.getElementById("msjeUser").innerHTML =
            '<i class="mdi mdi-check"></i> Presupuesto editado con éxito.';
        } else {
          document.getElementById("msjeUser").innerHTML =
            "Hubo un error al editar el presupuesto.";
        }
      },
    });
  }
}

function setFechaActual() {
  var today = new Date().toISOString().split("T")[0];
  document.getElementById("fechaInicio").value = today;
}

function cerrarModal(modal) {
  var modal = document.getElementById(modal);
  modal.style.display = "none";
  document.body.classList.remove("modal-open");
  var modalBackdrop = document.getElementsByClassName("modal-backdrop");
  if (modalBackdrop[0]) {
    modalBackdrop[0].parentNode.removeChild(modalBackdrop[0]);
  }
}
