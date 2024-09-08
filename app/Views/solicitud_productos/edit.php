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
                    <h4 class="header-title text-center">Continuar - N° de solicitud: <?= $productoIngreso['idProductoIngreso'] ?></h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Información de la solicitud -->
                    <div class="mb-4">
                        <h5 style="color: black;">Información de la solicitud</h5>
                        <form action="<?= site_url('solicitudproductos/update/' . $productoIngreso['idProductoIngreso']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="fechaIngreso" style="color: #000;">Fecha de ingreso</label>
                                        <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" value="<?= $productoIngreso['fechaIngreso'] ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="responsableEntrega" style="color: #000;">Responsable de entrega</label>
                                        <input type="text" class="form-control" id="responsableEntrega" name="responsableEntrega" value="<?= $productoIngreso['responsableEntrega'] ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="responsableRecibe" style="color: #000;">Responsable de recepción</label>
                                        <input type="text" class="form-control" id="responsableRecibe" name="responsableRecibe" value="<?= $productoIngreso['responsableRecibe'] ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <button type="submit" class="btn btn-primary mt-4">Actualizar Solicitud</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Formulario para agregar detalles de lote -->
                    <div class="mb-4">
                        <h5 style="color: black;">Agregar lote de producto</h5>
                        <form id="formLotes" action="<?= site_url('solicitudproductos/detalle') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" id="idProductoIngreso" name="idProductoIngreso" value="<?= $productoIngreso['idProductoIngreso'] ?>">
                            <div class="row">
                                <div class="col-md-4 mb-4">
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
                                <div class="col-md-4 mb-4">
                                    <div class="form-group">
                                        <label for="codigoLote" style="color: #000;">Código de lote</label>
                                        <input type="text" class="form-control" id="codigoLote" name="codigoLote" required>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="form-group">
                                        <label for="fechaIngreso" style="color: #000;">Fecha de ingreso</label>
                                        <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <div class="form-group">
                                        <label for="fechaVencimiento" style="color: #000;">Fecha de vencimiento</label>
                                        <input type="date" class="form-control" id="fechaVencimiento" name="fechaVencimiento" required>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
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
                                <div class="col-md-4 mb-4">
                                    <div class="form-group">
                                        <label for="existenciaCaja" style="color: #000;">Unidades de caja</label>
                                        <input type="number" class="form-control" id="existenciaCaja" name="existenciaCaja" min="0.00" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-4">
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
                                <div class="col-md-4 mb-4">
                                    <div class="form-group">
                                        <label for="existenciaIndividual" style="color: #000;">Unidades por caja</label>
                                        <input type="number" class="form-control" id="existenciaIndividual" name="existenciaIndividual" min="0.00" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="form-group">
                                        <label for="existenciaTotal" style="color: #000;">Total unidades a ingresar</label>
                                        <input type="number" class="form-control" id="existenciaTotal" name="existenciaTotal" min="0.00" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 mb-4">
                                    <?php if (session()->has('error')): ?>
                                        <div class="alert alert-danger">
                                            <?= session('error') ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (session()->has('success')): ?>
                                        <div class="alert alert-success">
                                            <?= session('success') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <button type="submit" class="btn btn-primary">Agregar Lote</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tabla de detalles -->
                    <div class="mb-4">
                        <h5 style="color: black;">Lotes de productos que ingresarán</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Código de lote</th>
                                    <th>Fechas</th>
                                    <th>Unidades de caja</th>
                                    <th>Unidades por caja</th>
                                    <th>Total ingreso<br>(unidades)</th>
                                    <th>Acciones</th>
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
                                        <td>
                                            <a href="<?= site_url('solicitudproductos/delete/' . $detalle['idProductoIngresoDetalle']) ?>" class="btn btn-danger"><i class="mdi mdi-delete"></i></a>
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
                    <div class="mb-4 text-right">
                        <form id="formFinalizar" action="<?= site_url('solicitudproductos/finalizar') ?>" method="post">
                            <input type="hidden" name="idProductoIngreso" value="<?= $productoIngreso['idProductoIngreso'] ?>">
                            <button type="submit" class="btn btn-primary">Finalizar solicitud</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
