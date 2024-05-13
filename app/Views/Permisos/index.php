<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Permiso Magisterial</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <!-- Botón para agregar un nuevo tipo de permiso -->
                    <div class="mb-3">
                        <a href="<?= site_url('permiso_magisterial/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Agregar <!-- Icono de Material Design Icons -->
                        </a>
                    </div>
                    <!-- Tabla de saldos de permiso -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>Nombre del Maestro</th>
                                    <th>Fecha de Solicitud</th>
                                    <?php foreach ($tipos_permisos as $tipo_permiso): ?>
                                        <th><?= $tipo_permiso['nombre'] ?> (<?= $tipo_permiso['cantidad_dias'] ?>)</th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($saldos_docentes as $saldo): ?>
                                    <tr>
                                        <td><?= $saldo['nip'] ?></td>
                                        <td><?= $saldo['nombre_completo'] ?></td>
                                        <td><?= $saldo['fecha_solicitud'] ?></td>
                                        <?php foreach ($saldo['detalle_saldos_permiso'] as $detalle_saldo): ?>
                                            <td>
                                                <!-- Mostrar los días ocupados y disponibles con coloración -->
                                                <div>
                                                    <strong>Días Ocupados:</strong> <?= $detalle_saldo['dias_ocupados'] ?>
                                                </div>
                                                <div>
                                                    <strong>Días Disponibles:</strong> <?= $detalle_saldo['dias_disponibles'] ?>
                                                </div>
                                            </td>
                                        <?php endforeach; ?>
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
