<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-radius: 15px; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h4 class="header-title text-center">Agregar tipo de unidades</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <form action="<?= site_url('unidadesporcaja/store') ?>" method="post">
                        <div class="form-group" style="color: #000;">
                            <label for="tipo_unidad">Tipo de Unidad</label>
                            <input type="text" name="tipo_unidad" class="form-control" id="tipo_unidad" required>
                        </div>
                        <div class="form-group" style="color: #000;">
                            <label for="unidades">Número de Unidades</label>
                            <input type="number" name="unidades" class="form-control" id="unidades" required>
                        </div>
                        <div class="form-group" style="color: #000;">
                            <label for="estado">Estado</label>
                            <select name="estado" class="form-control" id="estado" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">agregar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>