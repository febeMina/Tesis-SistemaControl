<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center">Editar Requisición de Productos</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <form action="<?= site_url('solicitud_productos/update/' . $solicitud['idSolicitudProductos']) ?>" method="post">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="Fecha_solicitud">Fecha</label>
                                <input type="date" name="Fecha_solicitud" class="form-control" value="<?= $solicitud['Fecha_solicitud'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="Comida_a_preparar">Comida a Preparar</label>
                                <input type="text" name="Comida_a_preparar" class="form-control" value="<?= $solicitud['Comida_a_preparar'] ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="responsable_entrega">Nombre del Solicitante</label>
                                <input type="text" name="responsable_entrega" class="form-control" value="<?= $solicitud['responsable_entrega'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="responsable_recibir">Nombre del que Entrega</label>
                                <input type="text" name="responsable_recibir" class="form-control" value="<?= $solicitud['responsable_recibir'] ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="productos">Productos</label>
                            <div id="productosContainer">
                                <?php foreach ($productos_solicitados as $index => $productoSolicitado): ?>
                                    <div class="productoRow mb-2">
                                        <select name="productos[<?= $index ?>][idProducto]" class="form-control productoSelect" required>
                                            <option value="">Seleccionar Producto</option>
                                            <?php foreach ($productos as $producto): ?>
                                                <option value="<?= $producto['idProducto'] ?>" data-fecha-vencimiento="<?= $producto['fecha_vencimiento'] ?>" <?= $producto['idProducto'] == $productoSolicitado['idProducto'] ? 'selected' : '' ?>>
                                                    <?= $producto['nombre'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="text" class="form-control fechaVencimientoInput" value="<?= $productoSolicitado['fecha_vencimiento'] ?>" readonly>
                                        <input type="number" name="productos[<?= $index ?>][cantidad]" class="form-control" value="<?= $productoSolicitado['cantidad'] ?>" required>
                                        <button type="button" class="btn btn-danger btn-remove-producto">Eliminar</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-primary" id="btnAddProducto">Agregar Producto</button>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Actualizar</button>
                            <a href="<?= site_url('solicitud_productos') ?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let productoIndex = <?= count($productos_solicitados) ?>;

    document.getElementById('btnAddProducto').addEventListener('click', function() {
        const productosContainer = document.getElementById('productosContainer');
        const newRow = document.createElement('div');
        newRow.classList.add('productoRow', 'mb-2');
        newRow.innerHTML = `
            <select name="productos[${productoIndex}][idProducto]" class="form-control productoSelect" required>
                <option value="">Seleccionar Producto</option>
                <?php foreach ($productos as $producto): ?>
                    <option value="<?= $producto['idProducto'] ?>" data-fecha-vencimiento="<?= $producto['fecha_vencimiento'] ?>">
                        <?= $producto['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="text" class="form-control fechaVencimientoInput" placeholder="Fecha de Vencimiento" readonly>
            <input type="number" name="productos[${productoIndex}][cantidad]" class="form-control" placeholder="Cantidad" required>
            <button type="button" class="btn btn-danger btn-remove-producto">Eliminar</button>
        `;
        productosContainer.appendChild(newRow);
        productoIndex++;
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-remove-producto')) {
            event.target.parentElement.remove();
        }
    });

    document.addEventListener('change', function(event) {
        if (event.target.classList.contains('productoSelect')) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            const fechaVencimiento = selectedOption.getAttribute('data-fecha-vencimiento');
            event.target.nextElementSibling.value = fechaVencimiento;
        }
    });
</script>

<?= $this->endSection() ?>
