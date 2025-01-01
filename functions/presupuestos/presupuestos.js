document.addEventListener("DOMContentLoaded", () => {
  getPresupuestos(currentPage);
  cargarCliente();
  searchClientes();
  searchResponsables();
});

let currentPage = 1;

function getPresupuestos(page = 1, order = "") {
  console.log(order + page);
  currentPage = page;
  const search = document.getElementById("searchPresupuestos").value;
  $.ajax({
    url: "../../functions/presupuestos/getAllPresupuestos.php",
    method: "GET",
    dataType: "json",
    data: { search: search, page: page, order: order }, // Enviar la ordenación
    success(data) {
      if (data) {
        $("#allPresupuestos").html(data.rows);
        $("#paginationContent").html(data.pagination);
      }
    },
  });
}

document.getElementById("sortButton").addEventListener("click", function () {
  let sortButton = this;
  let currentOrder = sortButton.getAttribute("data-order");

  let newOrder;
  if (currentOrder === "DESC") {
    newOrder = "ASC";
  } else if (currentOrder === "ASC") {
    newOrder = "";
  } else {
    newOrder = "DESC";
  }
  sortButton.setAttribute("data-order", newOrder);

  const sortIcon = document.getElementById("sortIcon");
  if (newOrder === "ASC") {
    sortIcon.classList.remove("mdi-unfold-more-horizontal");
    sortIcon.classList.add("mdi-chevron-up");
  } else if (newOrder === "DESC") {
    sortIcon.classList.remove("mdi-unfold-more-horizontal");
    sortIcon.classList.add("mdi-chevron-down");
  } else {
    sortIcon.classList.add("mdi-unfold-more-horizontal");
    sortIcon.classList.remove("mdi-chevron-up", "mdi-chevron-down");
  }
  getPresupuestos(currentPage, newOrder); // Pasar la ordenación
});

function getOrder() {
  return $("#sortButton").attr("data-order");
}

let clienteGlobal;
function agregarPresupuesto() {
  const nroPresupuesto = document.getElementById("nroPresupuesto").value;
  const nroExpediente = document.getElementById("nroExpediente").value;
  const fechaInicio = document.getElementById("fechaInicio").value;
  const fechaVencimiento = document.getElementById("fechaVencimiento").value;
  const fechaAprobacion = document.getElementById("fechaAprobacion").value;
  const fechaEnvio = document.getElementById("fechaEnvio").value;
  const fechaGanado = document.getElementById("fechaGanado").value;
  const productoServicio = document.getElementById("productoServicio").value;
  const descripcion = document.getElementById("descripcion").value;
  const monto = document.getElementById("monto").value;
  const moneda = document.getElementById("moneda").value;
  const estado = document.getElementById("estado").value;
  const observacion = document.getElementById("observaciones").value;
  const clienteId = clienteGlobal.id;
  const responsableId = responsable.id;

  getDolar(moneda, monto)
    .then(() => {
      const cotizacionDolar =
        document.getElementById("cotizacionDolar").value ?? 0;
      if (clienteId) {
        $.ajax({
          url: "../../functions/presupuestos/agregarPresupuesto.php",
          method: "GET",
          data: {
            nroPresupuesto,
            nroExpediente,
            fechaInicio,
            fechaVencimiento,
            fechaAprobacion,
            productoServicio,
            descripcion,
            fechaEnvio,
            monto,
            moneda,
            fechaGanado,
            estado,
            cotizacionDolar,
            clienteId,
            responsableId,
          },
          success(response) {
            let data = JSON.parse(response);
            if (data.status === "S") {
              cerrarModal("addModal");
              getPresupuestos(currentPage, getOrder());
              document.getElementById("msjeUser").innerHTML =
                '<i class="mdi mdi-check"></i> Presupuesto agregado con éxito.';

              let presupuestoId = data.presupuestoId;

              if (presupuestoId && observacion != "") {
                $.ajax({
                  url: "../../functions/presupuestos/agregarObservacion.php",
                  type: "GET",
                  data: {
                    observacion: observacion,
                    presupuesto_id: presupuestoId,
                  },
                });
              }
            } else {
              document.getElementById("msjeUser").innerHTML =
                "Hubo un error al agregar el presupuesto.";
            }
          },
        });
      }
    })
    .catch((error) => {
      console.error("Error en el proceso de agregar presupuesto:", error);
    });
}

function cargarCliente() {
  const urlParams = new URLSearchParams(window.location.search);
  const clienteId = urlParams.get("clienteId");

  if (clienteId) {
    $.ajax({
      url: "../../functions/clientes/getCliente.php",
      method: "GET",
      dataType: "json",
      data: { id: encodeURIComponent(clienteId) },
      success(cliente) {
        clienteGlobal = cliente;
        setFechaActual();
        setDolar();
        $("#cliente").val(clienteGlobal.razon_social);
        $("#addModal").modal("show");
        // Limpiar la URL después de cargar el cliente
        const urlSinParametros =
          window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, urlSinParametros);
      },
      error(xhr, status, error) {
        console.error("Error al obtener los datos del cliente:", error);
      },
    });
  }
}

function setupAutocomplete(inputId, suggestionsId, url, mapField, globalVar) {
  const $input = $(`#${inputId}`);
  const $suggestionsBox = $(`#${suggestionsId}`);

  $input.on("input", function () {
    const query = $(this).val();

    if (query.length === 0) {
      $suggestionsBox.hide();
      return;
    }

    $.get(url, { term: query }, (data) => {
      const parsedData = JSON.parse(data);
      const results = parsedData.map((item) => item[mapField]);
      if (results.length) {
        const suggestionsHtml = results
          .map((item) => `<div class="suggestion-item">${item}</div>`)
          .join("");

        const inputWidth = $input.outerWidth();
        $suggestionsBox.css("width", inputWidth);
        $suggestionsBox.html(suggestionsHtml).show();
      } else {
        $suggestionsBox.hide();
      }

      $input.data("results", parsedData);
    });
  });

  $(document).on("click", `#${suggestionsId} .suggestion-item`, function () {
    const selectedText = $(this).text();
    const selectedItem = $input
      .data("results")
      .find((item) => item[mapField] === selectedText);

    $input.val(selectedText);
    window[globalVar] = selectedItem;
    globalVar == "clienteGlobal"
      ? (clienteGlobal = selectedItem)
      : (responsable = selectedItem);

    $suggestionsBox.hide();
  });

  $(document).click(function (event) {
    if (!$(event.target).closest(`#${suggestionsId}, #${inputId}`).length) {
      $suggestionsBox.hide();
    }
  });
}

let responsable;
function searchClientes() {
  setupAutocomplete(
    "cliente",
    "clienteSuggestions",
    "../../functions/presupuestos/searchClientes.php",
    "razon_social",
    "clienteGlobal"
  );
}

function searchResponsables() {
  setupAutocomplete(
    "responsable",
    "responsableSuggestions",
    "../../functions/presupuestos/searchResponsables.php",
    "nombre",
    "responsable"
  );
}

let presupuestoId;
function editarPresupuesto(presupuesto) {
  presupuestoId = presupuesto.id;
  getObservaciones(presupuesto.id);
  clienteGlobal = presupuesto.clienteId;
  responsable = presupuesto.responsableId;
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
  document.getElementById("estadoEdit").value = presupuesto.estado;
  document.getElementById("responsableEdit").value = presupuesto.responsable;
  document.getElementById("cotizacionDolarEdit").value =
    presupuesto.cotizacionDolar;
  document.getElementById("clienteEdit").value = presupuesto.razonSocial;

  if (!presupuesto.monto || presupuesto.monto === 0) {
    setDolar();
  }
}

function editarPresupuestoSave() {
  const nroPresupuesto = document.getElementById("nroPresupuestoEdit").value;
  const nroExpediente = document.getElementById("nroExpedienteEdit").value;
  const fechaInicio = document.getElementById("fechaInicioEdit").value;
  const fechaVencimiento = document.getElementById(
    "fechaVencimientoEdit"
  ).value;
  const fechaAprobacion = document.getElementById("fechaAprobacionEdit").value;
  const fechaEnvio = document.getElementById("fechaEnvioEdit").value;
  const fechaGanado = document.getElementById("fechaGanadoEdit").value;
  const productoServicio = document.getElementById(
    "productoServicioEdit"
  ).value;
  const descripcion = document.getElementById("descripcionEdit").value;
  const monto = document.getElementById("montoEdit").value;
  const moneda = document.getElementById("monedaEdit").value;
  const estado = document.getElementById("estadoEdit").value;
  const cotizacionDolar = !monto
    ? ""
    : document.getElementById("cotizacionDolarEdit").value;
  const clienteId = clienteGlobal;
  const responsableId = responsable;

  if (clienteId) {
    $.ajax({
      url: "../../functions/presupuestos/editarPresupuesto.php",
      method: "GET",
      data: {
        presupuestoId,
        nroPresupuesto,
        nroExpediente,
        fechaInicio,
        fechaVencimiento,
        fechaAprobacion,
        productoServicio,
        descripcion,
        fechaEnvio,
        monto,
        moneda,
        fechaGanado,
        estado,
        cotizacionDolar,
        clienteId,
        responsableId,
      },
      success(data) {
        if (data === "S") {
          cerrarModal("editModal");
          getPresupuestos(currentPage, getOrder());
          document.getElementById("msjeUser").innerHTML =
            '<i class="mdi mdi-check"></i> Presupuesto actualizado con éxito.';
        } else {
          document.getElementById("msjeUser").innerHTML =
            "Hubo un error al actualizar el presupuesto.";
        }
      },
    });
  }
}

function deletePresupuestoModal(id, nroPresupuesto) {
  document.getElementById("presupuestoIdDelete").value = id;
  document.getElementById(
    "deleteModalLabel"
  ).innerHTML = `ELIMINAR PRESUPUESTO ${nroPresupuesto}`;
}

function deletePresupuesto() {
  const id = document.getElementById("presupuestoIdDelete").value;

  $.ajax({
    url: "../../functions/presupuestos/deletePresupuesto.php",
    method: "GET",
    data: { id },
    success(data) {
      if (data === "S") {
        cerrarModal("deleteModal");
        document.getElementById(`filaPresupuesto${id}`).remove();
        getPresupuestos(currentPage, getOrder());
      }
    },
  });
}

function setFechaActual() {
  const fecha = new Date();
  const day = ("0" + fecha.getDate()).slice(-2);
  const month = ("0" + (fecha.getMonth() + 1)).slice(-2);
  const today = fecha.getFullYear() + "-" + month + "-" + day;
  document.getElementById("fechaInicio").value = today;
}

function setDolar() {
  fetch("https://dolarapi.com/v1/dolares/blue")
    .then((response) => response.json())
    .then((data) => {
      document.getElementById("cotizacionDolar").value = data.compra;
      document.getElementById("cotizacionDolarEdit").value = data.compra;
    });
}

function getDolar(moneda, monto) {
  return new Promise((resolve, reject) => {
    if (moneda === "USD" && monto != null) {
      fetch("https://dolarapi.com/v1/dolares/blue")
        .then((response) => response.json())
        .then((data) => {
          document.getElementById("cotizacionDolar").value = data.compra;
          resolve();
        })
        .catch((error) => {
          console.error("Error al obtener la cotización del dólar:", error);
          reject(error);
        });
    } else {
      document.getElementById("cotizacionDolar").value = null;
      resolve();
    }
  });
}

function getObservaciones(presupuestoId) {
  $.ajax({
    url: "../../functions/observaciones/getObservaciones.php",
    method: "GET",
    data: {
      presupuesto_id: presupuestoId,
    },
    success: function (data) {
      if (data != "") {
        $("#observaciones-lista").html(data);
      }
    },
  });
}

$("#guardar-observacion").click(function () {
  let nuevaObservacion = $("#nueva-observacion").val();

  if (nuevaObservacion.trim() !== "") {
    $.ajax({
      url: "../../functions/observaciones/agregarObservacion.php",
      type: "GET",
      data: {
        observacion: nuevaObservacion,
        presupuesto_id: presupuestoId,
      },
      success: function (response) {
        $("#nueva-observacion").val(""); // Limpiar el textarea
        getObservaciones(presupuestoId); // Recargar observaciones
      },
    });
  } else {
    alert("Por favor, ingresa una observación.");
  }
});

function editarObservacion(observacionId) {
  const textarea = document.querySelector(
    `#observacion-${observacionId} .observacion-input`
  );
  textarea.removeAttribute("readonly");
  textarea.removeAttribute("disabled");
  textarea.focus();

  const editButton = document.querySelector(
    `#observacion-${observacionId} .mdi-pencil`
  );
  editButton.classList.remove("mdi-pencil");
  editButton.classList.add("mdi-content-save");
  editButton.style.backgroundColor = "green";

  editButton.onclick = function () {
    const nuevaObservacion = textarea.value;
    $.ajax({
      url: "../../functions/observaciones/editarObservacion.php",
      type: "GET",
      data: {
        observacion_id: observacionId,
        nueva_observacion: nuevaObservacion,
      },
      success: function (data) {
        if (data === "S") {
          textarea.setAttribute("readonly", true);
          textarea.setAttribute("disabled", true);

          editButton.classList.remove("mdi-content-save");
          editButton.classList.add("mdi-pencil");
          editButton.style.backgroundColor = "orange";

          getObservaciones(presupuestoId);
        } else {
          alert("Error al editar la observación.");
        }
      },
    });
  };
}

function eliminarObservacion(observacionId) {
  $.ajax({
    url: "../../functions/observaciones/eliminarObservacion.php",
    type: "GET",
    data: {
      observacion_id: observacionId,
    },
    success: function (data) {
      if (data === "S") {
        getObservaciones(presupuestoId);
        document.getElementById("observacion-" + observacionId).remove();
      } else {
        alert("Error al eliminar la observación.");
      }
    },
  });
}

$(".modal").on("hidden.bs.modal", function () {
  $("#observaciones-lista").html("");
});

document.addEventListener("DOMContentLoaded", function () {
  let form = document.getElementById("addPresupuestoForm");

  form.addEventListener(
    "submit",
    function (event) {
      let clienteSeleccionado = clienteGlobal && clienteGlobal.id;
      let responsableSeleccionado = responsable && responsable.id;

      if (
        !form.checkValidity() ||
        !clienteSeleccionado ||
        !responsableSeleccionado
      ) {
        event.preventDefault();
        event.stopPropagation();

        if (!clienteSeleccionado) {
          document.getElementById("cliente").classList.add("is-invalid");
        } else {
          document.getElementById("cliente").classList.remove("is-invalid");
          document.getElementById("cliente").classList.add("is-valid");
        }

        if (!responsableSeleccionado) {
          document.getElementById("responsable").classList.add("is-invalid");
        } else {
          document.getElementById("responsable").classList.remove("is-invalid");
          document.getElementById("responsable").classList.add("is-valid");
        }
      } else {
        event.preventDefault();
        agregarPresupuesto();
      }

      form.classList.add("was-validated");
    },
    false
  );
});

function cerrarModal(modalId) {
  $(`#${modalId}`).modal("hide");
  let modal = document.getElementById(modalId);
  modal.style.display = "none";
  document.body.classList.remove("modal-open");
  const modalBackdrop = document.querySelectorAll(".modal-backdrop");
  if (modalBackdrop) {
    modalBackdrop.forEach((backdrop) => backdrop.remove());
  }
  if (modalId === "addModal") {
    limpiarFormulario("addPresupuestoForm");
  }
}

function limpiarFormulario(formId) {
  const form = document.getElementById(formId);
  form.reset();
  form.classList.remove("was-validated"); // Elimina la clase de validación
  $(form).find("input").removeClass("is-invalid is-valid");
  $(form).find("textarea").removeClass("is-invalid is-valid");
}
