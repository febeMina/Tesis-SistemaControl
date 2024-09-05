<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Nueva Solicitud de Productos</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('public/solicitudproductos/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="fecha_solicitud" style="color: #000;"><i class="fas fa-calendar"></i> Fecha de Solicitud</label>
                            <input type="date" name="Fecha_solicitud" id="fecha_solicitud" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="comida_preparar" style="color: #000;"><i class="fas fa-utensils"></i> Comida a Preparar</label>
                            <input type="text" name="Comida_a_preparar" id="comida_preparar" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="responsable_entrega" style="color: #000;"><i class="fas fa-user-check"></i> Responsable de Entrega</label>
                            <input type="text" name="responsable_entrega" id="responsable_entrega" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="responsable_recibir" style="color: #000;"><i class="fas fa-user-check"></i> Responsable de Recibir</label>
                            <input type="text" name="responsable_recibir" id="responsable_recibir" class="form-control" required>
                        </div>

                        <!-- Productos -->
                        <div id="productos-container">
                            <div class="producto mt-4">
                                <h4 class="text-center" style="color: #000;">Producto</h4>
                                <div class="form-group">
                                    <label for="producto_0" style="color: #000;"><i class="fas fa-box"></i> Producto</label>
                                    <select name="productos[0][idProducto]" id="producto_0" class="form-control producto-select" required>
                                        <option value="">Selecciona un producto</option>
                                        <?php foreach ($productos as $producto) : ?>
                                            <option value="<?= $producto['idProducto'] ?>"
                                                data-descripcion="<?= isset($producto['descripcion']) ? esc($producto['descripcion']) : '' ?>"
                                                data-saldo="<?= esc($producto['producto_saldo']) ?>"
                                                data-vencimiento="<?= esc($producto['fecha_vencimiento']) ?>">
                                                <?= esc($producto['producto_nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="producto_cantidad_0" style="color: #000;"><i class="fas fa-sort-numeric-up"></i> Cantidad</label>
                                    <input type="number" name="productos[0][cantidad]" id="producto_cantidad_0" class="form-control producto-cantidad" min="1" required>
                                </div>

                                <div class="form-group">
                                    <label for="producto_vencimiento_0" style="color: #000;"><i class="fas fa-calendar-alt"></i> Fecha de Vencimiento</label>
                                    <input type="date" name="productos[0][vencimiento]" id="producto_vencimiento_0" class="form-control producto-vencimiento" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="producto_saldo_0" style="color: #000;"><i class="fas fa-balance-scale"></i> Saldo</label>
                                    <input type="text" name="productos[0][saldo]" id="producto_saldo_0" class="form-control producto-saldo" readonly>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary mt-3" id="agregar-producto"><i class="fas fa-plus"></i> Agregar Producto</button>
                        <button type="submit" class="btn btn-success mt-4"><i class="fas fa-save"></i> Guardar Solicitud</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const agregarProductoBtn = document.getElementById('agregar-producto');
        const productosContainer = document.getElementById('productos-container');
        let productoIndex = 1;

        agregarProductoBtn.addEventListener('click', function() {
            const nuevoProducto = document.createElement('div');
            nuevoProducto.className = 'producto mt-4';

            nuevoProducto.innerHTML = `
                <h4 class="text-center" style="color: #000;">Producto</h4>
                <div class="form-group">
                    <label for="producto_${productoIndex}" style="color: #000;"><i class="fas fa-box"></i> Producto</label>
                    <select name="productos[${productoIndex}][idProducto]" id="producto_${productoIndex}" class="form-control producto-select" required>
                        <option value="">Selecciona un producto</option>
                        <?php foreach ($productos as $producto) : ?>
                            <option value="<?= $producto['idProducto'] ?>"
                                data-descripcion="<?= isset($producto['descripcion']) ? esc($producto['descripcion']) : '' ?>"
                                data-saldo="<?= esc($producto['producto_saldo']) ?>"
                                data-vencimiento="<?= esc($producto['fecha_vencimiento']) ?>">
                                <?= esc($producto['producto_nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="producto_cantidad_${productoIndex}" style="color: #000;"><i class="fas fa-sort-numeric-up"></i> Cantidad</label>
                    <input type="number" name="productos[${productoIndex}][cantidad]" id="producto_cantidad_${productoIndex}" class="form-control producto-cantidad" min="1" required>
                </div>

                <div class="form-group">
                    <label for="producto_vencimiento_${productoIndex}" style="color: #000;"><i class="fas fa-calendar-alt"></i> Fecha de Vencimiento</label>
                    <input type="date" name="productos[${productoIndex}][vencimiento]" id="producto_vencimiento_${productoIndex}" class="form-control producto-vencimiento" readonly>
                </div>

                <div class="form-group">
                    <label for="producto_saldo_${productoIndex}" style="color: #000;"><i class="fas fa-balance-scale"></i> Saldo</label>
                    <input type="text" name="productos[${productoIndex}][saldo]" id="producto_saldo_${productoIndex}" class="form-control producto-saldo" readonly>
                </div>
            `;

            productosContainer.appendChild(nuevoProducto);
            productoIndex++;
        });

        productosContainer.addEventListener('change', function(event) {
            if (event.target.classList.contains('producto-select')) {
                const selectedOption = event.target.selectedOptions[0];
                const saldo = selectedOption.dataset.saldo;
                const vencimiento = selectedOption.dataset.vencimiento;

                const productoContainer = event.target.closest('.producto');
                productoContainer.querySelector('.producto-vencimiento').value = vencimiento;
                productoContainer.querySelector('.producto-saldo').value = saldo;
            }
        });
    });
</script>

<?= $this->endSection() ?>
