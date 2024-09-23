<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">

    <!-- Mensajes de alerta -->
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <?= session('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <?php if (session()->has('inactive')): ?>
        <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
            <?= session('inactive') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <?= session('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Personal magisterial</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Formulario de filtros -->
                    <form action="<?= site_url('maestros/index') ?>" method="get">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <input type="text" name="nombreCompleto" class="form-control" placeholder="Nombre Completo">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="nip" class="form-control" placeholder="NIP">
                            </div>
                            <div class="col-md-4">
                                <select name="estado" class="form-control">
                                    <option value="">Estado</option>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="tipo" class="form-control">
                                    <option value="">Tipo</option>
                                    <option value="Docente">Docente</option>
                                    <option value="Administrativo">Administrativo</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            <a href="<?= site_url('maestros/index') ?>" class="btn btn-secondary ms-2">Limpiar</a>
                            <a href="<?= site_url('maestros/inicializarSaldosPermisos'); ?>" class="btn btn-primary">Inicializar Saldos de Permisos</a>
                            <a href="<?= site_url('maestros/create') ?>" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Agregar
                            </a>
                        </div>
                    </form>
                    
                    <!-- Tabla de maestros -->
                    <div class="table-responsive mt-3">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre completo</th>
                                    <th>NIP</th>
                                    <th>Escalafón</th>
                                    <th>Fecha de ingreso</th>
                                    <th>Estado</th>
                                    <th>Tipo</th>
                                    <th>Cargo</th>
                                    <th>Grado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="maestrosTableBody">
                                <?php if (isset($maestros) && !empty($maestros)): ?>
                                    <?php foreach ($maestros as $maestro) : ?>
                                        <?php if ($maestro['estado'] !== 'Eliminado') : ?>
                                            <tr data-id="<?= esc($maestro['idDocente']) ?>">
                                                <td><?= esc($maestro['nombreCompleto']) ?></td>
                                                <td><?= esc($maestro['nip']) ?></td>
                                                <td><?= esc($maestro['escalafon']) ?></td>
                                                <td><?= esc($maestro['fechaIngreso']) ?></td>
                                                <td><?= esc($maestro['estado']) ?></td>
                                                <td><?= esc($maestro['tipo']) ?></td>
                                                <td><?= esc($maestro['cargo']) ?></td>
                                                <td>
                                                    <?php if ($maestro['tipo'] === 'Docente' && isset($maestro['grado'])) : ?>
                                                        <?= esc($maestro['grado']) ?>
                                                    <?php else : ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                            <a href="<?= site_url('maestros/edit/' . esc($maestro['idDocente'])) ?>" class="btn btn-edit">
                                                                <i class="mdi mdi-pencil"></i>
                                                            </a>
                                                            <a href="javascript:void(0);" class="btn btn-delete" onclick="showDeleteModal(<?= esc($maestro['idDocente']) ?>);">
                                                                <i class="mdi mdi-delete"></i>
                                                            </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No se encontraron registros</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Está seguro de que desea eliminar este maestro?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="confirmDeleteButton">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!-- Cargar jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script JavaScript para confirmación y redirección -->
<script>
let deleteId = null;

function showDeleteModal(id) {
    deleteId = id;
    $('#deleteModal').modal('show');
}

$('#confirmDeleteButton').click(function() {
    if (deleteId !== null) {
        $.ajax({
            url: '<?= site_url('maestros/delete') ?>',
            type: 'POST',
            data: { id: deleteId },
            success: function(response) {
                if (response.success) {
                    // Mostrar mensaje de éxito
                    var successAlert = $('<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">' +
                      response.message +
                      '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                      '<span aria-hidden="true">&times;</span>' +
                      '</button>' +
                      '</div>').prependTo('.container').fadeIn();
                      
                    setTimeout(function() {
                        successAlert.alert('close');
                    }, 3000);
                    
                    // Eliminar la fila de la tabla
                    $('tr[data-id="' + deleteId + '"]').remove();
                } else {
                    // Mostrar mensaje de error
                    var errorAlert = $('<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">' +
                      response.message +
                      '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                      '<span aria-hidden="true">&times;</span>' +
                      '</button>' +
                      '</div>').prependTo('.container').fadeIn();
                      
                    setTimeout(function() {
                        errorAlert.alert('close');
                    }, 3000);
                }
                // Ocultar el modal
                $('#deleteModal').modal('hide');
                deleteId = null;
            },
            error: function() {
                alert('Error al eliminar el registro.');
                $('#deleteModal').modal('hide');
                deleteId = null;
            }
        });
    }
});
</script>

<?= $this->endSection() ?>
