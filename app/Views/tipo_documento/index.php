<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">
            <?= session('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                <h4 class="header-title text-center">Tipos de documento</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <!-- Botón para crear nuevo tipo de documento -->
                    <a href="<?= site_url('/tipo-documento/create') ?>" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Agregar
                    </a>
                    
                    <!-- Tabla de tipos de documento -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Máscara</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tiposDocumento as $tipo): ?>
                                    <tr data-id="<?= esc($tipo['idTipoDocumento']) ?>">
                                        <td><?= esc($tipo['nombre']) ?></td>
                                        <td><?= esc($tipo['mascara']) ?></td>
                                        <td><?= esc($tipo['estado']) ?></td>
                                        <td>
                                            <a href="<?= base_url('public/tipo-documento/edit/'.$tipo['idTipoDocumento']) ?>" class="btn btn-edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="btn btn-delete" onclick="showDeleteModal(<?= esc($tipo['idTipoDocumento']) ?>);">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
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
                ¿Está seguro de que desea eliminar este tipo de documento?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmDeleteButton">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Cargar jQuery y Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script JavaScript para confirmación y eliminación -->
<script>
let deleteId = null;

function showDeleteModal(id) {
    deleteId = id;
    $('#deleteModal').modal('show');
}

$('#confirmDeleteButton').click(function() {
    if (deleteId !== null) {
        $.ajax({
    url: '<?= site_url('tipo-documento/delete/') ?>' + deleteId,
    type: 'POST',
    data: { id: deleteId },
    success: function(response) {
        console.log(response); // Añade esta línea para ver la respuesta en la consola
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
