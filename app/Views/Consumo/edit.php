<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0; background-color: #090066 !important;">
                    <h3 class="text-center">Editar Consumo</h3>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('consumo/update/' . $consumo['idConsumo']) ?>" method="post">
                        <div class="form-group">
                            <label for="fecha" style="color: #000;"><i class="fas fa-calendar"></i> Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="<?= $consumo['fecha'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="producto_id" style="color: #000;"><i class="fas fa-box"></i> Producto</label>
                            <select class="form-control" id="producto_id" name="producto_id" required>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?= $producto['idProducto'] ?>" <?= $producto['idProducto'] == $consumo['producto_id'] ? 'selected' : '' ?>><?= $producto['tamaño'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descripcion" style="color: #000;"><i class="fas fa-info-circle"></i> Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required><?= $consumo['descripcion'] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="fecha_vencimiento" style="color: #000;"><i class="fas fa-calendar"></i> Fecha de Vencimiento</label>
                            <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" value="<?= $consumo['fecha_vencimiento'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="saldo_inicial" style="color: #000;"><i class="fas fa-balance-scale"></i> Saldo Inicial</label>
                            <input type="number" class="form-control" id="saldo_inicial" name="saldo_inicial" value="<?= $consumo['saldo_inicial'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="salidas" style="color: #000;"><i class="fas fa-minus-circle"></i> Salidas</label>
                            <input type="number" class="form-control" id="salidas" name="salidas" value="<?= $consumo['salidas'] ?>" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #090066;">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
