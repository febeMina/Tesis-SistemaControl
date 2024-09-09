<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<!-- Contenido de la página -->
<div class="container mt-3">
    <a href="<?= site_url('productos') ?>" class="btn btn-secondary">
        <i class="mdi mdi-arrow-left"></i> Inventario general
    </a>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Lotes del producto: <?= $descripcionProducto ?></h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <!-- Tabla de productos -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Código de lote</th>
                                    <th>Fechas</th>
                                    <th>Unidades de caja</th>
                                    <th>Unidades por caja</th>
                                    <th>Existencia actual<br>(unidades)</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $n = 0;
                                    foreach ($productosLotes as $index => $lote): 
                                        $n++;
                                ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $lote->codigoLote ?></td> <!-- Nombre del producto -->
                                    <td>Ingreso: <?= date("d/m/Y", strtotime($lote->fechaIngreso)) ?><br>Vencimiento: <?= date("d/m/Y", strtotime($lote->fechaVencimiento)) ?></td>
                                    <td class="text-right"><?= $lote->existenciaCaja ?><br><?= $lote->udmCaja ?></td>
                                    <td class="text-right"><?= $lote->existenciaIndividual ?><br><?= $lote->udmIndividual ?></td>
                                    <td class="text-right"><?= $lote->existenciaTotal ?> u</td>
                                    <td>
                                        <a href="<?= site_url('productos_lotes/movimientos/' . $lote->idProductoLote) ?>" class="btn btn-edit">
                                            <i class="mdi mdi-account-clock"></i> Movimientos <!-- Icono de Material Design Icons -->
                                        </a>
                                        <a href="<?= site_url('productos_lotes/edit/' . $lote->idProductoLote) ?>" class="btn btn-edit">
                                            <i class="mdi mdi-pencil"></i> <!-- Icono de Material Design Icons -->
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    endforeach; 
                                    if($n == 0) {
                                        echo '<tr><td colspan="7" class="text-center">No se encontraron registros...</td></tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>