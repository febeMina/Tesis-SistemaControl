<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <a href="<?= site_url('consumo') ?>" class="btn btn-secondary">
        <i class="mdi mdi-arrow-left"></i> Requisiciones de productos
    </a>
    <hr>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="border-radius: 15px; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h4 class="header-title text-center">Ver - N° de requisición: <?= $productoRequisicion['idProductoRequisicion'] ?></h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Información de la solicitud -->
                    <div class="mb-4">
                        <h5 style="color: black;">Información de la requisición</h5>
                        <form action="<?= site_url('consumo/update/' . $productoRequisicion['idProductoRequisicion']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="fechaRequisicion" style="color: #000;">Fecha de la requisición</label>
                                        <input type="date" class="form-control" id="fechaRequisicion" name="fechaRequisicion" value="<?= $productoRequisicion['fechaRequisicion'] ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="comidaPreparar" style="color: #000;">Comida a preparar</label>
                                        <input type="text" class="form-control" id="comidaPreparar" name="comidaPreparar" value="<?= $productoRequisicion['comidaPreparar'] ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="responsableEntrega" style="color: #000;">Responsable de entrega</label>
                                        <input type="text" class="form-control" id="responsableEntrega" name="responsableEntrega" value="<?= $productoRequisicion['responsableEntrega'] ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="responsableRecibe" style="color: #000;">Responsable de recepción</label>
                                        <input type="text" class="form-control" id="responsableRecibe" name="responsableRecibe" value="<?= $productoRequisicion['responsableRecibe'] ?>" disabled>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tabla de detalles -->
                    <div class="mb-4">
                        <h5 style="color: black;">Lotes de productos de la requisición</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Código de lote</th>
                                    <th>Fechas</th>
                                    <th>Unidades de caja</th>
                                    <th>Unidades por caja</th>
                                    <th>Total salida<br>(unidades)</th>
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
                                        <td><?= $detalle['descripcionProducto'] ?><br>Lote: <?= $detalle['codigoLote'] ?></td>
                                        <td>Ingreso: <?= date("d/m/Y", strtotime($detalle['fechaIngreso'])) ?><br>Vencimiento: <?= date("d/m/Y", strtotime($detalle['fechaVencimiento'])) ?></td>
                                        <td class="text-right"><?= $detalle['existenciaCaja'] ?><br><?= $detalle['udmCaja'] ?></td>
                                        <td class="text-right"><?= $detalle['existenciaIndividual'] ?><br><?= $detalle['udmIndividual'] ?></td>
                                        <td class="text-right"><?= $detalle['existenciaTotal'] ?> u</td>
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
