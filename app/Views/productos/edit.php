<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-radius: 15px; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h4 class="header-title text-center">Editar Producto</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <form action="<?= site_url('productos/update/' . $producto->idProducto) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group row" style="color: #000;">
                            <div class="col-md-6">
                                <label for="idtipoProducto" class="form-label">Tipo de Producto</label>
                                <select class="form-control" id="idtipoProducto" name="idtipoProducto" required>
                                    <option value="">Seleccionar tipo de producto</option>
                                    <?php foreach ($tiposProducto as $tipo) : ?>
                                        <option value="<?= $tipo->idtipoProducto ?>" <?= $producto->idtipoProducto == $tipo->idtipoProducto ? 'selected' : '' ?>>
                                            <?= $tipo->nombre ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="codigo_lote">Codigo Lote</label>
                                <input type="text" class="form-control" id="codigo_lote" name="codigo_lote" value="<?= $producto->codigo_lote ?>" required>
                            </div>
                        </div>
                        <div class="form-group row" style="color: #000;">
                            <div class="col-md-6">
                                <label for="fecha_ingreso" class="form-label">Fecha Ingreso:</label>
                                <input type="text" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="<?= $producto->fecha_ingreso ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_vencimiento" class="form-label">Fecha Vencimiento:</label>
                                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" value="<?= $producto->fecha_vencimiento ?>" required>
                            </div>
                        </div>
                        <div class="form-group row" style="color: #000;">
                            <div class="col-md-4">
                                <label for="n_unidades_Caja" class="form-label">N° Unidades de Caja</label>
                                <input type="number" class="form-control" id="n_unidades_Caja" name="n_unidades_Caja" value="<?= $producto->n_unidades_Caja ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="idUnidadesPorCaja" class="form-label">Unidad de Medida por Caja</label>
                                <select class="form-control" id="idUnidadesPorCaja" name="idUnidadesPorCaja" required onchange="calcularUnidadesCaja()">
                                    <option value="">Seleccionar unidad de medida</option>
                                    <?php foreach ($unidadesPorCaja as $unidad) : ?>
                                        <option value="<?= $unidad->idUnidadesPorCaja ?>" data-unidades="<?= $unidad->unidades ?>" <?= $producto->idUnidadesPorCaja == $unidad->idUnidadesPorCaja ? 'selected' : '' ?>>
                                            <?= $unidad->tipo_unidad ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
    <label for="n_unidades_Caja">N° Unidades por caja</label>
    <input type="text" class="form-control" id="n_unidades_Caja" name="n_unidades_Caja" value="<?= $producto->n_unidades_Caja ?>">
</div>
                        </div>
                        <div class="form-group row" style="color: #000;">
                            <div class="col-md-4">
                                <small id="unidades_extras_help" class="form-text text-muted">*Solo debe agregar una cantidad si desea ingresar unidades extras.</small>
                            </div>
                            <div class="col-md-8">
                                <label for="unidades_extras" class="form-label">+ N° Unidades por caja Extras</label>
                                <input type="number" class="form-control" id="unidades_extras" name="unidades_extras" value="<?= $producto->unidades_extras ?>">
                            </div>
                        </div>
                        <div class="form-group" style="color: #000;">
                            <label for="idUnidades_individuales" class="form-label">Tipo de unidad por caja</label>
                            <select class="form-control" id="idUnidades_individuales" name="idUnidades_individuales" required>
                                <option value="">Seleccionar unidad de medida</option>
                                <?php foreach ($unidadIndividual as $unidad) : ?>
                                    <option value="<?= $unidad->idUnidades_individuales ?>" <?= $producto->idUnidades_individuales == $unidad->idUnidades_individuales ? 'selected' : '' ?>>
                                        <?= $unidad->unidadades_individuales ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function calcularUnidadesCaja() {
        var select = document.getElementById("idUnidadesPorCaja");
        var unidadesCajaInput = document.getElementById("unidades_caja");
        var nUnidadesCaja = document.getElementById("n_unidades_Caja").value;
        var selectedOption = select.options[select.selectedIndex];
        var unidadesCaja = selectedOption.getAttribute('data-unidades');

        if (parseInt(nUnidadesCaja) > 0) {
            unidadesCajaInput.value = unidadesCaja;
        } else {
            unidadesCajaInput.value = '0';
        }
    }

    document.getElementById('n_unidades_Caja').addEventListener('change', function() {
        var nUnidadesCaja = document.getElementById('n_unidades_Caja').value;
        var unidadesCajaInput = document.getElementById('unidades_caja');
        if (parseInt(nUnidadesCaja) > 0) {
            calcularUnidadesCaja();
        } else {
            unidadesCajaInput.value = '0';
        }
    });

    function validarNumero(input) {
        if (input.value < 0) {
            input.value = 0;
        }
        input.value = input.value.replace('e', '');
    }
</script>

<?= $this->endSection() ?>