<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<style>
    /* Estilos adicionales para mejorar la visibilidad */
    .card-header h3 {
        color: #ffffff; /* Texto blanco para el encabezado */
    }
    .form-label {
        color: #000000; /* Texto negro para las etiquetas de formulario */
    }
    .card-body {
        color: #000000; /* Texto negro para el cuerpo de la tarjeta */
    }
    .btn-primary, .btn-secondary, .btn-danger {
        color: #ffffff; /* Texto blanco para botones */
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center">Nueva Requisición de Productos</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <form action="<?= site_url('solicitud_productos/store') ?>" method="post">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="Fecha_solicitud" class="form-label">Fecha</label>
                                <input type="date" name="Fecha_solicitud" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="Comida_a_preparar" class="form-label">Comida a Preparar</label>
                                <input type="text" name="Comida_a_preparar" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="responsable_entrega" class="form-label">Nombre del Solicitante</label>
                                <input type="text" name="responsable_entrega" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="responsable_recibir" class="form-label">Nombre del que Entrega</label>
                                <input type="text" name="responsable_recibir" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="productos" class="form-label">Productos</label>
                            <div id="productosContainer">
                                <div class="productoRow mb-3">
                                    <select name="productos[0][idProducto]" class="form-control productoSelect" required>
                                        <option value="">Seleccionar Producto</option>
                                        <?php foreach ($productos as $producto): ?>
                                            <option value="<?= $producto['idProducto'] ?>" data-fecha-vencimiento="<?= $producto['fecha_vencimiento'] ?>">
                                                <?= $producto['nombre'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" class="form-control fechaVencimientoInput mt-2" placeholder="Fecha de Vencimiento" readonly>
                                    <input type="number" name="productos[0][cantidad]" class="form-control mt-2" placeholder="Cantidad" required>
                                    <button type="button" class="btn btn-danger btn-remove-producto mt-2">Eliminar</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary mt-3" id="btnAddProducto">Agregar Producto</button>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Guardar</button>
                            <a href="<?= site_url('solicitud_productos') ?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let productoIndex = 1;

    document.getElementById('btnAddProducto').addEventListener('click', function() {
        const productosContainer = document.getElementById('productosContainer');
        const newRow = document.createElement('div');
        newRow.classList.add('productoRow', 'mb-3');
        newRow.innerHTML = `
            <select name="productos[${productoIndex}][idProducto]" class="form-control productoSelect" required>
                <option value="">Seleccionar Producto</option>
                <?php foreach ($productos as $producto): ?>
                    <option value="<?= $producto['idProducto'] ?>" data-fecha-vencimiento="<?= $producto['fecha_vencimiento'] ?>">
                        <?= $producto['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="text" class="form-control fechaVencimientoInput mt-2" placeholder="Fecha de Vencimiento" readonly>
            <input type="number" name="productos[${productoIndex}][cantidad]" class="form-control mt-2" placeholder="Cantidad" required>
            <button type="button" class="btn btn-danger btn-remove-producto mt-2">Eliminar</button>
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
