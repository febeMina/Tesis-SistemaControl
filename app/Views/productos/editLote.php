<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-radius: 15px; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h4 class="header-title text-center">Editar lote de producto</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <form action="<?= site_url('productos_lotes/update/' . $lote['idProductoLote']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="idProducto" style="color: #000;">Producto</label>
                                    <select class="form-control" id="idProducto" name="idProducto" required>
                                        <option value="">Seleccionar el producto</option>
                                        <?php foreach ($productos as $producto) : ?>
                                            <option value="<?= $producto['idProducto'] ?>" <?= $producto['idProducto'] == $lote['idProducto'] ? 'selected' : '' ?>>
                                                <?= $producto['descripcionProducto'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="codigoLote" style="color: #000;">Código de lote</label>
                                    <input type="text" class="form-control" id="codigoLote" name="codigoLote" value="<?= $lote['codigoLote'] ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="fechaIngreso" style="color: #000;">Fecha de ingreso</label>
                                    <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" value="<?= $lote['fechaIngreso'] ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="fechaVencimiento" style="color: #000;">Fecha de vencimiento</label>
                                    <input type="date" class="form-control" id="fechaVencimiento" name="fechaVencimiento" value="<?= $lote['fechaVencimiento'] ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="idUdmCaja" style="color: #000;">Unidad de medida: Por caja</label>
                                    <select class="form-control" id="idUdmCaja" name="idUdmCaja" required>
                                        <option value="">Seleccionar unidad de medida</option>
                                        <?php foreach ($udmCaja as $caja) : ?>
                                            <option value="<?= $caja['idUdmCaja'] ?>" <?= $caja['idUdmCaja'] == $lote['idUdmCaja'] ? 'selected' : '' ?>>
                                                <?= $caja['nombreCaja'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="existenciaCaja" style="color: #000;">Unidades de caja</label>
                                    <input type="number" class="form-control" id="existenciaCaja" name="existenciaCaja" value="<?= $lote['existenciaCaja'] ?>" min="0.00" step="0.01" onkeyup="calcularExistenciaTotal();" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="idUdmIndividual" style="color: #000;">Unidad de medida: Individual</label>
                                    <select class="form-control" id="idUdmIndividual" name="idUdmIndividual" required>
                                        <option value="">Seleccionar unidad de medida</option>
                                        <?php foreach ($udmIndividual as $individual) : ?>
                                            <option value="<?= $individual['idUdmIndividual'] ?>" <?= $individual['idUdmIndividual'] == $lote['idUdmIndividual'] ? 'selected' : '' ?>>
                                                <?= $individual['nombreIndividual'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="existenciaIndividual" style="color: #000;">Unidades por caja</label>
                                     <input type="number" class="form-control" id="existenciaIndividual" name="existenciaIndividual" value="<?= $lote['existenciaIndividual'] ?>" min="0.00" step="0.01" onkeyup="calcularExistenciaTotal();" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-4" style="color: black;">
                                <b>Nota: </b> El total de unidades actuales ya fue ingresado al inventario y registrado sus movimientos, por lo que no puede modificarse.
                            </div>
                            <div class="col-6 mb-4">
                                <div class="form-group">
                                    <label for="existenciaTotal" style="color: #000;">Total unidades actuales</label>
                                     <input type="number" class="form-control" id="existenciaTotal" name="existenciaTotal" value="<?= $lote['existenciaTotal'] ?>" min="0.00" step="0.01" readonly required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="<?= previous_url() ?>" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
