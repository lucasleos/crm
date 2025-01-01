document.addEventListener("DOMContentLoaded", function () {
  getResponsables(currentPage);
});

let currentPage = 1;
function getResponsables(page = 1) {
  currentPage = page;
  const search = document.getElementById("searchResponsables").value;
  $.ajax({
    url: "../../functions/responsables/getAllResponsables.php",
    method: "GET",
    dataType: "json",
    data: {
      search: search,
      page: page,
    },
    success: function (data) {
      if (data) {
        $("#allResponsables").html(data.rows);
        $("#paginationContainer").html(data.pagination);
      }
    },
  });
}

function agregarResponsable() {
  const nombre = document.getElementById("nombre").value;
  const dni = document.getElementById("dni").value;
  const correo = document.getElementById("correo").value;
  const telefono = document.getElementById("telefono").value;

  $.ajax({
    url: "../../functions/responsables/agregarResponsable.php",
    method: "GET",
    data: {
      nombre: nombre,
      dni: dni,
      correo: correo,
      telefono: telefono,
    },
    success: function (data) {
      if (data === "S") {
        cerrarModal("addModal");
        getResponsables(currentPage);
        document.getElementById("msjeUser").innerHTML =
          '<i class="mdi mdi-check"></i> Responsable agregado con éxito.';
      } else {
        document.getElementById("msjeUser").innerHTML =
          "Hubo un error al agregar el responsable.";
      }
    },
  });
}

let responsableId;
function editarResponsable(responsable) {
  document.getElementById("nombreEdit").value = responsable.nombre;
  document.getElementById("dniEdit").value = responsable.dni;
  document.getElementById("correoEdit").value = responsable.correo;
  document.getElementById("telefonoEdit").value = responsable.telefono;
  responsableId = responsable.id;
}

function editarResponsableSave() {
  const nombre = document.getElementById("nombreEdit").value;
  const dni = document.getElementById("dniEdit").value;
  const correo = document.getElementById("correoEdit").value;
  const telefono = document.getElementById("telefonoEdit").value;
  const id = responsableId;

  if (nombre && correo && dni && telefono && id) {
    $.ajax({
      url: "../../functions/responsables/editarResponsable.php",
      method: "GET",
      data: {
        id: id,
        nombre: nombre,
        correo: correo,
        dni: dni,
        telefono: telefono,
      },
      success: function (data) {
        if (data === "S") {
          cerrarModal("editModal");
          getResponsables(currentPage);
          document.getElementById("msjeUser").innerHTML =
            '<i class="mdi mdi-check"></i> Responsable editado con éxito.';
        } else {
          document.getElementById("msjeUser").innerHTML =
            "Hubo un error al editar el responsable.";
        }
      },
    });
  }
}

function deleteResponsableModal(id, name) {
  document.getElementById("catiddelete").value = id;
  document.getElementById("deleteModalLabel").innerHTML =
    "ELIMINAR RESPONSABLE " + name;
}

function deleteResponsable() {
  id = document.getElementById("catiddelete").value;
  $.ajax({
    url: "../../functions/responsables/deleteResponsable.php",
    method: "GET",
    data: {
      id: id,
    },
    success: function (data) {
      if (data == "S") {
        cerrarModal("deleteModal");
        document.getElementById("filaResponsable" + id).remove();
        getResponsables(currentPage);
      }
    },
  });
}

function cerrarModal(modalId) {
  $(`#${modalId}`).modal("hide");
  let modal = document.getElementById(modalId);
  modal.style.display = "none";
  document.body.classList.remove("modal-open");
  let modalBackdrop = document.querySelectorAll(".modal-backdrop");
  if (modalBackdrop[0]) {
    modalBackdrop[0].parentNode.removeChild(modalBackdrop[0]);
  }

  if (modalId === "addModal") {
    limpiarFormulario("addResponsableForm");
  } else if (modalId === "editModal") {
    limpiarFormulario("editResponsableForm");
  }
}

function limpiarFormulario(formId) {
  const form = document.getElementById(formId);
  form.reset();
  $(form).find("input").removeClass("is-invalid is-valid");
  $(form).find("textarea").removeClass("is-invalid is-valid");
}

$(document).ready(function () {
  $("#addModal").on(
    "click",
    'button[type="button"].btn-primary',
    function (event) {
      if (validateForm()) {
        agregarResponsable();
      }
    }
  );

  // Función de validación
  function validateForm() {
    let isValid = true;
    $("#addResponsableForm input").each(function () {
      const $this = $(this);
      const value = $this.val();
      const pattern = $this.attr("pattern");

      if ($this.prop("required") && !value) {
        $this.addClass("is-invalid");
        isValid = false;
      } else if (pattern) {
        const regex = new RegExp(pattern);
        if (!regex.test(value)) {
          $this.addClass("is-invalid");
          isValid = false;
        } else {
          $this.removeClass("is-invalid").addClass("is-valid");
        }
      } else {
        $this.removeClass("is-invalid").addClass("is-valid");
      }
    });

    return isValid;
  }

  $("#addResponsableForm input").on("input", function () {
    $(this).removeClass("is-invalid");
  });

  $("#editModal").on(
    "click",
    'button[type="button"].btn-primary',
    function (event) {
      if (validateEditForm()) {
        editarResponsableSave();
      }
    }
  );

  function validateEditForm() {
    let isValid = true;
    $("#editModal input").each(function () {
      const $this = $(this);
      const value = $this.val();
      const pattern = $this.attr("pattern");

      if ($this.prop("required") && !value) {
        $this.addClass("is-invalid");
        isValid = false;
      } else if (pattern) {
        const regex = new RegExp(pattern);
        if (!regex.test(value)) {
          $this.addClass("is-invalid");
          isValid = false;
        } else {
          $this.removeClass("is-invalid").addClass("is-valid");
        }
      } else {
        $this.removeClass("is-invalid").addClass("is-valid");
      }
    });

    return isValid; // Retornar el estado de validez del formulario
  }

  $("#editModal input").on("input", function () {
    $(this).removeClass("is-invalid");
  });
});
