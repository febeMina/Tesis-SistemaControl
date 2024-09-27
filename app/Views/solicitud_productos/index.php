<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center text-white">Ingreso de productos: Ingreso de lotes</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <!-- Botón para crear nueva solicitud -->
                    <div class="mb-3">
                        <a href="<?= site_url('solicitudproductos/create') ?>" class="btn btn-primary">
                        <i class="mdi mdi-plus">Agregar</i>
                        </a>
                    </div>

                    <!-- Tabla de solicitudes -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Fecha de ingreso</th>
                                    <th>Responsables</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $n = 0;
                                    foreach ($solicitudesIngreso as $solicitud): 
                                        $n++;
                                ?>
                                    <tr>
                                        <td>
                                            <?= $n ?><br>N° ingreso: <?= $solicitud['idProductoIngreso'] ?>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($solicitud['fechaIngreso'])) ?></td>
                                        <td>Entrega: <?= $solicitud['responsableEntrega'] ?><br>Recibe: <?= $solicitud['responsableRecibe'] ?></td>
                                        <td>
                                            <?php 
                                                $estadoSolicitud = "";
                                                if($solicitud['estado'] == "Pendiente") {
                                                    $estadoSolicitud = '<span class="badge badge-warning">Pendiente</span>';
                                                } else if($solicitud['estado'] == "Finalizado") {
                                                    $estadoSolicitud = '<span class="badge badge-success">Finalizado</span>';
                                                } else {
                                                    $estadoSolicitud = '<span class="badge badge-danger">Anulado</span>';
                                                }
                                                echo $estadoSolicitud;
                                            ?>                                            
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <?php 
                                                    if($solicitud['estado'] == "Pendiente") {
                                                ?>
                                                        <a href="<?= site_url('solicitudproductos/edit/' . $solicitud['idProductoIngreso'])?>" class="btn btn-edit">
                                                            <i class="mdi mdi-sync"></i> Continuar
                                                        </a>
                                                        <a href="<?= site_url('solicitudproductos/anular/' . $solicitud['idProductoIngreso'])?>" class="btn btn-delete" onclick="return confirm('¿Estás seguro de que desea anular este ingreso?')">
                                                            <i class="mdi mdi-cancel"></i> Anular
                                                        </a>
                                                <?php 
                                                    } else {
                                                ?>
                                                        <a href="<?= site_url('solicitudproductos/ver/' . $solicitud['idProductoIngreso'])?>" class="btn btn-edit">
                                                            <i class="mdi mdi-eye"></i> Ver ingreso
                                                        </a>
                                                <?php 
                                                    }
                                                ?>
                                            </div>
                                        </td>
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
