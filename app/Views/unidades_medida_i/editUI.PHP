<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center">Editar unidad de medida: Individual <br><?= $unidad['nombreIndividual'] ?></h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <?= form_open('unidadesindividuales/update/' . $unidad['idUdmIndividual']) ?>
                    <div class="form-group">
                        <label for="nombreIndividual" style="color: #000;">Nombre de la unidad de medida individual</label>
                        <input type="text" class="form-control" id="nombreIndividual" name="nombreIndividual" value="<?= $unidad['nombreIndividual'] ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="<?= previous_url() ?>" class="btn btn-secondary">Cancelar</a>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>