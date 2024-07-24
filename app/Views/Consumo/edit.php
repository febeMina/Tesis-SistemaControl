<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h3 class="text-center">Editar Consumo</h3>
                </div>
                <div class="card-body">
                <form action="<?= site_url('consumo/update/' . $consumo['idConsumo']) ?>" method="post">
                        <div class="form-group">
                            <label for="fecha" style="color: #000;">Fecha:</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="<?= esc($consumo['fecha']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="idProducto" style="color: #000;">Producto:</label>
                            <select class="form-control" id="idProducto" name="idProducto" required>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?= $producto['idProducto'] ?>" <?= $producto['idProducto'] == $consumo['idProducto'] ? 'selected' : '' ?>>
                                        <?= $producto['nombre'] ?> - <?= $producto['fecha_vencimiento'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="saldo_inicial" style="color: #000;">Saldo inicial:</label>
                            <input type="number" class="form-control" id="saldo_inicial" name="saldo_inicial" value="<?= esc($consumo['saldo_inicial']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="salidas" style="color: #000;">Salidas:</label>
                            <input type="number" class="form-control" id="salidas" name="salidas" value="<?= esc($consumo['salidas']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="saldo" style="color: #000;">Saldo:</label>
                            <input type="number" class="form-control" id="saldo" name="saldo" value="<?= esc($consumo['saldo']) ?>" readonly>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
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

    // Llamar a updateSaldo al cargar la página
    updateSaldo();

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
