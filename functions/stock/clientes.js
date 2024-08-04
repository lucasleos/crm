document.addEventListener("DOMContentLoaded", function () {
  getClientes();
});

function getClientes() {
  var search = document.getElementById("searchClientes").value;
  $.ajax({
    url: "../../functions/stock/getAllClientes.php",
    method: "GET",
    data: {
      search: search,
    },
    success: function (data) {
      if (data != "") {
        $("#allClientes").html(data);
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

function deleteCategoriaModal(id, name) {
  document.getElementById("catiddelete").value = id;
  document.getElementById("deleteModalLabel").innerHTML =
    "ELIMINAR CATEGORÍA " + name;
}

function deleteCategoria() {
  id = document.getElementById("catiddelete").value;
  console.log(id);
  $.ajax({
    url: "../../functions/stock/deleteCategoria.php",
    method: "GET",
    data: {
      id: id,
    },
    success: function (data) {
      if (data == "S") {
        cerrarModal("deleteModal");
        document.getElementById("filaCat" + id).remove();
        getCategorias();
      }
    },
  });
}

function agregarCliente() {
  const razonSocial = document.getElementById("razonSocial").value;
  const correo = document.getElementById("correo").value;
  const localidad = document.getElementById("localidad").value;
  const cuit = document.getElementById("cuit").value;
  const telefono = document.getElementById("telefono").value;
  const codigoPostal = document.getElementById("codigoPostal").value;
  const provincia = document.getElementById("provincia").value;

  if (razonSocial != "" && correo != "" && cuit != "") {
    $.ajax({
      url: "../../functions/stock/agregarCliente.php",
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
        if (data == "S") {
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

  if (
    razonSocial &&
    correo &&
    localidad &&
    cuit &&
    telefono &&
    codigoPostal &&
    provincia &&
    id
  ) {
    $.ajax({
      url: "../../functions/stock/editarCliente.php",
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
          getClientes();
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

function cerrarModal(modal) {
  var modal = document.getElementById(modal);
  modal.style.display = "none";
  document.body.classList.remove("modal-open");
  var modalBackdrop = document.getElementsByClassName("modal-backdrop");
  if (modalBackdrop[0]) {
    modalBackdrop[0].parentNode.removeChild(modalBackdrop[0]);
  }
}

function seleccionarCliente(cliente) {
  localStorage.setItem("clienteSeleccionado", JSON.stringify(cliente));
  window.location.href = "/crm/modules/presupuestos/presupuestos.php";
}
