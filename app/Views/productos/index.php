<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<!-- Contenido de la página -->
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Inventario general</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <!-- Mensaje de error -->
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <!-- Botón para agregar un nuevo producto -->
                    <div class="mb-3">
                        <a href="<?= site_url('productos/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Nuevo producto<!-- Icono de Material Design Icons -->
                        </a>
                        <a href="<?= site_url('productos_lotes/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Nuevo lote<!-- Icono de Material Design Icons -->
                        </a>
                    </div>
                    <!-- Tabla de productos -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Producto</th>
                                    <th>Próximo vencimiento</th>
                                    <th>Existencia actual<br>(unidades)</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $n = 0;
                                    foreach ($productos as $producto) : 
                                        $n++;
                                ?>
                                    <tr>
                                        <td><?= $n; ?></td>
                                        <td><?= $producto->descripcionProducto; ?></td>
                                        <td><?= ($producto->fechaVencimientoProxima == "" ? "-" : date("d/m/Y", strtotime($producto->fechaVencimientoProxima))) ?></td>
                                        <td class="text-right"><?= number_format($producto->totalExistencia, 2, ".", ",") ?> u</td>
                                        <td>
                                            <?php 
                                                if($producto->estado == "Activo") {
                                            ?>
                                                    <span class="badge badge-success">Activo</span>
                                            <?php 
                                                } else {
                                            ?>
                                                    <span class="badge badge-danger">Inactivo</span>
                                            <?php 
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <!-- Ver lotes -->
                                                <a href="<?= site_url('productos_lotes/' . $producto->idProducto) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-view-list"></i> Lotes <!-- Icono de Material Design Icons -->
                                                </a>
                                                <!-- Ver movimientos -->
                                                <a href="<?= site_url('productos/movimientos/' . $producto->idProducto) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-account-clock"></i> Movimientos <!-- Icono de Material Design Icons -->
                                                </a>
                                                <a href="<?= site_url('productos/edit/' . $producto->idProducto) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i> <!-- Icono de Material Design Icons -->
                                                </a>
                                                <?php 
                                                    if($producto->estado == "Activo") {
                                                ?>
                                                        <!-- Cambiar estado -->
                                                        <a href="<?= site_url('productos/estado/' . $producto->idProducto) ?>" class="btn btn-delete">
                                                            <i class="mdi mdi-sync"></i> <!-- Icono de Material Design Icons -->
                                                        </a>
                                                <?php 
                                                    } else {
                                                ?>
                                                        <!-- Cambiar estado -->
                                                        <a href="<?= site_url('productos/estado/' . $producto->idProducto) ?>" class="btn btn-success">
                                                            <i class="mdi mdi-sync"></i> <!-- Icono de Material Design Icons -->
                                                        </a>
                                                <?php 
                                                    }
                                                ?>
                                                <a href="<?= site_url('productos/delete/' . $producto->idProducto) ?>" class="btn btn-delete">
                                                    <i class="mdi mdi-delete"></i> <!-- Icono de Material Design Icons -->
                                                </a>
                                            </div>
                                        </td>
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