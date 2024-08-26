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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Registros Diarios - Familias beneficiadas con alimentos</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <div class="mb-3 text-end">
                        <a href="<?= base_url('public/registro-diario/create') ?>" class="btn btn-primary">Nuevo Registro Diario</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Familias Beneficiadas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registros as $registro): ?>
                                    <tr>
                                        <td><?= esc($registro['fecha']) ?></td>
                                        <td><?= esc($registro['familiasBeneficiadas']) ?></td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-id="<?= esc($registro['idRegistroDiario']) ?>" data-toggle="modal" data-target="#modalDetalles">
                                                Ver Detalles
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-4">
                            <?= $pager->links() ?>
                        </div>
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
                <h5 class="modal-title" id="modalDetallesLabel">Detalles del Registro Diario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #f0f0f0;">
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

<!-- Script para cargar los detalles en el modal -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#modalDetalles').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var idRegistro = button.data('id');

        var modal = $(this);
        $.ajax({
            url: '<?= site_url('registro-diario/getDetails') ?>/' + idRegistro,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.error) {
                    modal.find('#modalDetallesBody').html('<p>' + data.error + '</p>');
                } else {
                    var registro = data.registro;
                    var detalles = data.detalles;
                    var totalFamiliasBeneficiadas = data.totalFamiliasBeneficiadas;
        
                    var detallesHtml = '<p><strong style="color: #6c757d;">Fecha:</strong> <span style="color: #000;">' + registro.fecha + '</span></p>'; // Color gris para la etiqueta, negro para el valor
                    detallesHtml += '<p><strong style="color: #6c757d;">Familias Beneficiadas:</strong> <span style="color: #000;">' + totalFamiliasBeneficiadas + '</span></p>'; // Color gris para la etiqueta, negro para el valor
                    detallesHtml += '<h4 style="color: #000;">Detalles de Asistencia</h4>';
                    detallesHtml += '<table class="table">';
                    detallesHtml += '<thead><tr><th>Grado</th><th>Niños</th><th>Niñas</th><th>Total</th></tr></thead>';
                    detallesHtml += '<tbody>';
                    detalles.forEach(function (detalle) {
                        detallesHtml += '<tr>';
                        detallesHtml += '<td>' + detalle.nombre_grado + '</td>';
                        detallesHtml += '<td>' + detalle.cantidad_niños + '</td>';
                        detallesHtml += '<td>' + detalle.cantidad_niñas + '</td>';
                        detallesHtml += '<td>' + detalle.Total + '</td>';
                        detallesHtml += '</tr>';
                    });
                    detallesHtml += '</tbody></table>';
        
                    modal.find('#modalDetallesBody').html(detallesHtml);
                }
            },
            error: function () {
                modal.find('#modalDetallesBody').html('<p>Hubo un error al cargar los detalles.</p>');
            }
        });

    });
});
</script>

<?= $this->endSection() ?>
