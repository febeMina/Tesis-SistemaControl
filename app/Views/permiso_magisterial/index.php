<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Saldo de Permiso Magisterial</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Tabla de saldos de permiso -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>Nombre del Docente</th>
                                    <th>Fecha de Solicitud</th>
                                    <th>Tipo de Permiso</th>
                                    <th>Días Ocupados</th>
                                    <th>Días Disponibles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($saldos_docentes as $saldo): ?>
                                    <?php foreach ($saldo['detalle_saldos_permiso'] as $detalle_saldo): ?>
                                        <tr>
                                            <td><?= $saldo['nip'] ?></td>
                                            <td><?= $saldo['nombre_completo'] ?></td>
                                            <td><?= $saldo['fecha_solicitud'] ?></td>
                                            <td><?= $detalle_saldo['tipo_permiso'] ?></td>
                                            <td>0</td> <!-- Ajustar estos valores -->
                                            <td><?= $detalle_saldo['dias_disponibles'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
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
