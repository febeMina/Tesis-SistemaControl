<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Nuevo Consumo por Producto</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                <form method="post" action="<?= site_url('consumo/store') ?>">
                        <div class="form-group">
                            <label for="fecha" style="color: #000;">Fecha:</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="idProducto" style="color: #000;">Producto - Descripción - Fecha vencimiento:</label>
                            <select name="idProducto" id="idProducto" class="form-control" required>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?= $producto['idProducto'] ?>" data-descripcion="<?= esc($producto['descripcion']) ?>" data-vencimiento="<?= esc($producto['fecha_vencimiento']) ?>">
                                        <?= esc($producto['nombre']) ?> - <?= esc($producto['descripcion']) ?> (<?= esc($producto['fecha_vencimiento']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="saldo_inicial" style="color: #000;">Saldo inicial:</label>
                            <input type="number" name="saldo_inicial" id="saldo_inicial" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="salidas" style="color: #000;">Salidas:</label>
                            <input type="number" name="salidas" id="salidas" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="saldo" style="color: #000;">Saldo:</label>
                            <input type="number" name="saldo" id="saldo" class="form-control" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cargar jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Tu script JavaScript -->
<script>
$(document).ready(function() {
    function updateSaldo() {
        var saldo_inicial = parseInt($('#saldo_inicial').val()) || 0;
        var salidas = parseInt($('#salidas').val()) || 0;
        var saldo = saldo_inicial - salidas;
        $('#saldo').val(saldo);
    }

    $('#saldo_inicial, #salidas').on('input', function() {
        updateSaldo();
    });

    $('#idProducto').change(function() {
        var idProducto = $(this).val();

        // Limpiar campos al cambiar de producto
        $('#saldo_inicial').val('');
        $('#salidas').val('');
        $('#saldo').val('');

        // Obtener saldo inicial del producto seleccionado
        $.ajax({
            url: '<?= site_url('consumo/getSaldoInicial/') ?>' + idProducto,
            method: 'GET',
            success: function(response) {
                $('#saldo_inicial').val(response.saldo_inicial);
                updateSaldo();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
