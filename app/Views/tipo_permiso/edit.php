<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="card shadow" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h3 class="text-center">Editar Tipo de Permiso</h3>
                </div>
                <div class="card-body">
                    <form id="updateForm" action="<?= site_url('tipo_permiso/update') ?>" method="post">
                        <input type="hidden" name="id" value="<?= $tipo_permiso['idTipoPermiso'] ?>">
                        <div class="form-group">
                            <label for="nombre" style="color: #000;"><i class="fas fa-user"></i> Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $tipo_permiso['nombre'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="cantidad_dias" style="color: #000;"><i class="fas fa-key"></i> Cantidad de Días</label>
                            <input type="number" class="form-control" id="cantidad_dias" name="cantidad_dias" value="<?= $tipo_permiso['cantidad_dias'] ?>" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #090066;">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#updateForm').submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        window.location.href = "<?= site_url('tipo_permiso') ?>";
                    } else {
                        alert(response.message); // Mostrar mensaje de error
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Ocurrió un error al actualizar el tipo de permiso.');
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>
