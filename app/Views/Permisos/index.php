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
                    <!-- Formulario de filtros -->
                    <form action="<?= site_url('permiso_magisterial/index') ?>" method="get">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" name="nombre_completo" class="form-control" placeholder="Nombre del Maestro">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="nip" class="form-control" placeholder="NIP">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="fecha_solicitud" class="form-control" placeholder="Fecha de Solicitud">
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                                <a href="<?= site_url('permiso_magisterial/index') ?>" class="btn btn-secondary mt-3 ms-2">Limpiar</a>
                            </div>
                        </div>
                    </form>
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
