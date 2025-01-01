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
        $("#nueva-observacion").val("");
        getObservaciones(presupuestoId);
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
