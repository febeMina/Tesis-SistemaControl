<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">

    <!-- Mensajes de alerta -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <?= session('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Listado de tipos de permiso</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="<?= site_url('tipo_permiso/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Agregar
                        </a>
                    </div>

                    <!-- Tabla de tipos de permiso -->
                    <div class="table-responsive mt-3">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cantidad de días</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tipos_permisos as $tipo_permiso) : ?>
                                    <tr data-id="<?= esc($tipo_permiso['idTipoPermiso']) ?>">
                                        <td><?= esc($tipo_permiso['nombre']); ?></td>
                                        <td><?= esc($tipo_permiso['cantidadDias']); ?></td>
                                        <td><?= esc($tipo_permiso['estado'] == 'Activo' ? 'Activo' : 'Inactivo'); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= site_url('tipo_permiso/edit/' . esc($tipo_permiso['idTipoPermiso'])) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-delete" onclick="showDeleteModal(<?= esc($tipo_permiso['idTipoPermiso']) ?>);">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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
                ¿Está seguro de que desea eliminar este tipo de permiso?
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
            url: '<?= site_url('tipo_permiso/delete/') ?>' + deleteId,
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
