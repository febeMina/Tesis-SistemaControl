<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center text-white">Consumo de productos: Salida de lotes</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <div class="mb-3">
                        <a href="<?= site_url('consumo/create') ?>" class="btn btn-primary">
                        <i class="mdi mdi-plus">Agregar</i>
                        </a>
                    </div>

                    <!-- Tabla de solicitudes -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Fecha de requisición</th>
                                    <th>Comida que se preparó</th>
                                    <th>Responsables</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $n = 0;
                                    foreach ($consumoSalida as $salida): 
                                        $n++;
                                ?>
                                    <tr>
                                        <td>
                                            <?= $n ?><br>N° requisición: <?= $salida['idProductoRequisicion'] ?>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($salida['fechaRequisicion'])) ?></td>
                                        <td><?= $salida['comidaPreparar'] ?></td>
                                        <td>Entrega: <?= $salida['responsableEntrega'] ?><br>Recibe: <?= $salida['responsableRecibe'] ?></td>
                                        <td>
                                            <?php 
                                                $estadoSalida = "";
                                                if($salida['estado'] == "Pendiente") {
                                                    $estadoSalida = '<span class="badge badge-warning">Pendiente</span>';
                                                } else if($salida['estado'] == "Finalizado") {
                                                    $estadoSalida = '<span class="badge badge-success">Finalizado</span>';
                                                } else {
                                                    $estadoSalida = '<span class="badge badge-danger">Anulado</span>';
                                                }
                                                echo $estadoSalida;
                                            ?>                                            
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <?php 
                                                    if($salida['estado'] == "Pendiente") {
                                                ?>
                                                        <a href="<?= site_url('consumo/edit/' . $salida['idProductoRequisicion'])?>" class="btn btn-edit">
                                                            <i class="mdi mdi-sync"></i> Continuar
                                                        </a>
                                                        <a href="<?= site_url('consumo/anular/' . $salida['idProductoRequisicion'])?>" class="btn btn-delete" onclick="return confirm('¿Estás seguro de que desea anular esta solicitud?')">
                                                            <i class="mdi mdi-cancel"></i> Anular
                                                        </a>
                                                <?php 
                                                    } else {
                                                ?>
                                                        <a href="<?= site_url('consumo/ver/' . $salida['idProductoRequisicion'])?>" class="btn btn-edit">
                                                            <i class="mdi mdi-eye"></i> Ver requisición
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
