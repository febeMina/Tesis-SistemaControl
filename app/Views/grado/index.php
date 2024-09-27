<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Listado de grados</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <a href="<?= site_url('grado/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Agregar grado
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
    <?php foreach ($grados as $grado): ?>
        <tr id="grado-<?= $grado['idGrado'] ?>" data-id="<?= $grado['idGrado'] ?>">
            <td><?= $grado['nombre'] ?></td>
            <td><?= $grado['descripcion'] ?></td>
            <td><?= $grado['estado'] == 'Activo' ? 'Activo' : 'Inactivo'; ?></td>
            <td>
                <div class="btn-group">
                    <a href="<?= site_url('grado/edit/' . $grado['idGrado']) ?>" class="btn btn-edit">
                        <i class="mdi mdi-pencil"></i>
                    </a>
                    <a href="javascript:void(0)" class="btn btn-delete" onclick="showDeleteModal(<?= $grado['idGrado'] ?>)">
                        <i class="mdi mdi-delete"></i>
                    </a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

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
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar este grado?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmDeleteButton">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteId = null;

function showDeleteModal(id) {
    console.log('Mostrar modal para eliminar el grado con ID:', id);  // Log del ID al abrir el modal
    deleteId = id;
    $('#deleteModal').modal('show');
}

$('#confirmDeleteButton').click(function() {
    console.log('Confirmar eliminación del grado con ID:', deleteId);  // Log para verificar el clic
    if (deleteId !== null) {
        $.ajax({
            url: '<?= site_url('grado/delete/') ?>' + deleteId,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);  // Log de la respuesta del servidor
                if (response.success) {
                    var successAlert = $('<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">' +
                      response.message +
                      '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                      '<span aria-hidden="true">&times;</span>' +
                      '</button>' +
                      '</div>').prependTo('.container').fadeIn();

                    setTimeout(function() {
                        successAlert.alert('close');
                    }, 3000);

                    $('#grado-' + deleteId).remove();
                } else {
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
                $('#deleteModal').modal('hide');
                deleteId = null;
            },
            error: function(xhr, status, error) {
                console.log('Error en la solicitud AJAX:', status, error);  // Log del error AJAX
                alert('Error al eliminar el registro.');
                $('#deleteModal').modal('hide');
                deleteId = null;
            }
        });
    }
});



</script>

<?= $this->endSection() ?>
