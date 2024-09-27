<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <a href="<?= site_url('solicitudproductos') ?>" class="btn btn-secondary">
        <i class="mdi mdi-arrow-left"></i> Solicitudes de productos
    </a>
    <hr>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="border-radius: 15px; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h4 class="header-title text-center">Ver - N° de ingreso: <?= $productoIngreso['idProductoIngreso'] ?></h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Información de la solicitud -->
                    <div class="mb-4">
                        <h5 style="color: black;">Información del ingreso</h5>
                        <form action="<?= site_url('solicitudproductos/update/' . $productoIngreso['idProductoIngreso']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="fechaIngreso" style="color: #000;">Fecha de ingreso</label>
                                        <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" value="<?= $productoIngreso['fechaIngreso'] ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="responsableEntrega" style="color: #000;">Responsable de entrega</label>
                                        <input type="text" class="form-control" id="responsableEntrega" name="responsableEntrega" value="<?= $productoIngreso['responsableEntrega'] ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="responsableRecibe" style="color: #000;">Responsable de recepción</label>
                                        <input type="text" class="form-control" id="responsableRecibe" name="responsableRecibe" value="<?= $productoIngreso['responsableRecibe'] ?>" disabled>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tabla de detalles -->
                    <div class="mb-4">
                        <h5 style="color: black;">
                            <?php 
                                if($productoIngreso['estado'] == "Anulado") {
                                    echo "Lotes de productos que se anularon (no se ingresaron)";
                                } else {
                                    echo "Lotes de productos que se ingresaron";
                                }
                            ?>
                        </h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Código de lote</th>
                                    <th>Fechas</th>
                                    <th>Unidades de caja</th>
                                    <th>Unidades por caja</th>
                                    <th>Total ingreso<br>(unidades)</th>
                                </tr>
                            </thead>
                            <tbody id="detallesTableBody">
                                <?php
                                    $n = 0;
                                    foreach ($detalles as $detalle) : 
                                        $n++;
                                ?>
                                    <tr>
                                        <td><?= $n ?></td>
                                        <td><?= $detalle['codigoLote'] ?></td>
                                        <td>Ingreso: <?= date("d/m/Y", strtotime($detalle['fechaIngreso'])) ?><br>Vencimiento: <?= date("d/m/Y", strtotime($detalle['fechaVencimiento'])) ?></td>
                                        <td class="text-right"><?= $detalle['existenciaCaja'] ?><br><?= $detalle['udmCaja'] ?></td>
                                        <td class="text-right"><?= $detalle['existenciaIndividual'] ?><br><?= $detalle['udmIndividual'] ?></td>
                                        <td class="text-right"><?= $detalle['existenciaTotal'] ?> u</td>
                                    </tr>
                                <?php 
                                    endforeach; 
                                    if($n == 0) {
                                        echo '<tr><td colspan="6" class="text-center">Los lotes de productos fueron eliminados</td></tr>';
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
