<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center text-white">Listado de Solicitudes de Productos</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    
                    <!-- Mensajes de sesión -->
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

                    <!-- Botón para crear nueva solicitud -->
                    <div class="mb-3">
                        <a href="<?= site_url('solicitudproductos/create') ?>" class="btn btn-primary">
                        <i class="mdi mdi-plus"> Agregar nueva solicitud</i>
                        </a>
                    </div>

                    <!-- Tabla de solicitudes -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
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
                                            <div class="btn-group" role="group">
                                                <a href="<?= site_url('solicitudproductos/edit/' . esc($solicitud['idSolicitudProductos'])) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <a href="<?= site_url('solicitudproductos/delete/' . esc($solicitud['idSolicitudProductos'])) ?>" class="btn btn-delete" onclick="return confirm('¿Estás seguro de que deseas eliminar esta solicitud?')">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                                <button type="button" class="btn btn-info" data-id="<?= esc($solicitud['idSolicitudProductos']) ?>" data-toggle="modal" data-target="#modalDetalles">
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación centrada -->
                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager->links() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalles -->
<div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #090066; color: white;">
                <h5 class="modal-title" id="modalDetallesLabel">Detalles de la Solicitud</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #f0f0f0;">
                <div id="modalDetallesBody"></div>
            </div>
        </div>
    </div>
</div>

<!-- Script para cargar los detalles en el modal -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#modalDetalles').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var idSolicitud = button.data('id');

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
