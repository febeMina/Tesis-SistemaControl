<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <!-- Mensajes de éxito, error o advertencia -->
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
    <?php if (session()->has('inactive')): ?>
        <div class="alert alert-warning">
            <?= session('inactive') ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #4CAF50; border-radius: 10px;">
                    <h4 class="header-title text-center">Reporte de Familias Beneficiadas</h4>
                </div>
                
                <div class="card-body" style="background-color: #FFFFFF; padding: 20px;">
                    <!-- Formulario de filtro por fecha -->
                    <form method="get" action="<?= base_url('public/reporte_familias/filtrar') ?>" class="mb-4">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="fecha_inicio">Fecha de Inicio</label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                            </div>
                            <div class="col-md-5">
                                <label for="fecha_fin">Fecha de Fin</label>
                                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            <a href="<?= site_url('reportes/familias') ?>" class="btn btn-secondary ms-2">Limpiar</a>
                        </div>
                    </form>

                    <!-- Mostrar tabla solo si hay registros y se han filtrado -->
                    <?php if (isset($registros) && !empty($registros)): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Familias Beneficiadas</th>
                                    <th>Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registros as $registro): ?>
                                    <tr>
                                        <td><?= esc($registro->fecha) ?></td>
                                        <td><?= esc($registro->familiasBeneficiadas) ?></td>
                                        <td>
                                            <button class="btn btn-info" onclick="mostrarDetalles(<?= esc($registro->idRegistroDiario) ?>)">Ver Detalles</button>
                                            <button class="btn btn-primary" onclick="location.href='<?= base_url('public/reporte_familias/generarReporte') ?>?idRegistroDiario=<?= esc($registro->idRegistroDiario) ?>'"> <i class="fas fa-print"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <!-- Mensaje que indica que no hay registros si no hay resultados -->
                        <div class="alert alert-warning" role="alert">
                            Por favor, selecciona las fechas y presiona "Filtrar" para ver los registros.
                        </div>
                    <?php endif; ?>

                   <!-- Enlaces de paginación -->
                    <div class="d-flex justify-content-center">
                        <?= $pager->links('group1', 'bootstrap_pagination') ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="detallesModal" tabindex="-1" role="dialog" aria-labelledby="detallesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detallesModalLabel">Detalles del Registro Diario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="detallesContenido"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
function mostrarDetalles(idRegistroDiario) {
    fetch(`<?= base_url('public/registro-diario/show') ?>/${idRegistroDiario}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data) {
            document.getElementById('detallesModalLabel').innerText = `Detalles del Registro Diario - ${data.fecha}`;
            document.getElementById('detallesContenido').innerHTML = `
                <h5>Familias Beneficiadas: ${data.familiasBeneficiadas}</h5>
                <table class="table-responsive">
                    <thead>
                        <tr>
                            <th>Grado</th>
                            <th>Docente</th>
                            <th>Cantidad de Niños</th>
                            <th>Cantidad de Niñas</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.detalles.map(detalle => `
                            <tr>
                                <td>${detalle.nombre_grado}</td>
                                <td>${detalle.nombre_docente}</td>
                                <td>${detalle.cantidadNinos}</td>
                                <td>${detalle.cantidadNinas}</td>
                                <td>${detalle.total}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            $('#detallesModal').modal('show');
        } else {
            console.error('No se encontró el registro.');
        }
    })
    .catch(error => console.error('Error'));
}
</script>

<?= $this->endSection() ?>
