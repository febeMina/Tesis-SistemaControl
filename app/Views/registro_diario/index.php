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
                <div class="card-header text-white" style="background-color: #4CAF50; border-radius: 10px;">
                    <h4 class="header-title text-center">Lista de registros diarios</h4>
                </div>
                
                <div class="card-body" style="background-color: #FFFFFF; padding: 20px;">
                     <!-- Botón para crear nuevo tipo de documento -->
                     <a href="<?= site_url('/registro-diario/create') ?>" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Agregar
                    </a>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Familias beneficiadas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registros as $registro): ?>
                                <tr>
                                    <td><?= esc($registro->fecha) ?></td>
                                    <td><?= esc($registro->familiasBeneficiadas) ?></td>
                                    <td>
                                        <button class="btn btn-info" onclick="mostrarRegistro(<?= esc($registro->idRegistroDiario) ?>)">Ver detalles</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        <?= $pager->links('group1', 'bootstrap_pagination') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="registroModal" tabindex="-1" role="dialog" aria-labelledby="registroModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registroModalLabel">Detalles del registro diario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="registroDetalles"></div>
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
function mostrarRegistro(idRegistroDiario) {
    fetch(`<?= base_url('public/registro-diario/show') ?>/${idRegistroDiario}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data) {
            console.log(data); // Log para verificar la estructura de los datos
            document.getElementById('registroModalLabel').innerText = `Detalles del Registro Diario - ${data.fecha}`;
            document.getElementById('registroDetalles').innerHTML = `
                <h5>Familias Beneficiadas: ${data.familiasBeneficiadas}</h5>
                <table class="table-responsive">
                    <thead>
                        <tr>
                            <th>Grado</th>
                            <th>Docente</th>
                            <th>Cantidad de niños</th>
                            <th>Cantidad de niñas</th>
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
            $('#registroModal').modal('show'); // Asegúrate de que el ID es correcto
        } else {
            console.error('No se encontró el registro.');
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<?= $this->endSection() ?>
