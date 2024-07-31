<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center text-white">Listado de Solicitudes de Productos</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">

                    <?php if (session()->get('success')): ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->get('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->get('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->get('error') ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <a href="<?= site_url('solicitudproductos/create') ?>" class="btn btn-success">Crear Nueva Solicitud</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha Solicitud</th>
                                    <th>Comida a Preparar</th>
                                    <th>Responsable Entrega</th>
                                    <th>Responsable Recibir</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($solicitudes as $solicitud): ?>
                                    <tr>
                                        <td><?= esc($solicitud['idSolicitudProductos']) ?></td>
                                        <td><?= esc($solicitud['Fecha_solicitud']) ?></td>
                                        <td><?= esc($solicitud['Comida_a_preparar']) ?></td>
                                        <td><?= esc($solicitud['responsable_entrega']) ?></td>
                                        <td><?= esc($solicitud['responsable_recibir']) ?></td>
                                        <td>
                                            <a href="<?= site_url('solicitudproductos/edit/' . esc($solicitud['idSolicitudProductos'])) ?>" class="btn btn-primary btn-sm btn-edit">
                                                Editar
                                            </a>
                                            <a href="<?= site_url('solicitudproductos/delete/' . esc($solicitud['idSolicitudProductos'])) ?>" class="btn btn-danger btn-sm btn-delete" onclick="return confirm('¿Estás seguro de que deseas eliminar esta solicitud?')">
                                                Eliminar
                                            </a>
                                            <button type="button" class="btn btn-info btn-sm" data-id="<?= esc($solicitud['idSolicitudProductos']) ?>" data-toggle="modal" data-target="#modalDetalles">
                                                Ver Detalles
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?= $pager->links() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalles -->
<div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallesLabel">Detalles de la Solicitud</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalDetallesBody">
                    <!-- Detalles serán cargados aquí por JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .card-header h3 {
        color: #ffffff;
    }
    .card-body {
        color: #000000;
    }
    .btn-primary {
        color: #ffffff;
    }
    .btn-success {
        color: #ffffff;
    }
    .btn-edit {
        color: #090066;
        border-radius: 5px;
        margin-right: 5px;
    }
    .btn-delete {
        color: red;
        border-radius: 5px;
    }
</style>

<!-- Script para cargar los detalles en el modal -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#modalDetalles').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var idSolicitud = button.data('id'); // Extract info from data-* attributes

        var modal = $(this);
        $.ajax({
            url: '<?= site_url('solicitudproductos/cargarModal') ?>/' + idSolicitud,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.detalles && data.detalles.length > 0) {
                    var detallesHtml = '<table class="table table-bordered">';
                    detallesHtml += '<thead><tr><th>Producto</th><th>Cantidad</th></tr></thead>';
                    detallesHtml += '<tbody>';
                    $.each(data.detalles, function (index, detalle) {
                        detallesHtml += '<tr><td>' + (detalle.nombre_producto || 'N/A') + '</td><td>' + (detalle.cantidad || 'N/A') + '</td></tr>';
                    });
                    detallesHtml += '</tbody></table>';
                    modal.find('#modalDetallesBody').html(detallesHtml);
                } else {
                    modal.find('#modalDetallesBody').html('<p>No se encontraron detalles.</p>');
                }
            },
            error: function () {
                modal.find('#modalDetallesBody').html('<p>Error al cargar los detalles.</p>');
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
