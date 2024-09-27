<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                <h4 class="header-title text-center">Asociados</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Mensaje de error -->
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario de filtros -->
                    <form method="get" action="<?= site_url('padres') ?>" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="nombre_completo" class="form-control" placeholder="Nombre Completo" value="<?= isset($filters['nombre_completo']) ? esc($filters['nombre_completo']) : '' ?>">
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" id="tipo_documento" name="tipo_documento">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($tiposDocumento as $tipo): ?>
                                        <option value="<?= $tipo['idTipoDocumento'] ?>" <?= isset($filters['tipo_documento']) && $filters['tipo_documento'] == $tipo['idTipoDocumento'] ? 'selected' : '' ?>>
                                            <?= esc($tipo['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="genero" class="form-control">
                                    <option value="">Género</option>
                                    <option value="M" <?= isset($filters['genero']) && $filters['genero'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                                    <option value="F" <?= isset($filters['genero']) && $filters['genero'] == 'F' ? 'selected' : '' ?>>Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="estado" class="form-control">
                                    <option value="">Estado</option>
                                    <option value="activo" <?= isset($filters['estado']) && $filters['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                                    <option value="inactivo" <?= isset($filters['estado']) && $filters['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            </div>
                        </div>    
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            <a href="<?= site_url('padres') ?>" class="btn btn-secondary ms-2">Limpiar</a>
                            <a href="<?= site_url('padres/create') ?>" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Agregar
                            </a>
                        </div>
                    </form>

                    <!-- Tabla de padres -->
                    <div class="table-responsive mt-3">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre completo</th>
                                    <th>Tipo de documento</th>
                                    <th>Número de documento</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Género</th>
                                    <th>Tipo de asociado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($padres as $padre): ?>
                                    <?php if ($padre['estado'] !== 'Eliminado'): ?>
                                        <tr>
                                            <td><?= esc($padre['nombreCompleto']); ?></td>
                                            <td><?= esc($padre['tipo_documento']); ?></td>
                                            <td><?= esc($padre['numeroDocumento']); ?></td>
                                            <td><?= esc($padre['telefono']) ?></td>
                                            <td><?= esc($padre['estado']) ?></td>
                                            <td><?= esc($padre['genero']) === 'M' ? 'Masculino' : 'Femenino' ?></td>
                                            <td><?= esc($padre['tipoAsociado']) === 'INTERNO' ? 'Interno' : 'Externo' ?></td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Acciones">
                                                    <a href="<?= site_url('padres/edit/' . $padre['idDatosResponsable']) ?>" class="btn btn-edit">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" class="btn btn-delete" onclick="showDeleteModal(<?= $padre['idDatosResponsable'] ?>)">
                                                        <i class="mdi mdi-delete"></i>
                                                    </a>
                                                    <button onclick="showAlumnosModal(<?= $padre['idDatosResponsable'] ?>)" class="btn btn-add-alumno">
                                                        <i class="mdi mdi-account-multiple"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar los alumnos asociados al padre -->
<div class="modal fade" id="alumnosModal" tabindex="-1" role="dialog" aria-labelledby="alumnosModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #090066; color: white;">
                <h5 class="modal-title" id="alumnosModalLabel">Alumnos Asociados al Padre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background-color: #f0f0f0;">
                <div id="alumnosContainer"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #090066; color: white;">
        <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Está seguro de que desea eliminar este asociado?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="confirmDeleteButton">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    let deleteId = null;

    function showAlumnosModal(padreId) {
        $.ajax({
            url: '<?= site_url('padres/getAlumnosAjax/') ?>' + padreId,
            type: 'GET',
            success: function(response) {
                $('#alumnosContainer').html(response);
                $('#alumnosModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function showDeleteModal(id) {
        deleteId = id;
        $('#deleteModal').modal('show');
    }

    $('#confirmDeleteButton').on('click', function() {
        if (deleteId) {
            window.location.href = '<?= site_url('padres/delete/') ?>' + deleteId;
        }
    });
</script>

<?= $this->endSection() ?>
