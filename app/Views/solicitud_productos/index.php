<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center">Listado de Requisiciones de Productos</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Tabla de requisiciones -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Comida a Preparar</th>
                                    <th>Responsable Entrega</th>
                                    <th>Responsable Recibir</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($solicitudes as $solicitud): ?>
                                    <tr>
                                        <td><?= esc($solicitud['Fecha_solicitud']) ?></td>
                                        <td><?= esc($solicitud['Comida_a_preparar']) ?></td>
                                        <td><?= esc($solicitud['responsable_entrega']) ?></td>
                                        <td><?= esc($solicitud['responsable_recibir']) ?></td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Acciones">
                                                <a href="<?= site_url('solicitudproductos/edit/' . $solicitud['idSolicitudProductos']) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <a href="<?= site_url('solicitudproductos/delete/' . $solicitud['idSolicitudProductos']) ?>" class="btn btn-delete" onclick="return confirm('¿Estás seguro de que deseas eliminar esta solicitud?')">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-3">
                        <a href="<?= site_url('solicitudproductos/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"> Nueva Requisición</i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
