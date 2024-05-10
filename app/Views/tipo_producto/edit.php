<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Editar Tipo de Producto</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Formulario de edición -->
                    <form action="<?= site_url('tipo_producto/update/' . $tipoProducto['idtipoProducto']) ?>" method="post">
                        <div class="form-group">
                            <label for="nombre"  style="color: #000;">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $tipoProducto['nombre'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion"  style="color: #000;">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required><?= $tipoProducto['descripcion'] ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>