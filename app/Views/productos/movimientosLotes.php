<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<!-- Contenido de la página -->
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Movimientos del producto: <?= $descripcionProducto ?><br>Lote del producto: <?= $codigoLote ?></h4>
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
                                    <th>Movimiento</th>
                                    <th>Fecha del movimiento</th>
                                    <th>Existencia antes</th>
                                    <th>Existencia movimiento</th>
                                    <th>Existencia después</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $n = 0;
                                    foreach ($movimientosLotes as $index => $movimiento): 
                                        $n++;
                                ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>Tipo: <?= $movimiento->tipoMovimiento . '<br>Descripción: ' . $movimiento->descripcionMovimiento ?></td>
                                    <td><?= date("d/m/Y", strtotime($movimiento->fechaMovimiento)) ?></td>
                                    <td class="text-right"><?= $movimiento->existenciaTotalAntes ?> u</td>
                                    <td class="text-right"><?= $movimiento->existenciaTotalMovimiento ?> u</td>
                                    <td class="text-right"><?= $movimiento->existenciaTotalDespues ?> u</td>
                                </tr>
                                <?php 
                                    endforeach; 

                                    if($n == 0) {
                                        echo '<tr><td colspan="6" class="text-center">No se encontraron registros...</td></tr>';
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