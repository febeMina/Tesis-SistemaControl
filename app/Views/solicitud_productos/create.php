<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Nueva Solicitud de Productos</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <form method="post" action="<?= site_url('solicitudproductos/store') ?>">
                        <div class="form-group">
                            <label for="Fecha_solicitud" style="color: #000;">Fecha de Solicitud:</label>
                            <input type="date" name="Fecha_solicitud" id="Fecha_solicitud" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="Comida_a_preparar" style="color: #000;">Comida a Preparar:</label>
                            <input type="text" name="Comida_a_preparar" id="Comida_a_preparar" class="form-control" required>
                        </div>


                        <div class="form-group">
                            <label for="responsable_entrega" style="color: #000;">Responsable de Entrega:</label>
                            <input type="text" name="responsable_entrega" id="responsable_entrega" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="responsable_recibir" style="color: #000;">Responsable de Recibir:</label>
                            <input type="text" name="responsable_recibir" id="responsable_recibir" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="productos" style="color: #000;">Productos:</label>
                            <div id="productos-container">
                                <!-- Aquí solo es necesario un bloque de producto para mostrar el select inicial -->
                                <div class="producto">
                                    <div class="form-group">
                                        <label for="producto_0" style="color: #000;">Producto:</label>
                                        <select name="productos[0][idProducto]" id="producto_0" class="form-control producto-select" required>
                                            <option value="">Selecciona un producto</option>
                                            <?php foreach ($consumos as $consumo) : ?>
                                                <option value="<?= $consumo['idProducto'] ?>"
                                                        data-descripcion="<?= $consumo['producto_descripcion'] ?>"
                                                        data-fecha_vencimiento="<?= $consumo['fecha_vencimiento'] ?>"
                                                        data-saldo="<?= $consumo['saldo'] ?>">
                                                    <?= $consumo['producto_nombre'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="cantidad_0" style="color: #000;">Cantidad:</label>
                                        <input type="number" name="productos[0][cantidad]" id="cantidad_0" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcion_0" style="color: #000;">Descripción:</label>
                                        <input type="text" id="descripcion_0" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_vencimiento_0" style="color: #000;">Fecha de Vencimiento:</label>
                                        <input type="text" id="fecha_vencimiento_0" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="saldo_0" style="color: #000;">Saldo:</label>
                                        <input type="text" id="saldo_0" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-product" class="btn btn-primary">Añadir otro producto</button>
                        </div>

                        <button type="submit" class="btn btn-success">Guardar Solicitud</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('productos-container').addEventListener('change', function(event) {
        if (event.target.matches('.producto-select')) {
            let select = event.target;
            let selectedOption = select.options[select.selectedIndex];
            let index = select.id.split('_')[1];

            console.log('Descripción:', selectedOption.getAttribute('data-descripcion'));
            console.log('Fecha de Vencimiento:', selectedOption.getAttribute('data-fecha_vencimiento'));
            console.log('Saldo:', selectedOption.getAttribute('data-saldo'));

            document.getElementById('descripcion_' + index).value = selectedOption.getAttribute('data-descripcion');
            document.getElementById('fecha_vencimiento_' + index).value = selectedOption.getAttribute('data-fecha_vencimiento');
            document.getElementById('saldo_' + index).value = selectedOption.getAttribute('data-saldo');
        }
    });

    document.getElementById('add-product').addEventListener('click', function() {
        let container = document.getElementById('productos-container');
        let index = container.querySelectorAll('.producto').length;
        let newProduct = document.createElement('div');
        newProduct.classList.add('producto');
        newProduct.innerHTML = `
            <div class="form-group">
                <label for="producto_${index}" style="color: #000;">Producto:</label>
                <select name="productos[${index}][idProducto]" id="producto_${index}" class="form-control producto-select" required>
                    <option value="">Selecciona un producto</option>
                    <?php foreach ($consumos as $consumo) : ?>
                        <option value="<?= $consumo['idProducto'] ?>"
                                data-descripcion="<?= $consumo['producto_descripcion'] ?>"
                                data-fecha_vencimiento="<?= $consumo['fecha_vencimiento'] ?>"
                                data-saldo="<?= $consumo['saldo'] ?>">
                            <?= $consumo['producto_nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="cantidad_${index}" style="color: #000;">Cantidad:</label>
                <input type="number" name="productos[${index}][cantidad]" id="cantidad_${index}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="descripcion_${index}" style="color: #000;">Descripción:</label>
                <input type="text" id="descripcion_${index}" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="fecha_vencimiento_${index}" style="color: #000;">Fecha de Vencimiento:</label>
                <input type="text" id="fecha_vencimiento_${index}" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="saldo_${index}" style="color: #000;">Saldo:</label>
                <input type="text" id="saldo_${index}" class="form-control" readonly>
            </div>
        `;
        container.appendChild(newProduct);
    });
    });
</script>

<?= $this->endSection() ?>
