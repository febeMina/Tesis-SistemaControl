<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-radius: 15px; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h4 class="header-title text-center">Editar producto: <?= $producto['descripcionProducto'] ?></h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <form action="<?= site_url('productos/update/' . $producto['idProducto']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="descripcionProducto" style="color: #000;">Nombre del producto</label>
                            <input type="text" class="form-control" id="descripcionProducto" name="descripcionProducto" value="<?= $producto['descripcionProducto'] ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="<?= previous_url() ?>" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>