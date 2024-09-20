<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h3 class="text-center">Editar grado</h3>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('grado/update/' . $grado['idGrado']) ?>" method="post">
                        <div class="form-group">
                            <label for="nombre" style="color: #000;">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $grado['nombre'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion" style="color: #000;">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= $grado['descripcion'] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="estado" style="color: #000;">Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo" <?= ($grado['estado'] == 'Activo') ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= ($grado['estado'] == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <a href="<?= base_url('public/grado') ?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
