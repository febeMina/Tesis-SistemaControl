<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center">Agregar Nuevo Proyecto</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <?= form_open('unidadesmedida/store') ?>
                    <div class="form-group">
                        <label for="nombre" style="color: #000;">Nombre de Proyecto</label>
                        <input type="text" class="form-control" id="nombreP" name="nombreP" required>
                    </div>
        
                    <div class="form-group">
                        <label for="descripción" style="color: #000;">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                       
                    <div class="form-group">
                        <label for="estado" style="color: #000;">Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="abreviatura" style="color: #000;">Meta Financiera</label>
                        <input type="tex" class="form-control" id="metaP" name="metaP" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>