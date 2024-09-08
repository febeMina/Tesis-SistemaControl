<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-radius: 15px; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h4 class="header-title text-center">Agregar Lote de producto</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <form action="<?= site_url('productos_lotes/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="idProducto" style="color: #000;">Producto</label>
                                    <select class="form-control" id="idProducto" name="idProducto" required>
                                        <option value="">Seleccionar el producto</option>
                                        <?php foreach ($productos as $producto) : ?>
                                            <option value="<?= $producto['idProducto'] ?>"><?= $producto['descripcionProducto'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="codigoLote" style="color: #000;">Código de lote</label>
                                    <input type="text" class="form-control" id="codigoLote" name="codigoLote" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="fechaIngreso" style="color: #000;">Fecha de ingreso</label>
                                    <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="fechaVencimiento" style="color: #000;">Fecha de vencimiento</label>
                                    <input type="date" class="form-control" id="fechaVencimiento" name="fechaVencimiento" required>
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
                                            <option value="<?= $caja['idUdmCaja'] ?>"><?= $caja['nombreCaja'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="existenciaCaja" style="color: #000;">Unidades de caja</label>
                                    <input type="number" class="form-control" id="existenciaCaja" name="existenciaCaja" min="0.00" step="0.01" required>
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
                                            <option value="<?= $individual['idUdmIndividual'] ?>"><?= $individual['nombreIndividual'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="existenciaIndividual" style="color: #000;">Unidades por caja</label>
                                     <input type="number" class="form-control" id="existenciaIndividual" name="existenciaIndividual" min="0.00" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-4">
                            </div>
                            <div class="col-6 mb-4">
                                <div class="form-group">
                                    <label for="existenciaTotal" style="color: #000;">Total unidades iniciales</label>
                                     <input type="number" class="form-control" id="existenciaTotal" name="existenciaTotal" min="0.00" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="<?= previous_url() ?>" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>