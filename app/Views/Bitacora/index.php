<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Bitácora de Acciones</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <!-- Tabla de bitacora -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                    <th>Tabla</th>
                                    <th>Fecha</th>
                                    <th>Campo</th>
                                    <th>Valor Anterior</th>
                                    <th>Valor Nuevo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bitacora as $entry) : ?>
                                    <tr>
                                        <td><?= $entry['usuario']; ?></td>
                                        <td><?= $entry['accion']; ?></td>
                                        <td><?= $entry['nombreTabla']; ?></td>
                                        <td><?= $entry['fecha']; ?></td>
                                        <td><?= $entry['campo']; ?></td>
                                        <td><?= $entry['valorAnterior']; ?></td>
                                        <td><?= $entry['valorNuevo']; ?></td>
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
<?= $this->endSection() ?>