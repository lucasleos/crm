document.addEventListener("DOMContentLoaded", function () {
  getClientes(currentPage);
});

let currentPage = 1;
function getClientes(page = 1) {
  currentPage = page;
  const search = document.getElementById("searchClientes").value;
  $.ajax({
    url: "../../functions/clientes/getAllClientes.php",
    method: "GET",
    dataType: "json",
    data: {
      search: search,
      page: page,
    },
    success: function (data) {
      if (data) {
        $("#allClientes").html(data.rows);
        $("#paginationContainer").html(data.pagination);
      }
    },
  });
}

(function () {
  "use strict";
  window.addEventListener(
    "load",
    function () {
      let form = document.getElementById("addClientForm");
      form.addEventListener(
        "submit",
        function (event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          } else {
            event.preventDefault();
            agregarCliente();
          }
          form.classList.add("was-validated");
        },
        false
      );
    },
    false
  );
})();

(function () {
  "use strict";
  window.addEventListener(
    "load",
    function () {
      let form = document.getElementById("editClientForm");
      form.addEventListener(
        "submit",
        function (event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          } else {
            event.preventDefault();
            editarClienteSave();
          }
          form.classList.add("was-validated");
        },
        false
      );
    },
    false
  );
})();

function agregarCliente() {
  const razonSocial = document.getElementById("razonSocial").value;
  const correo = document.getElementById("correo").value;
  const localidad = document.getElementById("localidad").value;
  const cuit = document.getElementById("cuit").value;
  const telefono = document.getElementById("telefono").value;
  const codigoPostal = document.getElementById("codigoPostal").value;
  const provincia = document.getElementById("provincia").value;

  $.ajax({
    url: "../../functions/clientes/agregarCliente.php",
    method: "GET",
    data: {
      razonSocial: razonSocial,
      correo: correo,
      localidad: localidad,
      cuit: cuit,
      telefono: telefono,
      codigoPostal: codigoPostal,
      provincia: provincia,
    },
    success: function (data) {
      if (data === "S") {
        cerrarModal("addModal");
        getClientes();
        document.getElementById("msjeUser").innerHTML =
          '<i class="mdi mdi-check"></i> Cliente agregado con éxito.';
      } else {
        document.getElementById("msjeUser").innerHTML =
          "Hubo un error al agregar el cliente.";
      }
    },
  });
}

function changeModal(id) {
  document.getElementById("newpassid").value = id;
}

function editarCliente(cliente) {
  document.getElementById("razonSocialEdit").value = cliente.razonSocial;
  document.getElementById("correoEdit").value = cliente.correo;
  document.getElementById("localidadEdit").value = cliente.localidad;
  document.getElementById("cuitEdit").value = cliente.cuit;
  document.getElementById("telefonoEdit").value = cliente.telefono;
  document.getElementById("codigoPostalEdit").value = cliente.codigoPostal;
  document.getElementById("provinciaEdit").value = cliente.provincia;
  document.getElementById("clienteIdEdit").value = cliente.id;
}

function deleteClienteModal(id, name) {
  document.getElementById("catiddelete").value = id;
  document.getElementById("deleteModalLabel").innerHTML =
    "ELIMINAR CLIENTE " + name;
}

function deleteCliente() {
  id = document.getElementById("catiddelete").value;
  $.ajax({
    url: "../../functions/clientes/deleteCliente.php",
    method: "GET",
    data: {
      id: id,
    },
    success: function (data) {
      if (data == "S") {
        cerrarModal("deleteModal");
        document.getElementById("filaCliente" + id).remove();
        getClientes(currentPage);
      }
    },
  });
}

function editarClienteSave() {
  const razonSocial = document.getElementById("razonSocialEdit").value;
  const correo = document.getElementById("correoEdit").value;
  const localidad = document.getElementById("localidadEdit").value;
  const cuit = document.getElementById("cuitEdit").value;
  const telefono = document.getElementById("telefonoEdit").value;
  const codigoPostal = document.getElementById("codigoPostalEdit").value;
  const provincia = document.getElementById("provinciaEdit").value;
  const id = document.getElementById("clienteIdEdit").value;

  if (razonSocial && localidad && cuit && codigoPostal && provincia && id) {
    $.ajax({
      url: "../../functions/clientes/editarCliente.php",
      method: "GET",
      data: {
        id: id,
        razonSocial: razonSocial,
        correo: correo,
        localidad: localidad,
        cuit: cuit,
        telefono: telefono,
        codigoPostal: codigoPostal,
        provincia: provincia,
      },
      success: function (data) {
        if (data === "S") {
          cerrarModal("editModal");
          getClientes(currentPage);
          document.getElementById("msjeUser").innerHTML =
            '<i class="mdi mdi-check"></i> Cliente editado con éxito.';
        } else {
          document.getElementById("msjeUser").innerHTML =
            "Hubo un error al editar el cliente.";
        }
      },
    });
  }
}

function cerrarModal(modalId) {
  $(`#${modalId}`).modal("hide");
  let modal = document.getElementById(modalId);
  modal.style.display = "none";
  document.body.classList.remove("modal-open");
  const modalBackdrop = document.querySelectorAll(".modal-backdrop");
  if (modalBackdrop) {
    modalBackdrop.forEach((backdrop) => backdrop.remove());
  }
  limpiarCampos(modalId);
}
function limpiarCampos(modalId) {
  const form =
    modalId === "addModal"
      ? document.getElementById("addClientForm")
      : document.getElementById("editClientForm");

  form.reset();
  form.classList.remove("was-validated");
}

function seleccionarCliente(clienteId) {
  window.location.href =
    "/crm/modules/presupuestos/presupuestos.php?clienteId=" +
    encodeURIComponent(clienteId);
}
