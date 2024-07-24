<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center text-white">Lista de Consumos por Producto</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <?php if (session()->get('success')): ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->get('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->get('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->get('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('consumo/index') ?>" method="get">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" name="producto_nombre" class="form-control" placeholder="Nombre del Producto" value="<?= isset($filters['producto_nombre']) ? esc($filters['producto_nombre']) : '' ?>">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="producto_descripcion" class="form-control" placeholder="Descripción del Producto" value="<?= isset($filters['producto_descripcion']) ? esc($filters['producto_descripcion']) : '' ?>">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="producto_fecha_vencimiento" class="form-control" placeholder="Fecha de Vencimiento" value="<?= isset($filters['producto_fecha_vencimiento']) ? esc($filters['producto_fecha_vencimiento']) : '' ?>">
                            </div>
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                                    <a href="<?= site_url('consumo/index') ?>" class="btn btn-secondary mt-3 ms-2">Limpiar</a>
                                </div>
                                <a href="<?= site_url('consumo/create') ?>" class="btn btn-success mt-3">Agregar Permiso</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table" style="color: #000; font-size: 0.9rem;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Producto</th>
                                    <th>Descripción</th>
                                    <th>Fecha de Vencimiento</th>
                                    <th>Saldo Inicial</th>
                                    <th>Salidas</th>
                                    <th>Saldo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($consumos as $consumo): ?>
                                    <tr>
                                        <td><?= esc($consumo['idConsumo']) ?></td>
                                        <td><?= esc($consumo['fecha']) ?></td>
                                        <td><?= esc($consumo['producto_nombre']) ?></td>
                                        <td><?= esc($consumo['producto_descripcion']) ?></td>
                                        <td><?= esc($consumo['producto_fecha_vencimiento']) ?></td>
                                        <td><?= esc($consumo['saldo_inicial']) ?></td>
                                        <td><?= esc($consumo['salidas']) ?></td>
                                        <td><?= esc($consumo['saldo']) ?></td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Acciones">
                                            <a href="<?= site_url('consumo/edit/' . $consumo['idConsumo']) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <a href="<?= site_url('consumo/delete/' . $consumo['idConsumo']) ?>" class="btn btn-delete" onclick="return confirm('¿Está seguro de eliminar este consumo?');">
                                                    <i class="mdi mdi-delete"></i>
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
