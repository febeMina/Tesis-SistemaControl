<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Editar unidad de medida: Por caja <br><?= $unidad['nombreCaja'] ?></h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <form action="<?= site_url('unidadesporcaja/update/' . $unidad['idUdmCaja']) ?>" method="post">
                        <div class="form-group" style="color: #000;">
                            <label for="nombreCaja">Nombre de la unidad de medida por caja</label>
                            <input type="text" name="nombreCaja" class="form-control" id="nombreCaja" value="<?= $unidad['nombreCaja'] ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="<?= previous_url() ?>" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>