<?php
include('../../views/body/head.php');
?>

<main role="main" class="main-content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="row align-items-center mb-2">
          <div class="col">
            <h2 class="h5 page-title">Clientes</h2>
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
                <label class="">Busca y modifica todos los clientes.</label><br>
                <label class="mt-2 pb-2" id="msjeUser"></label>
                <div class="toolbar">
                  <form class="form">
                    <div class="form-row">
                      <div class="form-group col-auto">
                        <label for="search" class="sr-only">Search</label>
                        <input type="text" class="form-control" id="searchClientes" onkeyup="getClientes()" placeholder="Filtrar">
                      </div>
                      <div class="form-group col-auto">
                        <label class="btn btn-primary" data-toggle="modal" data-target="#addModal"><i class="mdi mdi-plus-circle-outline"></i> AGREGAR</label>
                      </div>
                    </div>
                  </form>
                </div>
                <table class="table table-borderless table-hover">
                  <thead>
                    <tr>
                      <th>CLIENTE</th>
                      <th>CORREO ELECTRÓNICO</th>
                      <th>TELÉFONO</th>
                      <th>LOCALIDAD</th>
                    </tr>
                  </thead>
                  <tbody id="allClientes"></tbody>
                </table>
                <div id="paginationContainer"></div>
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
        <h5 class="modal-title" id="editModalLabel">EDITAR CLIENTE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editClientForm" class="needs-validation" novalidate>
        <div class="modal-body row">
          <div class="col">
            <div class="form-group">
              <label for="razonSocialEdit" class="col-form-label">RAZÓN SOCIAL:</label>
              <input type="text" class="form-control" id="razonSocialEdit" required>
              <div class="invalid-feedback">
                Por favor, ingrese la razón social.
              </div>
            </div>
            <div class="form-group">
              <label for="correoEdit" class="col-form-label">CORREO ELECTRÓNICO:</label>
              <input type="email" class="form-control" id="correoEdit" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
              <div class="invalid-feedback">
                Por favor, ingrese un correo electrónico válido.
              </div>
            </div>
            <div class="form-group">
              <label for="localidadEdit" class="col-form-label">LOCALIDAD:</label>
              <input type="text" class="form-control" id="localidadEdit" required>
              <div class="invalid-feedback">
                Por favor, ingrese la localidad.
              </div>
            </div>
            <div class="form-group">
              <label for="provinciaEdit" class="col-form-label">PROVINCIA:</label>
              <input type="text" class="form-control" id="provinciaEdit" required>
              <div class="invalid-feedback">
                Por favor, ingrese la provincia.
              </div>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="cuitEdit" class="col-form-label">CUIT:</label>
              <input type="text" class="form-control" id="cuitEdit" required pattern="\d{11}">
              <div class="invalid-feedback">
                Por favor, ingrese un CUIT válido (11 dígitos).
              </div>
            </div>
            <div class="form-group">
              <label for="telefonoEdit" class="col-form-label">TELÉFONO:</label>
              <input type="text" class="form-control" id="telefonoEdit" pattern="\d{0,10,15}">
              <div class="invalid-feedback">
                Por favor, ingrese un número de teléfono válido.
              </div>
            </div>
            <div class="form-group">
              <label for="codigoPostalEdit" class="col-form-label">CÓDIGO POSTAL:</label>
              <input type="text" class="form-control" id="codigoPostalEdit" required pattern="\d{4,5}">
              <div class="invalid-feedback">
                Por favor, ingrese un código postal válido.
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" class="form-control" id="clienteIdEdit">
          <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">CANCELAR</button>
          <button type="submit" onclick="editarCliente()" class="btn mb-2 btn-primary">GUARDAR CAMBIOS</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirmación de Eliminación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" id="catiddelete">
        <p><strong>¿Está seguro de que desea eliminar este cliente?</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-danger" onclick="deleteCliente()">Eliminar Cliente</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="varyModalLabel">AGREGAR CLIENTE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="addClientForm" class="needs-validation" novalidate>
        <div class="modal-body row">
          <div class="col">
            <div class="form-group">
              <label for="razonSocial" class="col-form-label">RAZÓN SOCIAL:</label>
              <input type="text" class="form-control" id="razonSocial" required>
              <div class="invalid-feedback">
                Por favor, ingrese la razón social.
              </div>
            </div>
            <div class="form-group">
              <label for="correo" class="col-form-label">CORREO ELECTRÓNICO:</label>
              <input type="email" class="form-control" id="correo" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
              <div class="invalid-feedback">
                Por favor, ingrese un correo electrónico válido.
              </div>
            </div>
            <div class="form-group">
              <label for="localidad" class="col-form-label">LOCALIDAD:</label>
              <input type="text" class="form-control" id="localidad" required>
              <div class="invalid-feedback">
                Por favor, ingrese la localidad.
              </div>
            </div>
            <div class="form-group">
              <label for="provincia" class="col-form-label">PROVINCIA:</label>
              <input type="text" class="form-control" id="provincia" required>
              <div class="invalid-feedback">
                Por favor, ingrese la provincia.
              </div>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="cuit" class="col-form-label">CUIT:</label>
              <input type="text" class="form-control" id="cuit" required pattern="\d{11}">
              <div class="invalid-feedback">
                Por favor, ingrese un CUIT válido (11 dígitos).
              </div>
            </div>
            <div class="form-group">
              <label for="telefono" class="col-form-label">TELÉFONO:</label>
              <input type="text" class="form-control" id="telefono" pattern="\d{10,15}">
              <div class="invalid-feedback">
                Por favor, ingrese un número de teléfono válido.
              </div>
            </div>
            <div class="form-group">
              <label for="codigoPostal" class="col-form-label">CÓDIGO POSTAL:</label>
              <input type="text" class="form-control" id="codigoPostal" required pattern="\d{4,5}">
              <div class="invalid-feedback">
                Por favor, ingrese un código postal válido.
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">CANCELAR</button>
          <button type="submit" class="btn mb-2 btn-primary">GUARDAR CAMBIOS</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
include('../../views/body/footer.php');
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../public/js/bootstrap.min.js"></script>
<script src="../../functions/clientes/clientes.js"></script>