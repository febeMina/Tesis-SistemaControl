<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center">Listado de Consumos</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario de filtros -->
                    <form method="get" action="<?= site_url('consumo') ?>" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="date" name="fecha" class="form-control" placeholder="Fecha" value="<?= isset($filters['fecha']) ? $filters['fecha'] : '' ?>">
                            </div>
                            <div class="col-md-4">
                                <select name="producto_id" class="form-control">
                                    <option value="">Producto</option>
                                    <?php foreach ($productos as $producto): ?>
                                        <option value="<?= $producto['idProducto'] ?>" <?= isset($filters['producto_id']) && $filters['producto_id'] == $producto['idProducto'] ? 'selected' : '' ?>><?= $producto['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="descripcion" class="form-control" placeholder="Descripción" value="<?= isset($filters['descripcion']) ? $filters['descripcion'] : '' ?>">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="submit" class="btn btn-primary">Filtrar</button>
                                    <a href="<?= site_url('consumo') ?>" class="btn btn-secondary ms-2">Limpiar</a>
                                </div>
                                <div>
                                    <a href="<?= site_url('consumo/create') ?>" class="btn btn-primary">
                                        <i class="mdi mdi-plus"> Agregar</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Tabla de consumos -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
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
                                        <td><?= esc($consumo['fecha']) ?></td>
                                        <td><?= esc($consumo['producto_id']) ?></td>
                                        <td><?= esc($consumo['descripcion']) ?></td>
                                        <td><?= esc($consumo['fecha_vencimiento']) ?></td>
                                        <td><?= esc($consumo['saldo_inicial']) ?></td>
                                        <td><?= esc($consumo['salidas']) ?></td>
                                        <td><?= esc($consumo['saldo_inicial'] - $consumo['salidas']) ?></td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Acciones">
                                                <a href="<?= site_url('consumo/edit/' . $consumo['idConsumo']) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <a href="<?= site_url('consumo/delete/' . $consumo['idConsumo']) ?>" class="btn btn-delete" onclick="return confirm('¿Estás seguro de que deseas eliminar este consumo?')">
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
