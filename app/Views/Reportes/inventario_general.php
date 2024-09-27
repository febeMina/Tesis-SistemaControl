<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Inventario general</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Mensajes de sesión -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Botón para generar el reporte -->
                    <div class="text-right mb-3">
                        <a href="<?= base_url('public/reportes/generar_inventario') ?>" class="btn btn-primary">Generar PDF</a>
                    </div>

                    <!-- Tabla de inventario -->
                    <div class="table-responsive">
                        <table class="table"  style="color: #000;">
                            <thead >
                                <tr>
                                    <th>N°</th>
                                    <th>Producto</th>
                                    <th>Próximo vencimiento</th>
                                    <th>Existencia actual<br>(unidades)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $n = 1;
                                    foreach ($productos as $producto): 
                                ?>
                                <tr>
                                    <td><?= $n++; ?></td>
                                    <td><?= $producto->descripcionProducto; ?></td>
                                    <td><?= ($producto->fechaVencimientoProxima == "" ? "-" : date("d/m/Y", strtotime($producto->fechaVencimientoProxima))); ?></td>
                                    <td><?= number_format($producto->totalExistencia, 2, ".", ","); ?> u</td>
                                </tr>
                                <?php endforeach; ?>

                                <?php if ($n == 1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No se encontraron productos.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
