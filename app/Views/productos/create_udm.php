<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-radius: 15px; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h4 class="header-title text-center">Agregar producto</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <form action="<?= site_url('productos/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group row" style="color: #000;">
                            <div class="col-md-6">
                                <label for="idtipoProducto" class="form-label">Tipo de producto</label>
                                <select class="form-control" id="idtipoProducto" name="idtipoProducto" required>
                                    <option value="">Seleccionar tipo de producto</option>
                                    <?php foreach ($tiposProducto as $tipo) : ?>
                                        <option value="<?= $tipo->idtipoProducto ?>"><?= $tipo->nombre ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="codigo_lote">Codigo lote</label>
                                <input type="text" name="codigo_lote" class="form-control" id="codigo_lote" required>
                            </div>
                        </div>
                        <div class="form-group row" style="color: #000;">
                            <div class="col-md-6">
                                <label for="fechaIngreso" class="form-label">Fecha ingreso:</label>
                                <input type="text" class="form-control" id="fechaIngreso" name="fechaIngreso" value="<?= date('Y-m-d') ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_vencimiento" class="form-label">Fecha vencimiento:</label>
                                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
                            </div>
                        </div>
                        <div class="form-group row" style="color: #000;">
                            <div class="col-md-4">
                                <label for="n_unidades_Caja" class="form-label">N° Unidades de caja</label>
                                <input type="number" class="form-control" id="n_unidades_Caja" name="n_unidades_Caja" required min="0" oninput="validarNumero(this)">
                            </div>
                            <div class="col-md-4">
                                <label for="idUnidadesPorCaja" class="form-label">Unidad de medida por caja</label>
                                <select class="form-control" id="idUnidadesPorCaja" name="idUnidadesPorCaja" required onchange="calcularUnidadesCaja()">
                                    <option value="">Seleccionar unidad de medida</option>
                                    <?php foreach ($unidadesPorCaja as $unidad) : ?>
                                        <option value="<?= $unidad->idUnidadesPorCaja ?>" data-unidades="<?= $unidad->unidades ?>"><?= $unidad->tipo_unidad ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="unidades_caja" class="form-label">N° Unidades por caja</label>
                                <input type="text" class="form-control" id="unidades_caja" name="unidades_caja" readonly>
                            </div>
                        </div>
                        <div class="form-group row" style="color: #000;">
                            <div class="col-md-4">
                                <small id="unidades_extras_help" class="form-text text-muted">*Solo debe agregar una cantidad si desea ingresar unidades extras.</small>
                            </div>
                            <div class="col-md-8">
                                <label for="unidades_extras" class="form-label">+ N° unidades por caja extras</label>
                                <input type="number" class="form-control" id="unidades_extras" name="unidades_extras" min="0" oninput="validarNumero(this)">
                            </div>
                        </div>
                        <div class="form-group" style="color: #000;">
                            <label for="idUnidades_individuales" class="form-label">Tipo de unidad por caja</label>
                            <select class="form-control" id="idUnidades_individuales" name="idUnidades_individuales" required>
                                <option value="">Seleccionar unidad de medida</option>
                                <?php foreach ($unidadIndividual as $unidad) : ?>
                                    <option value="<?= $unidad->idUnidades_individuales ?>"><?= $unidad->unidadades_individuales ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Guardar</button>
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