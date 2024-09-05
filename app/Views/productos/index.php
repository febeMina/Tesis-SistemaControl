<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<!-- Contenido de la página -->
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Listado de Productos</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <!-- Botón para agregar un nuevo producto -->
                    <div class="mb-3">
                        <a href="<?= site_url('productos/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Agregar<!-- Icono de Material Design Icons -->
                        </a>
                    </div>
                    <!-- Tabla de productos -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <!--<th>Fecha<br>Ingreso</th>-->
                                    <th>Codigo Lote</th>
                                    <th>Fecha<br>Vencimiento</th>
                                    <th>N°<br>Unidades</th>
                                    <th>Tipo de unidad</th>
                                    <th>N° Unidades<br>por caja</th>
                                    <th>+ N° Unidades<br>adicionales</th>
                                    <th>Tipo de unidad<br>individual</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos as $producto) : ?>
                                    <tr>
                                        <td><?= $producto->nombre_tipo; ?></td>
                                        <!--<td><?= $producto->fechaIngreso; ?></td>-->
                                        <td><?= $producto->codigo_lote; ?></td>
                                        <td><?= $producto->fecha_vencimiento; ?></td>
                                        <td><?= $producto->n_unidades_Caja; ?></td>
                                        <td><?= $producto->tipo_unidad_caja; ?></td>
                                        <td>
                                            <?php
                                            if ($producto->n_unidades_Caja == 0) {
                                                echo '0';
                                            } else {
                                                echo $producto->unidades_caja;
                                            }
                                            ?>
                                        </td>
                                        <td><?= $producto->unidades_extras; ?></td>
                                        <td><?= $producto->tipo_unidad_individual; ?></td>
                                        <td><?= $producto->total; ?></td><!-- resulatado -->
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= site_url('productos/edit/' . $producto->idProducto) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i> <!-- Icono de Material Design Icons -->
                                                </a>
                                                <a href="<?= site_url('productos/delete/' . $producto->idProducto) ?>" class="btn btn-delete">
                                                    <i class="mdi mdi-delete"></i> <!-- Icono de Material Design Icons -->
                                                </a>
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